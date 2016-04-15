<?php
namespace DrdPlus\Tests\Person\Skills\Psychical;

use DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Tests\Person\Skills\PersonSkillRankTest;

class PsychicalSkillRankTest extends PersonSkillRankTest
{

    /**
     * @return \Mockery\MockInterface|PsychicalSkillPoint
     */
    protected function createPersonSkillPoint()
    {
        $psychicalSkillPoint = $this->mockery(PsychicalSkillPoint::class);
        $this->addProfessionLevelGetter($psychicalSkillPoint);

        return $psychicalSkillPoint;
    }

}
