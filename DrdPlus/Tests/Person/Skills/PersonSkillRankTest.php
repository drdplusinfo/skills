<?php
namespace DrdPlus\Tests\Person\Skills;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Person\Skills\PersonSkillPoint;
use DrdPlus\Person\Skills\PersonSkillRank;
use Granam\Integer\PositiveInteger;
use Mockery\MockInterface;
use Granam\Tests\Tools\TestWithMockery;

abstract class PersonSkillRankTest extends TestWithMockery
{

    /**
     * @test
     * @dataProvider allowedSkillRankValues
     * @param int $skillRankValue
     */
    public function I_can_create_skill_rank($skillRankValue)
    {
        $sutClass = $this->getSutClass();
        /** @var PersonSkillRank $personSkillRank */
        $personSkillRank = new $sutClass(
            $this->createOwningPersonSkill(),
            $personSkillPoint = $this->createPersonSkillPoint(),
            $this->createRequiredRankValue($skillRankValue)
        );

        self::assertNull($personSkillRank->getId());
        self::assertSame($skillRankValue, $personSkillRank->getValue());
        self::assertSame("$skillRankValue", (string)$personSkillRank);
        self::assertSame($personSkillPoint->getProfessionLevel(), $personSkillRank->getProfessionLevel());
        self::assertSame($personSkillPoint, $personSkillRank->getPersonSkillPoint());
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
        return preg_replace('~[\\\]Tests([\\\].+)Test$~', '$1', static::class);
    }

    /**
     * @return PersonSkill
     */
    abstract protected function createOwningPersonSkill();

    protected function addProfessionLevelGetter(MockInterface $mock)
    {
        $mock->shouldReceive('getProfessionLevel')
            ->andReturn($this->mockery(ProfessionLevel::class));
    }

    /**
     * @return MockInterface|ProfessionFirstLevel
     */
    protected function createProfessionFirstLevel()
    {
        return $this->mockery(ProfessionFirstLevel::class);
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function I_can_not_create_negative_skill_rank()
    {
        /** @var  $sutClass */
        $sutClass = $this->getSutClass();
        new $sutClass(
            $this->createOwningPersonSkill(),
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
            $this->createOwningPersonSkill(),
            $this->createPersonSkillPoint(),
            $this->createRequiredRankValue(4)
        );
    }

    /**
     * @return \Mockery\MockInterface|PersonSkillPoint
     */
    abstract protected function createPersonSkillPoint();

    /**
     * @param int $value
     * @return \Mockery\MockInterface|PositiveInteger
     */
    private function createRequiredRankValue($value)
    {
        $requiredRankValue = $this->mockery(PositiveInteger::class);
        $requiredRankValue->shouldReceive('getValue')
            ->atLeast()->once()
            ->andReturn($value);

        return $requiredRankValue;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     */
    public function Person_skill_has_to_be_set_in_descendant_constructor_first()
    {
        /** @var PositiveInteger $requiredRankValue */
        $requiredRankValue = $this->mockery(PositiveInteger::class);

        new BrokenBecauseOfSkillNotSetInConstructor(
            $this->createOwningPersonSkill(),
            $this->createPersonSkillPoint(),
            $requiredRankValue
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\CanNotVerifyPaidPersonSkillPoint
     */
    public function Person_skill_point_has_to_be_set_in_descendant_constructor_first()
    {
        /** @var PositiveInteger $requiredRankValue */
        $requiredRankValue = $this->mockery(PositiveInteger::class);

        new BrokenBecauseOfSkillPointNotSetInConstructor(
            $this->createOwningPersonSkill(),
            $this->createPersonSkillPoint(),
            $requiredRankValue
        );
    }
}

class BrokenBecauseOfSkillNotSetInConstructor extends PersonSkillRank
{
    public function __construct(
        PersonSkill $owningPersonSkill,
        PersonSkillPoint $personSkillPoint,
        PositiveInteger $requiredRankValue
    )
    {
        parent::__construct($owningPersonSkill, $personSkillPoint, $requiredRankValue);
    }

    public function getPersonSkillPoint()
    {
    }

    public function getPersonSkill()
    {
    }

}

class BrokenBecauseOfSkillPointNotSetInConstructor extends PersonSkillRank
{
    private $personSkill;

    public function __construct(
        PersonSkill $owningPersonSkill,
        PersonSkillPoint $personSkillPoint,
        PositiveInteger $requiredRankValue
    )
    {
        $this->personSkill = $owningPersonSkill;
        parent::__construct($owningPersonSkill, $personSkillPoint, $requiredRankValue);
    }

    public function getPersonSkillPoint()
    {
    }

    public function getPersonSkill()
    {
        return $this->personSkill;
    }

}