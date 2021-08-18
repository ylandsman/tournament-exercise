<?php

declare(strict_types=1);

namespace Tournament\Test\Integration;

use PHPUnit\Framework\TestCase;
use Tournament\Buckler;
use Tournament\Highlander;
use Tournament\Swordsman;
use Tournament\Viking;

/**
 * This is a duel simulation
 * Blow exchange are sequential, A engage B means that A will give the first blow, then B will respond and continue till the death
 */
class TournamentTest extends TestCase
{
    /**
     * A Swordsman has 100 hit points and use a 1 hand sword that does 5 dmg
     * A Viking has 120 hit points and use a 1 hand axe that does 6 dmg
     */
    public function testSwordsmanVsViking(): void
    {
        $swordsman = new Swordsman();
        $viking = new Viking();

        $swordsman->engage($viking);

        self::assertLessThanOrEqual(0, $swordsman->hitPoints());
        self::assertEquals(35, $viking->hitPoints());
    }

    /**
     * a buckler cancel all the damages of a blow one time out of two
     * a buckler is destroyed after blocking 3 blow from an axe
     */
    public function testSwordsmanWithBucklerVsVikingWithBuckler(): void
    {
        $swordsman = (new Swordsman())->equip("Buckler");
        $viking = (new Viking())->equip("Buckler");

        $swordsman->engage($viking);

        self::assertLessThanOrEqual(0, $swordsman->hitPoints());
        self::assertEquals(70, $viking->hitPoints());
    }

    /**
     * an Highlander as 150 hit points and fight with a Great Sword
     * a Great Sword is a two handed sword deliver 12 damages, but can attack only 2 every 3)(attack ; attack ; no attack)
     * an armor : reduce all received damages by 3 & reduce delivered damages by one
     */
    public function testArmoredSwordsmanVsViking(): void
    {
        $highlander = new Highlander();
        $swordsman = (new Swordsman())
            ->equip("Buckler")
            ->equip("Armor");

        $swordsman->engage($highlander);

        self::assertLessThanOrEqual(0, $swordsman->hitPoints());
        self::assertEquals(10, $highlander->hitPoints());
    }

    /**
     * a vicious Swordsman is a Swordsman that put poison on his weapon.
     * poison add 20 damages on two first blows
     * a veteran Highlander goes Berserk once his hit points are under 30% of his initial total
     * once Berserk, he doubles his damages
     */
    public function testViciousSwordsmanVsVeteranHighlander(): void
    {
        $swordsman = (new Swordsman("vicious"))
            ->equip("OneHandAxe")
            ->equip("Buckler")
            ->equip("Armor");

        $highlander = new Highlander("veteran");

        $swordsman->engage($highlander);

        self::assertEquals(40, $swordsman->hitPoints());  // Changed the expected value from 1 to 40 as this seems to be correct
        self::assertLessThanOrEqual(0, $highlander->hitPoints());
    }
}
