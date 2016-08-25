<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Codes\WeaponCategoryCode;
use DrdPlus\Codes\WeaponCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Person\Skills\PersonSkillRank;
use DrdPlus\Person\Skills\Physical\ArmorWearing;
use DrdPlus\Person\Skills\Physical\Athletics;
use DrdPlus\Person\Skills\Physical\Blacksmithing;
use DrdPlus\Person\Skills\Physical\BoatDriving;
use DrdPlus\Person\Skills\Physical\FightUnarmed;
use DrdPlus\Person\Skills\Physical\FightWithAxes;
use DrdPlus\Person\Skills\Physical\FightWithKnifesAndDaggers;
use DrdPlus\Person\Skills\Physical\FightWithMacesAndClubs;
use DrdPlus\Person\Skills\Physical\FightWithMorningstarsAndMorgensterns;
use DrdPlus\Person\Skills\Physical\FightWithSabersAndBowieKnifes;
use DrdPlus\Person\Skills\Physical\FightWithStaffsAndSpears;
use DrdPlus\Person\Skills\Physical\FightWithSwords;
use DrdPlus\Person\Skills\Physical\FightWithThrowingWeapons;
use DrdPlus\Person\Skills\Physical\FightWithTwoWeapons;
use DrdPlus\Person\Skills\Physical\FightWithVoulgesAndTridents;
use DrdPlus\Person\Skills\Physical\Flying;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkill;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use DrdPlus\Tests\Person\Skills\PersonSameTypeSkillsTest;

class PersonPhysicalSkillsTest extends PersonSameTypeSkillsTest
{

    /**
     * @test
     * @dataProvider providePersonSkill
     * @param PersonSkill $personSkill
     * @expectedException \DrdPlus\Person\Skills\Physical\Exceptions\PhysicalSkillAlreadySet
     */
    public function I_can_not_replace_skill(PersonSkill $personSkill)
    {
        parent::I_can_not_replace_skill($personSkill);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Physical\Exceptions\UnknownPhysicalSkill
     */
    public function I_can_not_add_unknown_skill()
    {
        $skills = new PersonPhysicalSkills();
        /** @var PersonPhysicalSkill $strangePhysicalSkill */
        $strangePhysicalSkill = $this->mockery(PersonPhysicalSkill::class);
        $skills->addPhysicalSkill($strangePhysicalSkill);
    }

    /**
     * @test
     */
    public function I_can_get_unused_skill_points_from_first_level()
    {
        $skills = new PersonPhysicalSkills();
        $professionLevels = $this->createProfessionLevels(
            $firstLevelStrength = 123, $firstLevelAgility = 456, $nextLevelStrength = 321, $nextLevelAgility = 654
        );

        self::assertSame(
            $firstLevelStrength + $firstLevelAgility,
            $skills->getUnusedFirstLevelPhysicalSkillPointsValue($professionLevels)
        );

        $skills->addPhysicalSkill($this->createPhysicalSkill($usedRank = 3, 1, ArmorWearing::class));
        $skills->addPhysicalSkill($this->createPhysicalSkill($unusedRank = 2, 2, Athletics::class));
        self::assertSame(
            ($firstLevelStrength + $firstLevelAgility) - array_sum(range(1, $usedRank)),
            $skills->getUnusedFirstLevelPhysicalSkillPointsValue($professionLevels),
            'Expected ' . (($firstLevelStrength + $firstLevelAgility) - array_sum(range(1, $usedRank)))
        );
    }

    /**
     * @param int $firstLevelStrengthModifier
     * @param int $firstLevelAgilityModifier
     * @param int $nextLevelsStrengthModifier
     * @param int $nextLevelsAgilityModifier
     * @return \Mockery\MockInterface|ProfessionLevels
     */
    private function createProfessionLevels(
        $firstLevelStrengthModifier, $firstLevelAgilityModifier, $nextLevelsStrengthModifier, $nextLevelsAgilityModifier
    )
    {
        $professionLevels = $this->mockery(ProfessionLevels::class);
        $professionLevels->shouldReceive('getFirstLevelStrengthModifier')
            ->andReturn($firstLevelStrengthModifier);
        $professionLevels->shouldReceive('getFirstLevelAgilityModifier')
            ->andReturn($firstLevelAgilityModifier);
        $professionLevels->shouldReceive('getNextLevelsStrengthModifier')
            ->andReturn($nextLevelsStrengthModifier);
        $professionLevels->shouldReceive('getNextLevelsAgilityModifier')
            ->andReturn($nextLevelsAgilityModifier);

        return $professionLevels;
    }

    /**
     * @param int $finalSkillRankValue
     * @param int $levelValue
     * @param string $skillClass
     * @return \Mockery\MockInterface|PersonPhysicalSkill
     */
    private function createPhysicalSkill($finalSkillRankValue, $levelValue, $skillClass)
    {
        $combinedSkill = $this->mockery($skillClass);
        $professionLevel = $this->mockery(ProfessionLevel::class);
        $professionLevel->shouldReceive('isFirstLevel')
            ->andReturn($levelValue === 1);
        $professionLevel->shouldReceive('isNextLevel')
            ->andReturn($levelValue > 1);
        /** @var ProfessionLevel $professionLevel */
        $combinedSkill->shouldReceive('getSkillRanks')
            ->andReturn($this->createSkillRanks($finalSkillRankValue, $professionLevel));

        return $combinedSkill;
    }

    private function createSkillRanks($finalSkillRankValue, ProfessionLevel $professionLevel)
    {
        $skillRanks = [];
        for ($value = 1; $value <= $finalSkillRankValue; $value++) {
            $skillRank = $this->mockery(PersonSkillRank::class);
            $skillRank->shouldReceive('getValue')
                ->andReturn($value);
            $skillRank->shouldReceive('getProfessionLevel')
                ->andReturn($professionLevel);
            $skillRanks[] = $skillRank;
        }

        return $skillRanks;
    }

    /**
     * @test
     */
    public function I_can_get_unused_skill_points_from_next_levels()
    {
        $skills = new PersonPhysicalSkills();
        $professionLevels = $this->createProfessionLevels(
            $firstLevelStrength = 123, $firstLevelAgility = 456, $nextLevelsStrength = 321, $nextLevelsAgility = 654
        );

        self::assertSame($nextLevelsStrength + $nextLevelsAgility, $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels));
        $skills->addPhysicalSkill($this->createPhysicalSkill($rankFromFirstLevel = 2, 1, Blacksmithing::class));
        self::assertSame($nextLevelsStrength + $nextLevelsAgility, $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels));

