<?php

namespace Services\Commissions;

use Entities\Amount;
use Entities\Transaction;

interface Commission
{
    public function calculate(Transaction $transaction): Amount;
}