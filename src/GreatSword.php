<?php

namespace Tournament;

class GreatSword extends Equipment
{
    protected int $damage = 12;
    protected array $role = ["weapon"];
    private int $attackCycle = 3;
    private array $attackPossible = [true, true, false];
    private int $currentAttack = 0;

    public function calculateAttack(): int
    {
        $attackPoints = 0;
        if ($this->currentAttack >= $this->attackCycle) {
            $this->currentAttack = 0;
        }
        $this->attackPossible[$this->currentAttack] ? print_r("Great Sword Attacks\n") : print_r("Great Sword Doesn't Attack\n");
        if ($this->attackPossible[$this->currentAttack++]) {
            $attackPoints = parent::calculateAttack();
        }
        return $attackPoints;
    }
}
