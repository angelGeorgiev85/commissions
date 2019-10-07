<?php

namespace Services;

use Entities\UserType;
use Entities\Amount;
use Entities\Operation;
use Entities\Transaction;
use Services\Commissions\CacheInCommission;
use Services\Commissions\CacheOutLegalCommission;
use Services\Commissions\CacheOutNaturalCommission;
use Services\Commissions\Commission;

class CommissionCalculator
{
    private $cache_in;
    private $cache_out_legal;
    private $cache_out_natural;

    public function __construct(Currencies $converter)
    {
        $this->cache_in = new CacheInCommission($converter);
        $this->cache_out_legal = new CacheOutLegalCommission($converter);
        $this->cache_out_natural = new CacheOutNaturalCommission($converter);
    }

    public function calculateCommission(Transaction $transaction): Amount
    {
        return $this
            ->pickStrategy($transaction)
            ->calculate($transaction);
    }

    protected function pickStrategy(Transaction $transaction): Commission
    {
        if ($transaction->getOperation()->getType() === Operation::CASH_IN) {
            return $this->cache_in;
        } elseif ($transaction->getOperation()->getType() === Operation::CASH_OUT
            && $transaction->getUserType()->getType() == UserType::TYPE_LEGAL) {
            return $this->cache_out_legal;
        } elseif ($transaction->getOperation()->getType() === Operation::CASH_OUT
            && $transaction->getUserType()->getType() == UserType::TYPE_NATURAL) {
            return $this->cache_out_natural;
        } else {
            throw new \DomainException('Unexpected transaction type');
        }
    }
}