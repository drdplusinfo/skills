<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Skills\Physical\Flying;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Tests\Skills\SkillRankTest;

class PhysicalSkillRankTest extends SkillRankTest
{

    /**
     * @return \Mockery\MockInterface|PhysicalSkillPoint
     */
    protected function createSkillPoint()
    {
        $physicalSkillPoint = $this->mockery(PhysicalSkillPoint::class);
        $this->addProfessionLevelGetter($physicalSkillPoint);

        return $physicalSkillPoint;
    }

    protected function createOwningSkill()
    {
        return new Flying($this->createProfessionFirstLevel());
    }

}