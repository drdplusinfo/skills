<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\Physical\Swimming;
use Granam\Tests\Tools\TestWithMockery;

class SwimmingTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_bonus_to_swimming_and_speed()
    {
        $swimming = new Swimming($this->createProfessionLevel());

        self::assertSame(0, $swimming->getCurrentSkillRank()->getValue());
        self::assertSame(0, $swimming->getBonusToSwimming());
        self::assertSame(0, $swimming->getBonusToSpeed());

        $swimming->increaseSkillRank($this->createPhysicalSkillPoint());
        self::assertSame(1, $swimming->getCurrentSkillRank()->getValue());
        self::assertSame(4, $swimming->getBonusToSwimming());
        self::assertSame(2, $swimming->getBonusToSpeed());

        $swimming->increaseSkillRank($this->createPhysicalSkillPoint());
        self::assertSame(2, $swimming->getCurrentSkillRank()->getValue());
        self::assertSame(6, $swimming->getBonusToSwimming());
        self::assertSame(3, $swimming->getBonusToSpeed());

        $swimming->increaseSkillRank($this->createPhysicalSkillPoint());
        self::assertSame(3, $swimming->getCurrentSkillRank()->getValue());
        self::assertSame(8, $swimming->getBonusToSwimming());
        self::assertSame(4, $swimming->getBonusToSpeed());
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