<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\Combined\Cooking;
use DrdPlus\Tests\Skills\SkillRankTest;

class CombinedSkillRankTest extends SkillRankTest
{

    /**
     * @return \Mockery\MockInterface|CombinedSkillPoint
     */
    protected function createSkillPoint()
    {
        $combinedSkillPoint = $this->mockery(CombinedSkillPoint::class);
        $this->addProfessionLevelGetter($combinedSkillPoint);

        return $combinedSkillPoint;
    }

    protected function createOwningSkill()
    {
        return new Cooking($this->createProfessionFirstLevel());
    }

}