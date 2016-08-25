<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Person\Skills\Physical\PhysicalSkillRank;
use DrdPlus\Person\Skills\Physical\ShieldUsage;
use DrdPlus\Tables\Armaments\Shields\MissingShieldSkillTable;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;

class ShieldUsageTest extends PersonPhysicalSkillTest
{
    /**
     * @test
     */
    public function I_can_get_malus_to_fight_number()
    {
        $missingWeaponsSkillTable = $this->createMissingWeaponsSkillTable();
        $shieldUsage = new ShieldUsage($this->createProfessionFirstLevel());

        $missingWeaponsSkillTable->shouldReceive('getFightNumberForWeaponSkill')
            ->with(0)
            ->atLeast()->once()
            ->andReturn(123);
        $missingShieldsSkillTable = $this->createMissingShieldsSkillTable();
        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkillRank')
            ->with(0)
            ->atLeast()->once()
            ->andReturn(456);
        self::assertSame(123, $shieldUsage->getMalusToFightNumber($missingShieldsSkillTable, -5, $missingWeaponsSkillTable));

        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(1, $shieldUsage));
        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(2, $shieldUsage));
        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(3, $shieldUsage));
        $missingShieldsSkillTable = $this->createMissingShieldsSkillTable();
        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkillRank')
            ->with(3)
            ->andReturn(3);
        self::assertSame(121, $shieldUsage->getMalusToFightNumber($missingShieldsSkillTable, -5, $missingWeaponsSkillTable));
    }

    /**
     * @return \Mockery\MockInterface|MissingShieldSkillTable
     */
    private function createMissingShieldsSkillTable()
    {
        return $this->mockery(MissingShieldSkillTable::class);
    }

    /**
     * @return \Mockery\MockInterface|MissingWeaponSkillTable
     */
    private function createMissingWeaponsSkillTable()
    {
        return $this->mockery(MissingWeaponSkillTable::class);
    }

    /**
     * @param $value
     * @param PersonSkill $personSkill
     * @return \Mockery\MockInterface|PhysicalSkillRank
     */
    private function createPhysicalSkillRank($value, PersonSkill $personSkill)
    {
        $physicalSkillRank = $this->mockery(PhysicalSkillRank::class);
        $physicalSkillRank->shouldReceive('getValue')
            ->andReturn($value);
        $physicalSkillRank->shouldReceive('getPersonSkill')
            ->andReturn($personSkill);

        return $physicalSkillRank;
    }

    /**
     * @test
     */
    public function I_can_get_restriction_with_shield()
    {
        $missingShieldsSkillTable = $this->createMissingShieldsSkillTable();
        $shieldUsage = new ShieldUsage($this->createProfessionFirstLevel());

        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkillRank')
            ->with(0)
            ->andReturn(3);
        self::assertSame(-2, $shieldUsage->getRestrictionWithShield($missingShieldsSkillTable, -5));

        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(1, $shieldUsage));
        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkillRank')
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

        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkillRank')
            ->with(0)
            ->andReturn(456);
        self::assertSame(0, $shieldUsage->getRestrictionWithShield($missingShieldsSkillTable, -5));

        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(1, $shieldUsage));
        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(2, $shieldUsage));
        $missingShieldsSkillTable->shouldReceive('getRestrictionBonusForSkillRank')
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

        $missingWeaponsSkillTable->shouldReceive('getAttackNumberForWeaponSkill')
            ->with(0)// it should be always called with zero (because there is nothing like 'Fight with shield' skill)
            ->andReturn(-456);
        self::assertSame(-456, $shieldUsage->getMalusToAttackNumber($missingWeaponsSkillTable));

        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(1, $shieldUsage));
        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(2, $shieldUsage));
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

        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(1, $shieldUsage));
        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(2, $shieldUsage));
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

        $missingWeaponsSkillTable->shouldReceive('getBaseOfWoundsForWeaponSkill')
            ->with(0)// it should be always called with zero (because there is nothing like 'Fight with shield' skill)
            ->andReturn(-123);
        self::assertSame(-123, $shieldUsage->getMalusToBaseOfWounds($missingWeaponsSkillTable));

        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(1, $shieldUsage));
        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(2, $shieldUsage));
        $shieldUsage->addSkillRank($this->createPhysicalSkillRank(3, $shieldUsage));
        self::assertSame(
            -123,
            $shieldUsage->getMalusToBaseOfWounds($missingWeaponsSkillTable),
            'I should get same malus to base of wounds regardless to shield usage skill'
        );
    }
}