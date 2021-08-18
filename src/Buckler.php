<?php

namespace Tournament;

class Buckler extends Equipment
{
    protected array $role = ["defense"];
    private bool $destroyed = false;
    private bool $activeThisTime = false;
    private int $axeAttacks = 0;

    public function calculateDefense(Equipment $attacker = null, int $attackPoints = 0): int
    {
        $defenseValue = 0;
        $this->activeThisTime = !$this->activeThisTime;
        $this->activeThisTime ? print_r("Buckler Active\n") : print_r("Buckler Inactive\n");
        if (!$this->destroyed && $this->activeThisTime) {
            if ($attacker && $attacker->isAxe()) {
                if (++$this->axeAttacks == 3) {
                    $this->destroyed = true;
                }
            }
            $defenseValue = $attackPoints;
        }
        return $defenseValue;
    }
}
