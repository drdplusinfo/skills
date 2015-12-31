<?php
namespace DrdPlus\Tests\Person\Skills\Psychical;

use DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Tests\Person\Skills\AbstractTestOfSkillRank;

class PsychicalSkillRankTest extends AbstractTestOfSkillRank
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
