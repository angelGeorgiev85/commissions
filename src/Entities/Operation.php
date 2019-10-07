<?php

namespace Entities;

class Operation
{
    const CASH_IN = 1;
    const CASH_OUT = 2;

    private $type;

    public function __construct(int $type)
    {
        if (!in_array($type, [self::CASH_IN, self::CASH_OUT])) {
            throw new \DomainException('Unknown operation type');
        }

        $this->type = $type;
    }

    public function getType(): int
    {
        return $this->type;
    }
}