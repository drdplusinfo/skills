<?php
namespace DrdPlus\Tests\Person\Skills;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\PersonSkillPoint;
use DrdPlus\Person\Skills\PersonSkillRank;
use DrdPlus\Tests\Tools\TestWithMockery;
use Granam\Integer\IntegerInterface;

abstract class AbstractTestOfSkillRank extends TestWithMockery
{

    /**
     * @param int $skillRankValue
     *
     * @test
     * @dataProvider allowedSkillRankValues
     */
    public function I_can_create_skill_rank($skillRankValue)
    {
        $sutClass = $this->getSutClass();
        /** @var PersonSkillRank $personSkillRank */
        $personSkillRank = new $sutClass(
            $professionLevel = $this->createProfessionLevel(),
            $personSkillPoint = $this->createPersonSkillPoint(),
            $this->createRequiredRankValue($skillRankValue)
        );

        $this->assertNull($personSkillRank->getId());
        $this->assertSame($skillRankValue, $personSkillRank->getValue());
        $this->assertSame("$skillRankValue", (string)$personSkillRank);
        $this->assertSame($professionLevel, $personSkillRank->getProfessionLevel());
        $this->assertSame($personSkillPoint, $personSkillRank->getPersonSkillPoint());
    }

    public function allowedSkillRankValues()
    {
        return [[0], [1], [2], [3]];
    }

    /**
     * @return string|PersonSkillRank
     */
    protected function getSutClass()
    {
        $sutClass = preg_replace('~[\\\]Tests([\\\].+)Test$~', '$1', static::class);

        return $sutClass;
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function I_can_not_create_negative_skill_rank()
    {
        $sutClass = $this->getSutClass();
        new $sutClass(
            $this->createProfessionLevel(),
            $this->createPersonSkillPoint(),
            $this->createRequiredRankValue(-1)
        );
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function I_can_not_create_skill_rank_with_value_of_four()
    {
        $sutClass = $this->getSutClass();
        new $sutClass(
            $this->createProfessionLevel(),
            $this->createPersonSkillPoint(),
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
     * @return \Mockery\MockInterface|PersonSkillPoint
     */
    abstract protected function createPersonSkillPoint();

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
