<?php

namespace Tournament;

class OneHandAxe extends Equipment
{
    protected int $damage = 6;
    protected array $role = ["weapon"];
    public bool $isAxe = true;
}
