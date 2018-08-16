<?php
declare(strict_types=1);

namespace LeaderBoard;


class Group implements IArrayTransform
{
    /** @var int $id */
    private $id;

    /** @var Type $type */
    private $type;

    /** @var MembersCollection $membersCollections*/
    private $membersCollections;

    public function __construct(int $id, Type $type, MembersCollection $membersCollection = null)
    {
        $this->id = $id;

        $this->type = $type;

        $this->membersCollections = $membersCollection ?? new MembersCollection();
        $this->membersCollections->setLimit(new MemberLimit($this->type));
    }

    public function getType(): string
    {
        return $this->type->toString();
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
            "isFull" => $this->membersCollections->isFull(),
        ];
    }

    public static function fromArray(array $data): self
    {
        $type = new Type($data["type"]);
        $membersCollection = MembersCollection::fromArray($data['members']);
        $membersCollection->setLimit(new MemberLimit($type));
        return new self($data["id"], $type, $membersCollection);
    }

    public function addMember(Member $member): bool
    {
        return $this->membersCollections->addMember($member);
    }

    public static function createForMember(int $id, Member $member): self
    {
        $memberCollection = new MembersCollection([$member]);

        return new self($id, $member->getType(), $memberCollection);
    }

    /**
     * Только для отладки
     * @return array
     */
    public function getCount(): array
    {
        return [
            $this->membersCollections->getCount(),
            $this->membersCollections->getCountByType(new Type(Type::WHALE)),
            $this->membersCollections->getCountByType(new Type(Type::PAYER)),
            $this->membersCollections->getCountByType(new Type(Type::DEFAULT)),
            $this->membersCollections->isFull() ? "+" : '-',
        ];
    }
}
