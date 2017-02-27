<?php
namespace DrdPlus\Tests\Skills\Physical;

class FlyingTest extends WithBonusFromPhysicalSkillTest
{
    /**
     * @param int $skillRankValue
     * @return int
     */
    protected function getExpectedBonusFromSkill(int $skillRankValue): int
    {
        return $skillRankValue * 2;
    }

}