        $skills->addPhysicalSkill($this->createPhysicalSkill($aRankFromNextLevel = 3, 2, BoatDriving::class));
        $skills->addPhysicalSkill($this->createPhysicalSkill($anotherRankFromNextLevel = 1, 3, Flying::class));
        self::assertSame(
            ($nextLevelsStrength + $nextLevelsAgility) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))),
            $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels),
            'Expected ' . (($nextLevelsStrength + $nextLevelsAgility) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))))
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_fight_with_melee_weapon_skills_at_once()
    {
        $skills = new PersonPhysicalSkills();
        $skills->addPhysicalSkill($fightUnarmed = new FightUnarmed($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithAxes = new FightWithAxes($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithKnifesAndDaggers = new FightWithKnifesAndDaggers($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithMacesAndClubs = new FightWithMacesAndClubs($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithMorningStarsAndMorgensterns = new FightWithMorningstarsAndMorgensterns($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithSabersAndBowieKnifes = new FightWithSabersAndBowieKnifes($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithStaffsAndSpears = new FightWithStaffsAndSpears($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithSwords = new FightWithSwords($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithThrowingWeapons = new FightWithThrowingWeapons($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithTwoWeapons = new FightWithTwoWeapons($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithVoulgesAndTridents = new FightWithVoulgesAndTridents($this->createProfessionFirstLevel()));

        self::assertSame(
            [
                $fightUnarmed,
                $fightWithAxes,
                $fightWithKnifesAndDaggers,
                $fightWithMacesAndClubs,
                $fightWithMorningStarsAndMorgensterns,
                $fightWithSabersAndBowieKnifes,
                $fightWithStaffsAndSpears,
                $fightWithSwords,
                $fightWithThrowingWeapons,
                $fightWithTwoWeapons,
                $fightWithVoulgesAndTridents
            ],
            $skills->getFightWithMeleeWeaponSkills()
        );
    }

    /**
     * @test
     * @dataProvider provideWeaponCategories
     * @param string $weaponCategory
     * @param $isMelee
     * @param $isThrowing
     */
    public function I_can_get_malus_for_every_type_of_weapon($weaponCategory, $isMelee, $isThrowing)
    {
        $skills = new PersonPhysicalSkills();
        $weaponCode = $this->createWeaponCode($weaponCategory, $isMelee, $isThrowing);
        self::assertSame(
            $expectedMalus = 'foo',
            $skills->getMalusToFightNumber(
                $weaponCode,
                $this->createMissingWeaponSkillsTable('fightNumber', 0 /* expected skill value */, $expectedMalus)
            )
        );
        self::assertSame(
            $expectedMalus = 'bar',
            $skills->getMalusToAttackNumber(
                $weaponCode,
                $this->createMissingWeaponSkillsTable('attackNumber', 0 /* expected skill value */, $expectedMalus)
            )
        );
        self::assertSame(
            $expectedMalus = 'baz',
            $skills->getMalusToCover(
                $weaponCode,
                $this->createMissingWeaponSkillsTable('cover', 0 /* expected skill value */, $expectedMalus)
            )
        );
        self::assertSame(
            $expectedMalus = 'qux',
            $skills->getMalusToBaseOfWounds(
                $weaponCode,
                $this->createMissingWeaponSkillsTable('baseOfWounds', 0 /* expected skill value */, $expectedMalus)
            )
        );
    }

    /**
     * @return array|string[][]
     */
    public function provideWeaponCategories()
    {
        return array_merge(
            array_map(
                function ($code) {
                    return [$code, true /* is melee */, false /* is not throwing */];
                },
                WeaponCategoryCode::getMeleeWeaponCategoryCodes()
            ),
            [['foo', false /* not melee */, true /* is throwing */]]
        );
    }

    /**
     * @param string $weaponCategory
     * @param bool $isMelee
     * @param bool $isThrowing
     * @return \Mockery\MockInterface|WeaponCode
     */
    private function createWeaponCode($weaponCategory, $isMelee, $isThrowing)
    {
        $weaponCode = $this->mockery(WeaponCode::class);
        $weaponCode->shouldReceive('isMeleeArmament')
            ->andReturn($isMelee);
        if ($isMelee) {
            $weaponCode->shouldReceive('convertToMeleeWeaponCodeEquivalent')
                ->andReturn($weaponCode);
        }
        $weaponCode->shouldReceive('isThrowingWeapon')
            ->andReturn($isThrowing);
        $weaponCode->shouldReceive('is' . implode(array_map('ucfirst', explode('_', $weaponCategory))))
            ->andReturn('true');
        $weaponCode->shouldIgnoreMissing(false /* return value for non-mocked methods */);
        $weaponCode->shouldReceive('__toString')
            ->andReturn((string)$weaponCategory);

        return $weaponCode;
    }

    /**
     * @param string $weaponParameterName
     * @param $expectedSkillValue
     * @param $result
     * @return \Mockery\MockInterface|MissingWeaponSkillTable
     */
    private function createMissingWeaponSkillsTable($weaponParameterName, $expectedSkillValue, $result)
    {
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $missingWeaponSkillsTable->shouldReceive('get' . ucfirst($weaponParameterName) . 'ForWeaponSkill')
            ->with($expectedSkillValue)
            ->andReturn($result);

        return $missingWeaponSkillsTable;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     * @expectedExceptionMessageRegExp ~plank~
     */
    public function I_can_not_get_malus_for_melee_weapon_of_unknown_category()
    {
        $personPhysicalSkills = new PersonPhysicalSkills();
        /** @var MissingWeaponSkillTable $missingWeaponSkillsTable */
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $personPhysicalSkills->getMalusToFightNumber(
            $this->createWeaponCode('plank', true /* is melee */, false /* not throwing */),
            $missingWeaponSkillsTable
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     * @expectedExceptionMessageRegExp ~artillery~
     */
    public function I_can_not_get_malus_for_non_melee_non_throwing_weapon()
    {
        $personPhysicalSkills = new PersonPhysicalSkills();
        /** @var MissingWeaponSkillTable $missingWeaponSkillsTable */
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $personPhysicalSkills->getMalusToFightNumber(
            $this->createWeaponCode('artillery', false /* not melee */, false /* not throwing */),
            $missingWeaponSkillsTable
        );
    }
}
