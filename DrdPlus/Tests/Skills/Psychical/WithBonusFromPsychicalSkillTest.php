<?php
namespace DrdPlus\Tests\Skills\Psychical;

use DrdPlus\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Skills\SkillPoint;
use DrdPlus\Tests\Skills\WithBonusFromSkillTest;

abstract class WithBonusFromPsychicalSkillTest extends WithBonusFromSkillTest
{
    /**
     * @return \Mockery\MockInterface|PsychicalSkillPoint|SkillPoint
     */
    protected function createSkillPoint(): SkillPoint
    {
        $psychicalSkillPoint = $this->mockery(PsychicalSkillPoint::class);
        $psychicalSkillPoint->shouldReceive('getValue')
            ->andReturn(1);

        return $psychicalSkillPoint;
    }
}