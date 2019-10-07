<?php

namespace Entities;

class UserType
{
    const TYPE_NATURAL = 1;
    const TYPE_LEGAL = 2;

    private $id;
    private $type;

    public function __construct(int $id, int $type)
    {
        if (!in_array($type, [self::TYPE_NATURAL, self::TYPE_LEGAL])) {
            throw new \DomainException('Unknown user type');
        }

        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): int
    {
        return $this->type;
    }
}