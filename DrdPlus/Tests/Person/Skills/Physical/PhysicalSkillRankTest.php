<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Person\Skills\Physical\Flying;
use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Tests\Person\Skills\PersonSkillRankTest;

class PhysicalSkillRankTest extends PersonSkillRankTest
{

    /**
     * @return \Mockery\MockInterface|PhysicalSkillPoint
     */
    protected function createPersonSkillPoint()
    {
        $physicalSkillPoint = $this->mockery(PhysicalSkillPoint::class);
        $this->addProfessionLevelGetter($physicalSkillPoint);

        return $physicalSkillPoint;
    }

    protected function createOwningPersonSkill()
    {
        return new Flying();
    }

}
