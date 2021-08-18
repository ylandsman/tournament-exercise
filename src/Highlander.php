<?php

declare(strict_types=1);

namespace Tournament;

/**
 * Class Highlander.
 */
class Highlander extends Character
{
    protected int $initialHitPoints = 150;
    protected array $initialEquipment = ["GreatSword"];
    protected bool $berzerk = false;
    protected string $name = "Highlander";

    protected function modify(int $attackPoints): int
    {
        if ($this->veteran && $this->hitPoints() < $this->initialHitPoints * 0.3) {
            $this->berzerk = true;
        }
        if ($this->berzerk) {
            print_r("Attack modification $attackPoints modified to ");
            $attackPoints *= 2;
            print_r("$attackPoints because of berzerk\n");
        }
        return $attackPoints;
    }
}
