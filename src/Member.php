<?php
declare(strict_types=1);

namespace LeaderBoard;


class Member implements IArrayTransform
{
    private $type;
    private $id;
    private $score;
    private $level;

    /**
     * Member constructor.
     * @param Type $type
     * @param int $id
     * @param int $level
     * @param int $score
     */
    public function __construct(Type $type, int $id, int $level, int $score)
    {
        $this->type = $type;
        $this->id = $id;
        $this->level = $level;
        $this->score = $score;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    public function toArray(): array
    {
        return [
            "type" => $this->getType()->toString(),
            "id" => $this->getId(),
            "level" => $this->getLevel(),
            "score" => $this->getScore(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(new Type($data['type']), $data['id'], $data['level'], $data['score']);
    }

}
