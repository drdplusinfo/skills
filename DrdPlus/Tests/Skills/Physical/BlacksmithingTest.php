<?php
namespace DrdPlus\Tests\Skills\Physical;

class BlacksmithingTest extends WithBonusFromPhysicalTest
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