<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\Combined\HuntingAndFishing;
use Granam\Tests\Tools\TestWithMockery;

class HuntingAndFishingTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_it_as_hunting_and_fishing_bonus()
    {
        $huntingAndFishing = new HuntingAndFishing($this->createProfessionLevel());
        $huntingAndFishing->increaseSkillRank($this->createCombinedSkillPoint());
        self::assertSame(1, $huntingAndFishing->getCurrentSkillRank()->getValue());
        self::assertSame(2, $huntingAndFishing->getBonusFromSkill());
        $huntingAndFishing->increaseSkillRank($this->createCombinedSkillPoint());
        self::assertSame(2, $huntingAndFishing->getCurrentSkillRank()->getValue());
        self::assertSame(4, $huntingAndFishing->getBonusFromSkill());
    }

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    private function createProfessionLevel()
    {
        return $this->mockery(ProfessionFirstLevel::class);
    }

    /**
     * @return \Mockery\MockInterface|CombinedSkillPoint
     */
    private function createCombinedSkillPoint()
    {
        $combinedSkillPoint = $this->mockery(CombinedSkillPoint::class);
        $combinedSkillPoint->shouldReceive('getValue')
            ->andReturn(1);

        return $combinedSkillPoint;
    }
}