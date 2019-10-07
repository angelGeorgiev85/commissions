<?php

namespace Services\Transform;

use Services\CommissionCalculator;

class TransactionToCommission
{
    private $calculator;

    public function __construct(CommissionCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function transactionToCommission($transactions)
    {
        $commissions = [];
        foreach($transactions as $transaction) {
            $commission = $this->calculator->calculateCommission($transaction);
            $commissions[] = $commission;
        }
        return $commissions;
    }
}