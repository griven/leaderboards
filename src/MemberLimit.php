<?php
declare(strict_types=1);

namespace LeaderBoard;


class MemberLimit
{
    const TOTAL_LIMIT = 70;

    /** @var int  */
    private $whaleLimit;

    /** @var int  */
    private $payerLimit;

    /** @var int  */
    private $defaultLimit;

    /**
     * MemberLimit constructor.
     * @param Type $type
     * @throws LeaderBoardException
     */
    public function __construct(Type $type)
    {
        switch ($type->toString()) {
            case Type::WHALE:
                $this->whaleLimit = 4;
                $this->payerLimit = 16;
                $this->defaultLimit = 50;
                break;
            case Type::PAYER:
                $this->whaleLimit = 0;
                $this->payerLimit = 20;
                $this->defaultLimit = 50;
                break;
            case Type::DEFAULT:
            default:
                $this->whaleLimit = 0;
                $this->payerLimit = 0;
                $this->defaultLimit = 70;
                break;
        }

        if ( ($this->whaleLimit + $this->payerLimit + $this->defaultLimit) != self::TOTAL_LIMIT) {
            throw new LeaderBoardException("bad limits");
        }
    }

    /**
     * @return int
     */
    public function getTotalLimit(): int
    {
        return self::TOTAL_LIMIT;
    }

    public function getLimitByType(Type $type)
    {
        switch ($type->toString()) {
            case Type::WHALE:
                return $this->getWhaleLimit();
            case Type::PAYER:
                return $this->getPayerLimit();
            case Type::DEFAULT:
            default:
                return $this->getDefaultLimit();
        }
    }

    /**
     * @return int
     */
    public function getWhaleLimit(): int
    {
        return $this->whaleLimit;
    }

    /**
     * @return int
     */
    public function getPayerLimit(): int
    {
        return $this->payerLimit;
    }

    /**
     * @return int
     */
    public function getDefaultLimit(): int
    {
        return $this->defaultLimit;
    }
}
