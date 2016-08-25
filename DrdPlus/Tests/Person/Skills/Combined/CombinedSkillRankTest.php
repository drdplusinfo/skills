<?php
namespace DrdPlus\Tests\Person\Skills\Combined;

use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Person\Skills\Combined\Cooking;
use DrdPlus\Tests\Person\Skills\PersonSkillRankTest;

class CombinedSkillRankTest extends PersonSkillRankTest
{

    /**
     * @return \Mockery\MockInterface|CombinedSkillPoint
     */
    protected function createPersonSkillPoint()
    {
        $combinedSkillPoint = $this->mockery(CombinedSkillPoint::class);
        $this->addProfessionLevelGetter($combinedSkillPoint);

        return $combinedSkillPoint;
    }

    protected function createOwningPersonSkill()
    {
        return new Cooking($this->createProfessionFirstLevel());
    }

}