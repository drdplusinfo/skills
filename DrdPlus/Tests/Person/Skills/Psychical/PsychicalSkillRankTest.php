<?php
namespace DrdPlus\Tests\Person\Skills\Psychical;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillRank;
use DrdPlus\Tools\Tests\TestWithMockery;
use Granam\Integer\IntegerInterface;

class PsychicalSkillRankTest extends TestWithMockery
{

    /**
     * @param int $skillRankValue
     *
     * @test
     * @dataProvider allowedSkillRankValues
     */
    public function I_can_create_skill_rank($skillRankValue)
    {
        $zeroSkillRank = new PsychicalSkillRank(
            $this->createProfessionLevel(),
            $this->createPsychicalSkillPoint(),
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
        new PsychicalSkillRank(
            $this->createProfessionLevel(),
            $this->createPsychicalSkillPoint(),
            $this->createRequiredRankValue(-1)
        );
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function I_can_not_create_skill_rank_with_value_of_four()
    {
        new PsychicalSkillRank(
            $this->createProfessionLevel(),
            $this->createPsychicalSkillPoint(),
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
     * @return \Mockery\MockInterface|PsychicalSkillPoint
     */
    private function createPsychicalSkillPoint()
    {
        $combinedSkillPoint = $this->mockery(PsychicalSkillPoint::class);

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
