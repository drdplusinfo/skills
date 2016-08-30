<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Codes\Armaments\RangeWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\Combined\BigHandwork;
use DrdPlus\Skills\Combined\Cooking;
use DrdPlus\Skills\Combined\FirstAid;
use DrdPlus\Skills\Combined\Gambling;
use DrdPlus\Skills\Combined\CombinedSkill;
use DrdPlus\Skills\Combined\CombinedSkills;
use DrdPlus\Skills\Combined\Seduction;
use DrdPlus\Skills\Skill;
use DrdPlus\Skills\SkillRank;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use DrdPlus\Tests\Skills\SameTypeSkillsTest;

class CombinedSkillsTest extends SameTypeSkillsTest
{

    /**
     * @test
     * @dataProvider provideSkill
     * @param Skill $skill
     * @expectedException \DrdPlus\Skills\Combined\Exceptions\CombinedSkillAlreadySet
     */
    public function I_can_not_replace_skill(Skill $skill)
    {
        parent::I_can_not_replace_skill($skill);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Combined\Exceptions\UnknownCombinedSkill
     */
    public function I_can_not_add_unknown_skill()
    {
        $skills = new CombinedSkills();
        /** @var CombinedSkill $strangeCombinedSkill */
        $strangeCombinedSkill = $this->mockery(CombinedSkill::class);
        $skills->addCombinedSkill($strangeCombinedSkill);
    }

    /**
     * @test
     */
    public function I_can_get_unused_skill_points_from_first_level()
    {
        $skills = new CombinedSkills();
        $professionLevels = $this->createProfessionLevels(
            $firstLevelKnack = 123, $firstLevelCharisma = 456, $nextLevelKnack = 321, $nextLevelCharisma = 654
        );

        self::assertSame(
            $firstLevelKnack + $firstLevelCharisma,
            $skills->getUnusedFirstLevelCombinedSkillPointsValue($professionLevels)
        );

        $skills->addCombinedSkill($this->createCombinedSkill($usedRank = 3, 1, BigHandwork::class));
        $skills->addCombinedSkill($this->createCombinedSkill($unusedRank = 2, 2, Cooking::class));
        self::assertSame(
            ($firstLevelKnack + $firstLevelCharisma) - array_sum(range(1, $usedRank)),
            $skills->getUnusedFirstLevelCombinedSkillPointsValue($professionLevels),
            'Expected ' . (($firstLevelKnack + $firstLevelCharisma) - array_sum(range(1, $usedRank)))
        );
    }

    /**
     * @param int $firstLevelKnackModifier
     * @param int $firstLevelCharismaModifier
     * @param int $nextLevelsKnackModifier
     * @param int $nextLevelsCharismaModifier
     * @return \Mockery\MockInterface|ProfessionLevels
     */
    private function createProfessionLevels(
        $firstLevelKnackModifier, $firstLevelCharismaModifier, $nextLevelsKnackModifier, $nextLevelsCharismaModifier
    )
    {
        $professionLevels = $this->mockery(ProfessionLevels::class);
        $professionLevels->shouldReceive('getFirstLevelKnackModifier')
            ->andReturn($firstLevelKnackModifier);
        $professionLevels->shouldReceive('getFirstLevelCharismaModifier')
            ->andReturn($firstLevelCharismaModifier);
        $professionLevels->shouldReceive('getNextLevelsKnackModifier')
            ->andReturn($nextLevelsKnackModifier);
        $professionLevels->shouldReceive('getNextLevelsCharismaModifier')
            ->andReturn($nextLevelsCharismaModifier);

        return $professionLevels;
    }

    /**
     * @param int $finalSkillRankValue
     * @param int $levelValue
     * @param string $skillClass
     * @return \Mockery\MockInterface|CombinedSkill
     */
    private function createCombinedSkill($finalSkillRankValue, $levelValue, $skillClass)
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
        $skills = new CombinedSkills();
        $professionLevels = $this->createProfessionLevels(
            $firstLevelKnack = 123, $firstLevelCharisma = 456, $nextLevelsKnack = 321, $nextLevelsCharisma = 654
        );

        self::assertSame($nextLevelsKnack + $nextLevelsCharisma, $skills->getUnusedNextLevelsCombinedSkillPointsValue($professionLevels));
        $skills->addCombinedSkill($this->createCombinedSkill($rankFromFirstLevel = 2, 1, FirstAid::class));
        self::assertSame($nextLevelsKnack + $nextLevelsCharisma, $skills->getUnusedNextLevelsCombinedSkillPointsValue($professionLevels));

        $skills->addCombinedSkill($this->createCombinedSkill($aRankFromNextLevel = 3, 2, Gambling::class));
        $skills->addCombinedSkill($this->createCombinedSkill($anotherRankFromNextLevel = 1, 3, Seduction::class));
        self::assertSame(
            ($nextLevelsKnack + $nextLevelsCharisma) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))),
            $skills->getUnusedNextLevelsCombinedSkillPointsValue($professionLevels),
            'Expected ' . (($nextLevelsKnack + $nextLevelsCharisma) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))))
        );
    }

    /**
     * @test
     * @dataProvider provideWeaponCategories
     * @param string $rangeWeaponCategory
     */
    public function I_can_get_malus_for_every_type_of_weapon($rangeWeaponCategory)
    {
        $combinedSkills = new CombinedSkills();
        self::assertSame(
            $expectedMalus = 'foo',
            $combinedSkills->getMalusToFightNumber(
                $this->createRangeWeaponCode($rangeWeaponCategory),
                $this->createMissingWeaponSkillsTable('fightNumber', 0 /* expected skill value */, $expectedMalus)
            )
        );
        self::assertSame(
            $expectedMalus = 'bar',
            $combinedSkills->getMalusToAttackNumber(
                $this->createRangeWeaponCode($rangeWeaponCategory),
                $this->createMissingWeaponSkillsTable('attackNumber', 0 /* expected skill value */, $expectedMalus)
            )
        );
        self::assertSame(
            $expectedMalus = 'baz',
            $combinedSkills->getMalusToCover(
                $this->createRangeWeaponCode($rangeWeaponCategory),
                $this->createMissingWeaponSkillsTable('cover', 0 /* expected skill value */, $expectedMalus)
            )
        );
        self::assertSame(
            $expectedMalus = 'qux',
            $combinedSkills->getMalusToBaseOfWounds(
                $this->createRangeWeaponCode($rangeWeaponCategory),
                $this->createMissingWeaponSkillsTable('baseOfWounds', 0 /* expected skill value */, $expectedMalus)
            )
        );
    }

    /**
     * @return array|string[][]
     */
    public function provideWeaponCategories()
    {
        return [
            [WeaponCategoryCode::BOW],
            [WeaponCategoryCode::CROSSBOW],
        ];
    }

    /**
     * @param $weaponCategory
     * @return \Mockery\MockInterface|RangeWeaponCode
     */
    private function createRangeWeaponCode($weaponCategory)
    {
        $code = $this->mockery(RangeWeaponCode::class);
        $code->shouldReceive('is' . ucfirst($weaponCategory))
            ->andReturn('true');
        $code->shouldIgnoreMissing(false /* return value for non-mocked methods */);
        $code->shouldReceive('__toString')
            ->andReturn((string)$weaponCategory);

        return $code;
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
     * @expectedException \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     * @expectedExceptionMessageRegExp ~notBowNorCrossbowYouKnow~
     */
    public function I_can_not_get_malus_for_weapon_not_affected_by_combined_skill()
    {
        $combinedSkills = new CombinedSkills();
        /** @var MissingWeaponSkillTable $missingWeaponSkillsTable */
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $combinedSkills->getMalusToFightNumber(
            $this->createRangeWeaponCode('notBowNorCrossbowYouKnow'),
            $missingWeaponSkillsTable
        );
    }

}