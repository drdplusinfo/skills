<?php
namespace DrdPlus\Tests\Skills\Psychical;

use DrdPlus\Skills\Psychical\Botany;
use DrdPlus\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Tests\Skills\SkillRankTest;

class PsychicalSkillRankTest extends SkillRankTest
{

    /**
     * @return \Mockery\MockInterface|PsychicalSkillPoint
     */
    protected function createSkillPoint()
    {
        $psychicalSkillPoint = $this->mockery(PsychicalSkillPoint::class);
        $this->addProfessionLevelGetter($psychicalSkillPoint);

        return $psychicalSkillPoint;
    }

    protected function createOwningSkill()
    {
        return new Botany($this->createProfessionFirstLevel());
    }

}