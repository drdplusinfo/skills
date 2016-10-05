<?php
namespace DrdPlus\Tests\Skills\Psychical;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\Skill;
use DrdPlus\Skills\SkillRank;
use DrdPlus\Skills\Psychical\Astronomy;
use DrdPlus\Skills\Psychical\Botany;
use DrdPlus\Skills\Psychical\Mythology;
use DrdPlus\Skills\Psychical\PsychicalSkill;
use DrdPlus\Skills\Psychical\PsychicalSkills;
use DrdPlus\Skills\Psychical\Technology;
use DrdPlus\Skills\Psychical\Zoology;
use DrdPlus\Tests\Skills\SameTypeSkillsTest;

class PsychicalSkillsTest extends SameTypeSkillsTest
{

    /**
     * @test
     * @dataProvider provideSkill
     * @param Skill $skill
     * @expectedException \DrdPlus\Skills\Psychical\Exceptions\PsychicalSkillAlreadySet
     */
    public function I_can_not_replace_skill(Skill $skill)
    {
        parent::I_can_not_replace_skill($skill);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Psychical\Exceptions\UnknownPsychicalSkill
     */
    public function I_can_not_add_unknown_skill()
    {
        $skills = new PsychicalSkills();
        /** @var PsychicalSkill $strangePsychicalSkill */
        $strangePsychicalSkill = $this->mockery(PsychicalSkill::class);
        $skills->addPsychicalSkill($strangePsychicalSkill);
    }

    /**
     * @test
     */
    public function I_can_get_unused_skill_points_from_first_level()
    {
        $skills = new PsychicalSkills();
        $professionLevels = $this->createProfessionLevels(
            $firstLevelWill = 123, $firstLevelIntelligence = 456, $nextLevelWill = 321, $nextLevelIntelligence = 654
        );

        self::assertSame(
            $firstLevelWill + $firstLevelIntelligence,
            $skills->getUnusedFirstLevelPsychicalSkillPointsValue($professionLevels)
        );

        $skills->addPsychicalSkill($this->createPsychicalSkill($usedRank = 3, 1, Astronomy::class));
        $skills->addPsychicalSkill($this->createPsychicalSkill($unusedRank = 2, 2, Botany::class));
        self::assertSame(
            ($firstLevelWill + $firstLevelIntelligence) - array_sum(range(1, $usedRank)),
            $skills->getUnusedFirstLevelPsychicalSkillPointsValue($professionLevels),
            'Expected ' . (($firstLevelWill + $firstLevelIntelligence) - array_sum(range(1, $usedRank)))
        );
    }

    /**
     * @param int $firstLevelWillModifier
     * @param int $firstLevelIntelligenceModifier
     * @param int $nextLevelsWillModifier
     * @param int $nextLevelsIntelligenceModifier
     * @return \Mockery\MockInterface|ProfessionLevels
     */
    private function createProfessionLevels(
        $firstLevelWillModifier, $firstLevelIntelligenceModifier, $nextLevelsWillModifier, $nextLevelsIntelligenceModifier
    )
    {
        $professionLevels = $this->mockery(ProfessionLevels::class);
        $professionLevels->shouldReceive('getFirstLevelWillModifier')
            ->andReturn($firstLevelWillModifier);
        $professionLevels->shouldReceive('getFirstLevelIntelligenceModifier')
            ->andReturn($firstLevelIntelligenceModifier);
        $professionLevels->shouldReceive('getNextLevelsWillModifier')
            ->andReturn($nextLevelsWillModifier);
        $professionLevels->shouldReceive('getNextLevelsIntelligenceModifier')
            ->andReturn($nextLevelsIntelligenceModifier);

        return $professionLevels;
    }

    /**
     * @param int $finalSkillRankValue
     * @param int $levelValue
     * @param string $skillClass
     * @return \Mockery\MockInterface|PsychicalSkill
     */
    private function createPsychicalSkill($finalSkillRankValue, $levelValue, $skillClass)
    {
        $professionLevel = $this->mockery(ProfessionLevel::class);
        $professionLevel->shouldReceive('isFirstLevel')
            ->andReturn($levelValue === 1);
        $professionLevel->shouldReceive('isNextLevel')
            ->andReturn($levelValue > 1);
        $psychicalSkill = $this->mockery($skillClass);
        /** @var ProfessionLevel $professionLevel */
        $psychicalSkill->shouldReceive('getSkillRanks')
            ->andReturn($this->createSkillRanks($finalSkillRankValue, $professionLevel));

        return $psychicalSkill;
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
        $skills = new PsychicalSkills();
        $professionLevels = $this->createProfessionLevels(
            $firstLevelWill = 123, $firstLevelIntelligence = 456, $nextLevelsWill = 321, $nextLevelsIntelligence = 654
        );

        self::assertSame($nextLevelsWill + $nextLevelsIntelligence, $skills->getUnusedNextLevelsPsychicalSkillPointsValue($professionLevels));
        $skills->addPsychicalSkill($this->createPsychicalSkill($rankFromFirstLevel = 2, 1, Mythology::class));
        self::assertSame($nextLevelsWill + $nextLevelsIntelligence, $skills->getUnusedNextLevelsPsychicalSkillPointsValue($professionLevels));

        $skills->addPsychicalSkill($this->createPsychicalSkill($aRankFromNextLevel = 3, 2, Technology::class));
        $skills->addPsychicalSkill($this->createPsychicalSkill($anotherRankFromNextLevel = 1, 3, Zoology::class));
        self::assertSame(
            ($nextLevelsWill + $nextLevelsIntelligence) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))),
            $skills->getUnusedNextLevelsPsychicalSkillPointsValue($professionLevels),
            'Expected ' . (($nextLevelsWill + $nextLevelsIntelligence) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))))
        );
    }

}