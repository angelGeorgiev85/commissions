<?php

namespace Entities;

use DateTimeImmutable;

class Transaction
{
    private $date;
    private $user_type;
    private $operation;
    private $amount;

    public function __construct(
        DateTimeImmutable $date,
        UserType $user_type,
        Operation $operation,
        Amount $amount
    )
    {
        $this->date = $date;
        $this->user_type = $user_type;
        $this->operation = $operation;
        $this->amount = $amount;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getUserType(): UserType
    {
        return $this->user_type;
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

}