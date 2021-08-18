<?php

namespace Tournament;

abstract class Character
{
    protected array $initialEquipment = [];
    protected int $initialHitPoints = 0;

    protected array $equipmentArray = [];
    protected int $hitPoints;

    protected bool $vicious = false;
    protected bool $veteran = false;

    protected int $numberOfBlows = 0;

    protected bool $berzerk = false;
    protected string $name = "";


    /**
     *
     */
    public function __construct(string $modifier = null)
    {
        if ($modifier) {
            $this->$modifier = true;
        }
        $this->hitPoints = $this->initialHitPoints;
        foreach ($this->initialEquipment as $equipment) {
            $this->equip($equipment);
        }
    }

    /**
     * @param string $equipment
     *
     * @return Character
     */
    public function equip(string $equipment): Character
    {
        $classname = 'Tournament\\' . $equipment;
        $this->equipmentArray[] = new $classname();

        return $this;
    }

    /**
     * @param Character $defender
     *
     * @return Character
     */
    public function engage(Character $defender): Character
    {
        $turn = 1;

        while ($this->hitPoints() > 0 && $defender->hitPoints() > 0) {
            print_r("\nTurn " . $turn++ . "\n\n");
            print_r("$this->name started with $this->hitPoints\n");
            print_r("$defender->name started out with $defender->hitPoints\n");
            $defender->setHitPoints($defender->hitPoints() - $this->attack($this, $defender));
            print_r("Remaining Hit points after attack $defender->hitPoints\n");
            if ($defender->hitPoints() > 0) {
                $this->setHitPoints($this->hitPoints() - $defender->attack($defender, $this));
                print_r("Remaining Hit points after attack $this->hitPoints\n");
            }
        }
        return $this->hitPoints() > $defender->hitPoints() ? $this : $defender;
    }

    /**
     * @param Character $onAttack
     * @param Character $onDefence
     *
     * @return int
     */
    protected function attack(Character $onAttack, Character $onDefence): int
    {
        print_r("\n$onAttack->name attack $onDefence->name\n");
        $netAttack = 0;
        foreach ($onAttack->equipmentArray as $attackerEquipment) {
            if ($attackerEquipment->isWeapon()) {
                print_r("\n" . get_class($attackerEquipment) . "\n");
                $attackPoints = $attackerEquipment->calculateAttack();
                $attackPoints = $this->modify($attackPoints);
                $reducePoints = $defendPoints = 0;
                if ($attackPoints) {
                    $reducePoints = $onAttack->reduce();
                    $defendPoints = $onDefence->defend($attackerEquipment, $attackPoints);
                }

                $netAttack = $netAttack + $attackPoints - $reducePoints - $defendPoints;
                print_r("Attack: $attackPoints   Reduce: $reducePoints Defend: $defendPoints   Net: $netAttack\n");
            }
        }
        return $netAttack;
    }

    /**
     * @param Equipment $attackerEquipment
     * @param int $attackPoints
     *
     * @return int
     */
    public function defend(Equipment $attackerEquipment, int $attackPoints): int
    {
        $defensePoints = 0;
        foreach ($this->equipmentArray as $defenderEquipment) {
            if ($defenderEquipment->isDefense()) {
                $defensePoints = $defensePoints + $defenderEquipment->calculateDefense($attackerEquipment, $attackPoints);
            }
        }
        return $defensePoints < $attackPoints ? $defensePoints : $attackPoints;
    }

    /**
     * @return int
     */
    public function reduce(): int
    {
        $reducePoints = 0;
        foreach ($this->equipmentArray as $attackerEquipment) {
            if ($attackerEquipment->isReducer()) {
                $reducePoints = $attackerEquipment->calculateReduction();
            }
        }
        return $reducePoints;
    }

    /**
     * @return int
     */
    public function hitPoints(): int
    {
        return $this->hitPoints;
    }

    /**
     * @param int $newHitPoints
     *
     * @return int
     */
    protected function setHitPoints(int $newHitPoints): int
    {
        return $this->hitPoints = $newHitPoints;
    }

    abstract protected function modify(int $attackPoints): int;
}
