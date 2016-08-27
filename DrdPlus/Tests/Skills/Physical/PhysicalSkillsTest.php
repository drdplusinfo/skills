<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Codes\WeaponCategoryCode;
use DrdPlus\Codes\WeaponCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\Skill;
use DrdPlus\Skills\SkillRank;
use DrdPlus\Skills\Physical\ArmorWearing;
use DrdPlus\Skills\Physical\Athletics;
use DrdPlus\Skills\Physical\Blacksmithing;
use DrdPlus\Skills\Physical\BoatDriving;
use DrdPlus\Skills\Physical\FightUnarmed;
use DrdPlus\Skills\Physical\FightWithAxes;
use DrdPlus\Skills\Physical\FightWithKnifesAndDaggers;
use DrdPlus\Skills\Physical\FightWithMacesAndClubs;
use DrdPlus\Skills\Physical\FightWithMorningstarsAndMorgensterns;
use DrdPlus\Skills\Physical\FightWithSabersAndBowieKnifes;
use DrdPlus\Skills\Physical\FightWithStaffsAndSpears;
use DrdPlus\Skills\Physical\FightWithSwords;
use DrdPlus\Skills\Physical\FightWithThrowingWeapons;
use DrdPlus\Skills\Physical\FightWithTwoWeapons;
use DrdPlus\Skills\Physical\FightWithVoulgesAndTridents;
use DrdPlus\Skills\Physical\Flying;
use DrdPlus\Skills\Physical\PhysicalSkill;
use DrdPlus\Skills\Physical\PhysicalSkills;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use DrdPlus\Tests\Skills\SameTypeSkillsTest;

class PhysicalSkillsTest extends SameTypeSkillsTest
{

    /**
     * @test
     * @dataProvider provideSkill
     * @param Skill $personSkill
     * @expectedException \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillAlreadySet
     */
    public function I_can_not_replace_skill(Skill $personSkill)
    {
        parent::I_can_not_replace_skill($personSkill);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Physical\Exceptions\UnknownPhysicalSkill
     */
    public function I_can_not_add_unknown_skill()
    {
        $skills = new PhysicalSkills();
        /** @var PhysicalSkill $strangePhysicalSkill */
        $strangePhysicalSkill = $this->mockery(PhysicalSkill::class);
        $skills->addPhysicalSkill($strangePhysicalSkill);
    }

    /**
     * @test
     */
    public function I_can_get_unused_skill_points_from_first_level()
    {
        $skills = new PhysicalSkills();
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
     * @return \Mockery\MockInterface|PhysicalSkill
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
            $skillRank = $this->mockery(SkillRank::class);
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
        $skills = new PhysicalSkills();
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
        $skills = new PhysicalSkills();
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
        $skills = new PhysicalSkills();
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
     * @expectedException \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     * @expectedExceptionMessageRegExp ~plank~
     */
    public function I_can_not_get_malus_for_melee_weapon_of_unknown_category()
    {
        $personPhysicalSkills = new PhysicalSkills();
        /** @var MissingWeaponSkillTable $missingWeaponSkillsTable */
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $personPhysicalSkills->getMalusToFightNumber(
            $this->createWeaponCode('plank', true /* is melee */, false /* not throwing */),
            $missingWeaponSkillsTable
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     * @expectedExceptionMessageRegExp ~artillery~
     */
    public function I_can_not_get_malus_for_non_melee_non_throwing_weapon()
    {
        $personPhysicalSkills = new PhysicalSkills();
        /** @var MissingWeaponSkillTable $missingWeaponSkillsTable */
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $personPhysicalSkills->getMalusToFightNumber(
            $this->createWeaponCode('artillery', false /* not melee */, false /* not throwing */),
            $missingWeaponSkillsTable
        );
    }
}