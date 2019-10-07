<?php

namespace Services\Transform;

use Entities\UserType;
use Entities\Amount;
use Entities\Operation;
use Entities\Transaction;
use DateTimeImmutable;
use Services\Currencies;

class CsvLineToTransaction
{

    private $currencies;

    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;

    }

    public function csvLineToTransaction($args){
        $transactions = [];
        foreach($args as $arg){
            $date = $this->buildDate($arg[0]);
            $user_type = $this->buildUserType($arg[1], $arg[2]);
            $operation = $this->buildOperation($arg[3]);
            $amount = $this->buildAmount($arg[4], $arg[5]);

            $transaction = new Transaction($date, $user_type, $operation, $amount);
            $transactions[] = $transaction;
        }

        return $transactions;
    }

    private function buildDate(string $value): DateTimeImmutable
    {
        $date = DateTimeImmutable::createFromFormat($date_format = 'Y-m-d', $value);
        if ($date instanceof DateTimeImmutable && $date->format($date_format) == $value) {
            $date = $date->setTime(0, 0, 0);
        } else {
            throw new \DomainException("Value $value is not a Y-m-d format date");
        }

        return $date;
    }

    private function buildUserType(string $raw_id, string $raw_type): UserType
    {
        $user_id = filter_var($raw_id, FILTER_VALIDATE_INT);
        if (false !== $user_id) {
            $user_id = intval($user_id);
        } else {
            throw new \DomainException("Value $raw_id is not a numeric id");
        }
        if ($raw_type == 'natural') {
            $user_type = UserType::TYPE_NATURAL;
        } elseif ($raw_type == 'legal') {
            $user_type = UserType::TYPE_LEGAL;
        } else {
            throw new \DomainException("Value $raw_type is not valid user type");
        }

        return new UserType($user_id, $user_type);
    }

    private function buildOperation(string $raw_type): Operation
    {
        if ($raw_type == 'cash_in') {
            $operation_type = Operation::CASH_IN;
        } elseif ($raw_type == 'cash_out') {
            $operation_type = Operation::CASH_OUT;
        } else {
            throw new \DomainException("Value $raw_type is not valid opration type");
        }

        return new Operation($operation_type);
    }

    private function buildAmount(string $raw_amount, string $raw_currency): Amount
    {
        $amount = filter_var($raw_amount, FILTER_VALIDATE_FLOAT);
        if (false === $amount) {
            throw new \DomainException("Value $raw_amount is not a valid amount of money");
        }

        return new Amount($amount, $raw_currency, $this->currencies);
    }
}