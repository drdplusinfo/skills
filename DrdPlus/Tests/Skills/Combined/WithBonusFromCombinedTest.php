<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\SkillPoint;
use DrdPlus\Tests\Skills\WithBonusTest;

abstract class WithBonusFromCombinedTest extends WithBonusTest
{
    /**
     * @param int $skillRankValue
     * @return int
     */
    protected function getExpectedBonus(int $skillRankValue): int
    {
        return 2 * $skillRankValue;
    }

    /**
     * @return \Mockery\MockInterface|CombinedSkillPoint|SkillPoint
     */
    protected function createSkillPoint(): SkillPoint
    {
        $combinedSkillPoint = $this->mockery(CombinedSkillPoint::class);
        $combinedSkillPoint->shouldReceive('getValue')
            ->andReturn(1);

        return $combinedSkillPoint;
    }
}