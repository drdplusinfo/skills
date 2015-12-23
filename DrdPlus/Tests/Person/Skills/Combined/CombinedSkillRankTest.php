<?php
namespace DrdPlus\Tests\Person\Skills\Combined;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Person\Skills\Combined\CombinedSkillRank;
use DrdPlus\Tools\Tests\TestWithMockery;
use Granam\Integer\IntegerInterface;

class CombinedSkillRankTest extends TestWithMockery
{

    /**
     * @param int $skillRankValue
     *
     * @test
     * @dataProvider allowedSkillRankValues
     */
    public function I_can_create_skill_rank($skillRankValue)
    {
        $zeroSkillRank = new CombinedSkillRank(
            $this->createProfessionLevel(),
            $this->createCombinedSkillPoint(),
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
        new CombinedSkillRank(
            $this->createProfessionLevel(),
            $this->createCombinedSkillPoint(),
            $this->createRequiredRankValue(-1)
        );
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function I_can_not_create_skill_rank_with_value_of_four()
    {
        new CombinedSkillRank(
            $this->createProfessionLevel(),
            $this->createCombinedSkillPoint(),
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
     * @return \Mockery\MockInterface|CombinedSkillPoint
     */
    private function createCombinedSkillPoint()
    {
        $combinedSkillPoint = $this->mockery(CombinedSkillPoint::class);

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
