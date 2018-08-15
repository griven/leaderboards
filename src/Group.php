<?php
declare(strict_types=1);

namespace LeaderBoard;


class Group implements IArrayTransform
{
    const MAX_SIZE = 70;

    /** @var int $id */
    private $id;

    /** @var Type $type */
    private $type;

    /** @var MembersCollection $membersCollections*/
    private $membersCollections;

    /** @var array $counts */
    private $counts;

    public function __construct(int $id, Type $type, MembersCollection $membersCollection)
    {
        $this->id = $id;

        $this->type = $type;
        $this->setCounts();

        if ($membersCollection->getCount() > self::MAX_SIZE) {
            throw new \InvalidArgumentException('too much members');
        }

        $this->membersCollections = $membersCollection;
    }

    public function getType(): string
    {
        return $this->type->toString();
    }

    private function setCounts()
    {
        switch ($this->type->toString()) {
            case Type::WHALE:
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
            Type::WHALE => $kitCount,
            Type::PAYER => $payerCount,
            Type::DEFAULT => $defaultCount,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "type" => $this->getType(),
            "members" => $this->membersCollections->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        $type = new Type($data["type"]);
        $membersCollection = MembersCollection::fromArray($data['members']);
        return new self($data["id"], $type, $membersCollection);
    }

    public function addMember(Member $member)
    {
        $this->membersCollections->addMember($member);
    }
}
