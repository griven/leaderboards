<?php
declare(strict_types=1);

namespace LeaderBoard;


class Group
{
    const MAX_SIZE = 70;

    private $id;

    private $type;

    private $members;

    private $counts;

    public function __construct(int $id, Type $type, array $members = [])
    {
        $this->id = $id;

        $this->type = $type;
        $this->setCounts();


        $this->members = $members;
        if (count($this->members) > self::MAX_SIZE) {
            throw new \InvalidArgumentException('too much members');
        }
    }

    public function getType(): string
    {
        return $this->type->toString();
    }

    private function setCounts()
    {
        switch ($this->type->toString()) {
            case Type::KIT:
                $kitCount = 4;
                $payerCount = 16;
                $defaultCount = 50;
                break;
            case Type::PAYER:
                $kitCount = 0;
                $payerCount = 20;
                $defaultCount = 50;
                break;
            case Type::DEFAULT:
            default:
                $kitCount = 0;
                $payerCount = 0;
                $defaultCount = 70;
                break;
        }

        if ( ($kitCount + $payerCount + $defaultCount) != self::MAX_SIZE) {
            throw new \Exception("bad max size");
        }

        $this->counts = [
            Type::KIT => $kitCount,
            Type::PAYER => $payerCount,
            Type::DEFAULT => $defaultCount,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMembers(): iterable
    {
        return $this->members;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "type" => $this->getType(),
            "members" => $this->getMembers(),
        ];
    }

    public static function fromArray($data): self
    {
        $type = new Type($data["type"]);
        return new self($data["id"], $type, []);
    }
}
