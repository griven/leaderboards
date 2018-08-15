<?php
declare(strict_types=1);

namespace LeaderBoard;


interface IArrayTransform
{
    public function toArray(): array;

    public static function fromArray(array $data);
}
