<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Tests\Person\Skills\AbstractTestOfSkillRank;

class PhysicalSkillRankTest extends AbstractTestOfSkillRank
{

    /**
     * @return \Mockery\MockInterface|PhysicalSkillPoint
     */
    protected function createPersonSkillPoint()
    {
        $physicalSkillPoint = $this->mockery(PhysicalSkillPoint::class);

        return $physicalSkillPoint;
    }

}
