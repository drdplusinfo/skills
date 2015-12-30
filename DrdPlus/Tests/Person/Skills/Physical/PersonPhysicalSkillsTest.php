<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Person\Skills\PersonSkillRank;
use DrdPlus\Person\Skills\Physical\ArmorWearing;
use DrdPlus\Person\Skills\Physical\Athletics;
use DrdPlus\Person\Skills\Physical\Blacksmithing;
use DrdPlus\Person\Skills\Physical\BoatDriving;
use DrdPlus\Person\Skills\Physical\Flying;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkill;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Tests\Person\Skills\AbstractTestOfPersonSameTypeSkills;

class PersonPhysicalSkillsTest extends AbstractTestOfPersonSameTypeSkills
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

        $this->assertSame(
            $firstLevelStrength + $firstLevelAgility,
            $skills->getUnusedFirstLevelPhysicalSkillPointsValue($professionLevels)
        );

        $skills->addPhysicalSkill($this->createPhysicalSkill($usedRank = 3, 1, ArmorWearing::class));
        $skills->addPhysicalSkill($this->createPhysicalSkill($unusedRank = 2, 2, Athletics::class));
        $this->assertSame(
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

        $this->assertSame($nextLevelsStrength + $nextLevelsAgility, $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels));
        $skills->addPhysicalSkill($this->createPhysicalSkill($rankFromFirstLevel = 2, 1, Blacksmithing::class));
        $this->assertSame($nextLevelsStrength + $nextLevelsAgility, $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels));

        $skills->addPhysicalSkill($this->createPhysicalSkill($aRankFromNextLevel = 3, 2, BoatDriving::class));
        $skills->addPhysicalSkill($this->createPhysicalSkill($anotherRankFromNextLevel = 1, 3, Flying::class));
        $this->assertSame(
            ($nextLevelsStrength + $nextLevelsAgility) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))),
            $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels),
            'Expected ' . (($nextLevelsStrength + $nextLevelsAgility) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))))
        );
    }

}
