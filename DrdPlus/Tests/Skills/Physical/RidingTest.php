<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\SkillPoint;
use DrdPlus\Tests\Skills\WithBonusTest;

class RidingTest extends WithBonusTest
{
    use CreatePhysicalSkillPointTrait;

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
}