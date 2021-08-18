<?php

declare(strict_types=1);

namespace Tournament;

/**
 * Class Swordsman.
 */
class Swordsman extends Character
{
    protected int $initialHitPoints = 100;
    protected array $initialEquipment = ["OneHandSword"];
    protected string $name = "Swordsman";

    protected function modify(int $attackPoints): int
    {
        if ($this->vicious && $this->numberOfBlows++ < 2) {
            print_r("Attack Modification $attackPoints modified to ");
            $attackPoints += 20;
            print_r("$attackPoints because of  vicious\n");
        }
        return $attackPoints;
    }
}
