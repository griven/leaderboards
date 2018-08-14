<?php
declare(strict_types=1);

namespace LeaderBoard;


class Member
{
    private $type;
    private $id;
    private $score;

    /**
     * Member constructor.
     * @param Type $type
     * @param int $id
     * @param int $score
     */
    public function __construct(Type $type, int $id, int $score)
    {
        $this->type = $type;
        $this->id = $id;
        $this->score = $score;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }



}
