<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\Physical\CityMoving;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use Granam\Tests\Tools\TestWithMockery;

class CityMovingTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_bonus_to_speed_and_intelligence_or_senses()
    {
        $swimming = new CityMoving($this->createProfessionLevel());

        self::assertSame(0, $swimming->getCurrentSkillRank()->getValue());
        self::assertSame(0, $swimming->getBonusToSpeed());
        self::assertSame(0, $swimming->getBonusToIntelligenceOrSenses());

        $swimming->increaseSkillRank($this->createPhysicalSkillPoint());
        self::assertSame(1, $swimming->getCurrentSkillRank()->getValue());
        self::assertSame(1, $swimming->getBonusToSpeed());
        self::assertSame(2, $swimming->getBonusToIntelligenceOrSenses());

        $swimming->increaseSkillRank($this->createPhysicalSkillPoint());
        self::assertSame(2, $swimming->getCurrentSkillRank()->getValue());
        self::assertSame(2, $swimming->getBonusToSpeed());
        self::assertSame(4, $swimming->getBonusToIntelligenceOrSenses());

        $swimming->increaseSkillRank($this->createPhysicalSkillPoint());
        self::assertSame(3, $swimming->getCurrentSkillRank()->getValue());
        self::assertSame(3, $swimming->getBonusToSpeed());
        self::assertSame(6, $swimming->getBonusToIntelligenceOrSenses());
    }

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    private function createProfessionLevel()
    {
        return $this->mockery(ProfessionFirstLevel::class);
    }

    /**
     * @return \Mockery\MockInterface|PhysicalSkillPoint
     */
    private function createPhysicalSkillPoint(): PhysicalSkillPoint
    {
        $physicalSkillPoint = $this->mockery(PhysicalSkillPoint::class);
        $physicalSkillPoint->shouldReceive('getValue')
            ->andReturn(1);

        return $physicalSkillPoint;
    }
}