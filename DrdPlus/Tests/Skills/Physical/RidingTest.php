<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\SkillPoint;
use DrdPlus\Tests\Skills\WithBonusFromSkillTest;

class RidingTest extends WithBonusFromSkillTest
{
    /**
     * @param int $skillRankValue
     * @return int
     * @throws \LogicException
     */
    protected function getExpectedBonusFromSkill(int $skillRankValue): int
    {
        switch ($skillRankValue) {
            case 1 :
                return 4;
            case 2 :
                return 6;
            case 3 :
                return 8;
            default :
                throw new \LogicException('Unexpected skill rank value ' . $skillRankValue);
        }
    }

    /**
     * @return \Mockery\MockInterface|PhysicalSkillPoint|SkillPoint
     */
    protected function createSkillPoint(): SkillPoint
    {
        $physicalSkillPoint = $this->mockery(PhysicalSkillPoint::class);
        $physicalSkillPoint->shouldReceive('getValue')
            ->andReturn(1);

        return $physicalSkillPoint;
    }
}