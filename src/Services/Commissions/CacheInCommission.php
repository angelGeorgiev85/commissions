<?php

namespace Services\Commissions;

use Entities\Amount;
use Entities\Transaction;
use Services\Currencies;

class CacheInCommission implements Commission
{
    const COMMISSION_RATE = '0.0003';
    private $ceiling;

    public function __construct(Currencies $converter)
    {
        $this->ceiling = new Amount('5.0', 'EUR', $converter);
    }

    public function calculate(Transaction $transaction): Amount
    {
        return $transaction
            ->getAmount()
            ->multiply(self::COMMISSION_RATE)
            ->least($this->ceiling)
            ->convert($transaction->getAmount()->getCurrency())
            ->roundUp();
    }
}