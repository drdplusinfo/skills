<?php
namespace DrdPlus\Tests\Skills\Psychical;

use DrdPlus\Skills\Psychical\Astronomy;
use DrdPlus\Skills\Psychical\PsychicalSkillPoint;

class AstronomyTest extends WithBonusToIntelligenceFromPsychicalTest
{
    /**
     * @return \Mockery\MockInterface|PsychicalSkillPoint
     */
    private function createPsychicalSkillPoint(): PsychicalSkillPoint
    {
        $psychicalSkillPoint = $this->mockery(PsychicalSkillPoint::class);
        $psychicalSkillPoint->shouldReceive('getValue')
            ->andReturn(1);

        return $psychicalSkillPoint;
    }

    /**
     * @test
     */
    public function I_can_get_bonus_to_orientation()
    {
        $astronomy = new Astronomy($this->createProfessionLevel());

        self::assertSame(0, $astronomy->getCurrentSkillRank()->getValue());
        self::assertSame(0, $astronomy->getBonusToOrientation());

        $astronomy->increaseSkillRank($this->createPsychicalSkillPoint());
        self::assertSame(1, $astronomy->getCurrentSkillRank()->getValue());
        self::assertSame(1, $astronomy->getBonusToOrientation());

        $astronomy->increaseSkillRank($this->createPsychicalSkillPoint());
        self::assertSame(2, $astronomy->getCurrentSkillRank()->getValue());
        self::assertSame(2, $astronomy->getBonusToOrientation());

        $astronomy->increaseSkillRank($this->createPsychicalSkillPoint());
        self::assertSame(3, $astronomy->getCurrentSkillRank()->getValue());
        self::assertSame(3, $astronomy->getBonusToOrientation());
    }

}