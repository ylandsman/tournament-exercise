<?php

namespace Tournament;

class Armor extends Equipment
{
    protected array $role = ["defense", "reduce"];
    protected int $defense = 3;
    protected int $reduce = 1;
}
