<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Skills\Physical\ShieldUsage;
use DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable;
use DrdPlus\Tables\Armaments\Weapons\WeaponSkillTable;

class ShieldUsageTest extends PhysicalSkillTest
{
    /**
     * @test
     */
    public function I_can_get_malus_to_fight_number()
    {
        $missingWeaponsSkillTable = $this->createMissingWeaponsSkillTable();
        $shieldUsage = new ShieldUsage($this->createProfessionFirstLevel());

        $missingWeaponsSkillTable->shouldReceive('getFightNumberMalusForSkill')
            ->with(0)
            ->atLeast()->once()
            ->andReturn(123);
        $missingShieldsSkillTable = $this->createMissingShieldsSkillTable();
        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkill')
            ->with(0)
            ->atLeast()->once()
            ->andReturn(456);
        self::assertSame(123, $shieldUsage->getMalusToFightNumber($missingShieldsSkillTable, -5, $missingWeaponsSkillTable));

        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $missingShieldsSkillTable = $this->createMissingShieldsSkillTable();
        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkill')
            ->with(3)
            ->andReturn(3);
        self::assertSame(121, $shieldUsage->getMalusToFightNumber($missingShieldsSkillTable, -5, $missingWeaponsSkillTable));
    }

    /**
     * @return \Mockery\MockInterface|ShieldUsageSkillTable
     */
    private function createMissingShieldsSkillTable()
    {
        return $this->mockery(ShieldUsageSkillTable::class);
    }

    /**
     * @return \Mockery\MockInterface|WeaponSkillTable
     */
    private function createMissingWeaponsSkillTable()
    {
        return $this->mockery(WeaponSkillTable::class);
    }

    /**
     * @test
     */
    public function I_can_get_restriction_with_shield()
    {
        $missingShieldsSkillTable = $this->createMissingShieldsSkillTable();
        $shieldUsage = new ShieldUsage($this->createProfessionFirstLevel());

        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkill')
            ->with(0)
            ->andReturn(3);
        self::assertSame(-2, $shieldUsage->getRestrictionWithShield($missingShieldsSkillTable, -5));

        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkill')
            ->with(1)
            ->andReturn(5);
        self::assertSame(-7, $shieldUsage->getRestrictionWithShield($missingShieldsSkillTable, -12));
    }

    /**
     * @test
     */
    public function I_get_zero_as_restriction_with_shield_even_if_bonus_is_higher_than_malus()
    {
        $shieldUsage = new ShieldUsage($this->createProfessionFirstLevel());
        $missingShieldsSkillTable = $this->createMissingShieldsSkillTable();

        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkill')
            ->with(0)
            ->andReturn(456);
        self::assertSame(0, $shieldUsage->getRestrictionWithShield($missingShieldsSkillTable, -5));

        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkill')
            ->with(2)
            ->andReturn(10);
        self::assertSame(0, $shieldUsage->getRestrictionWithShield($missingShieldsSkillTable, -10));
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_attack_number_always_as_with_zero_skill()
    {
        $shieldUsage = new ShieldUsage($this->createProfessionFirstLevel());
        $missingWeaponsSkillTable = $this->createMissingWeaponsSkillTable();

        $missingWeaponsSkillTable->shouldReceive('getAttackNumberMalusForSkill')
            ->with(0)// it should be always called with zero (because there is nothing like 'Fight with shield' skill)
            ->andReturn(-456);
        self::assertSame(-456, $shieldUsage->getMalusToAttackNumber($missingWeaponsSkillTable));

        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        self::assertSame(
            -456,
            $shieldUsage->getMalusToAttackNumber($missingWeaponsSkillTable),
            'I should get same malus to attack number regardless to shield usage skill'
        );
    }

    /**
     * @test
     */
    public function I_can_cover_with_shield()
    {
        $shieldUsage = new ShieldUsage($this->createProfessionFirstLevel());
        $missingShieldsSkillTable = $this->createMissingShieldsSkillTable();

        $missingShieldsSkillTable->shouldReceive('getCoverForSkillRank')
            ->with(0)
            ->andReturn(5);
        self::assertSame(5, $shieldUsage->getMalusToCover($missingShieldsSkillTable));

        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $missingShieldsSkillTable->shouldReceive('getCoverForSkillRank')
            ->with(2)
            ->andReturn(11);
        self::assertSame(11, $shieldUsage->getMalusToCover($missingShieldsSkillTable));
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_base_of_wounds_always_as_with_zero_skill()
    {
        $shieldUsage = new ShieldUsage($this->createProfessionFirstLevel());
        $missingWeaponsSkillTable = $this->createMissingWeaponsSkillTable();

        $missingWeaponsSkillTable->shouldReceive('getBaseOfWoundsMalusForSkill')
            ->with(0)// it should be always called with zero (because there is nothing like 'Fight with shield' skill)
            ->andReturn(-123);
        self::assertSame(-123, $shieldUsage->getMalusToBaseOfWounds($missingWeaponsSkillTable));

        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        $shieldUsage->increaseSkillRank($this->createSkillPoint());
        self::assertSame(
            -123,
            $shieldUsage->getMalusToBaseOfWounds($missingWeaponsSkillTable),
            'I should get same malus to base of wounds regardless to shield usage skill'
        );
    }
}