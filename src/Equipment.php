<?php

namespace Tournament;

class Equipment
{
    protected int $damage = 0;
    protected int $defense = 0;
    protected int $reduce = 0;
    protected array $role = [];
    public bool $isAxe = false;

    public function calculateAttack(): int
    {
        return $this->damage;
    }

    public function calculateDefense(Equipment $attacker = null, int $attackPoints = 0): int
    {
        return $this->defense;
    }

    public function calculateReduction(): int
    {
        return $this->reduce;
    }

    public function isWeapon(): bool
    {
        return in_array("weapon", $this->role);
    }

    public function isAxe(): bool
    {
        return $this->isAxe;
    }

    public function isDefense(): bool
    {
        return in_array("defense", $this->role);
    }

    public function isReducer(): bool
    {
        return in_array("reduce", $this->role);
    }
}
