<?php
namespace DrdPlus\Tests\Person\Skills\Combined;

use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Tests\Person\Skills\AbstractTestOfSkillRank;

class CombinedSkillRankTest extends AbstractTestOfSkillRank
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

}
