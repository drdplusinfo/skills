<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Person\Skills\Physical\PhysicalSkillRank;
use DrdPlus\Tools\Tests\TestWithMockery;
use Granam\Integer\IntegerInterface;

class PhysicalSkillRankTest extends TestWithMockery
{

    /**
     * @param int $skillRankValue
     *
     * @test
     * @dataProvider allowedSkillRankValues
     */
    public function I_can_create_skill_rank($skillRankValue)
    {
        $zeroSkillRank = new PhysicalSkillRank(
            $this->createProfessionLevel(),
            $this->createPhysicalSkillPoint(),
            $this->createRequiredRankValue($skillRankValue)
        );

        $this->assertSame($skillRankValue, $zeroSkillRank->getValue());
    }

    public function allowedSkillRankValues()
    {
        return [[0], [1], [2], [3]];
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function I_can_not_create_negative_skill_rank()
    {
        new PhysicalSkillRank(
            $this->createProfessionLevel(),
            $this->createPhysicalSkillPoint(),
            $this->createRequiredRankValue(-1)
        );
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function I_can_not_create_skill_rank_with_value_of_four()
    {
        new PhysicalSkillRank(
            $this->createProfessionLevel(),
            $this->createPhysicalSkillPoint(),
            $this->createRequiredRankValue(4)
        );
    }

    /**
     * @return \Mockery\MockInterface|ProfessionLevel
     */
    private function createProfessionLevel()
    {
        $professionLevels = $this->mockery(ProfessionLevel::class);

        return $professionLevels;
    }

    /**
     * @return \Mockery\MockInterface|PhysicalSkillPoint
     */
    private function createPhysicalSkillPoint()
    {
        $combinedSkillPoint = $this->mockery(PhysicalSkillPoint::class);

        return $combinedSkillPoint;
    }

    /**
     * @param int $value
     * @return \Mockery\MockInterface|IntegerInterface
     */
    private function createRequiredRankValue($value)
    {
        $requiredRankValue = $this->mockery(IntegerInterface::class);
        $requiredRankValue->shouldReceive('getValue')
            ->atLeast()->once()
            ->andReturn($value);

        return $requiredRankValue;
    }
}
