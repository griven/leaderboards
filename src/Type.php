<?php
declare(strict_types=1);

namespace LeaderBoard;


class Type
{
    const KIT = "kit";
    const PAYER = "payer";
    const DEFAULT = "default";

    const POSSIBLE_VALUES = [
        self::KIT,
        self::PAYER,
        self::DEFAULT,
    ];

    private $type;

    public function __construct(string $type)
    {
        if (!in_array($type, self::POSSIBLE_VALUES)) {
            throw new \InvalidArgumentException("unknown TYPE");
        }

        $this->type = $type;
    }

    public function toString(): string
    {
        return $this->type;
    }
}
