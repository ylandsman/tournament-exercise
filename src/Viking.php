<?php

declare(strict_types=1);

namespace Tournament;

/**
 * Class Viking.
 */
class Viking extends Character
{
    protected int $initialHitPoints = 120;
    protected array $initialEquipment = ["OneHandAxe"];
    protected string $name = "Viking";

    protected function modify(int $attackPoints): int
    {
        return $attackPoints;
    }
}
