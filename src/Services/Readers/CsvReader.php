<?php
namespace Services\Readers;

use Services\CommissionCalculator;
use Services\Currencies;
use Services\Transform\CsvLineToTransaction;
use Services\Transform\TransactionToCommission;

class CsvReader implements Reader
{
    private $infile;
    private $currencies;

    public function __construct(string $infile, $currencies)
    {
        $this->infile = $infile;
        $this->currencies = new Currencies($currencies);
    }

    public function execute(): array
    {
        $transactions = [];
        $csvLineToTransaction = new CsvLineToTransaction($this->currencies);
        $csvLineToTransaction = $csvLineToTransaction->csvLineToTransaction($this->readLines());

        $transactionToCommission = new TransactionToCommission(new CommissionCalculator($this->currencies));
        $transactionToCommission = $transactionToCommission->transactionToCommission($csvLineToTransaction);

        foreach($transactionToCommission as $commission){
            $transactions[] = $commission->getAmount();
        }

        return $transactions;
    }

    function readLines(): array
    {
        if (!($file = fopen($this->infile, 'r'))) {
            throw new \RuntimeException('Failed to open file');
        }

        $lines = [];
        while (is_array($line = fgetcsv($file, null, ','))) {
            $lines[] = $line;
        }

        return $lines;
    }
}