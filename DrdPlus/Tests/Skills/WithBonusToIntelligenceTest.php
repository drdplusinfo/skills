<?php
namespace DrdPlus\Tests\Skills;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\Combined\CombinedSkill;
use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\Physical\PhysicalSkill;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\Psychical\PsychicalSkill;
use DrdPlus\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Skills\SkillPoint;
use DrdPlus\Skills\WithBonusToIntelligence;
use Granam\Tests\Tools\TestWithMockery;

abstract class WithBonusToIntelligenceTest extends TestWithMockery
{

    /**
     * @test
     */
    public function It_has_expected_interface()
    {
        self::assertTrue(
            is_a(self::getSutClass(), WithBonusToIntelligence::class, true),
            self::getSutClass() . ' should implement ' . WithBonusToIntelligence::class
        );
    }

    /**
     * @test
     */
    public function I_can_get_bonus_to_intelligence()
    {
        $sutClass = self::getSutClass();
        /** @var WithBonusToIntelligence|CombinedSkill|PhysicalSkill|PsychicalSkill $sut */
        $sut = new $sutClass($this->createProfessionLevel());

        self::assertSame(0, $sut->getCurrentSkillRank()->getValue());
        self::assertSame(0, $sut->getBonusToIntelligence());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(1, $sut->getCurrentSkillRank()->getValue());
        self::assertSame($this->getExpectedBonusFromSkill(1), $sut->getBonusToIntelligence());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(2, $sut->getCurrentSkillRank()->getValue());
        self::assertSame($this->getExpectedBonusFromSkill(2), $sut->getBonusToIntelligence());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(3, $sut->getCurrentSkillRank()->getValue());
        self::assertSame($this->getExpectedBonusFromSkill(3), $sut->getBonusToIntelligence());
    }

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    protected function createProfessionLevel()
    {
        return $this->mockery(ProfessionFirstLevel::class);
    }

    /**
     * @return \Mockery\MockInterface|PhysicalSkillPoint|PsychicalSkillPoint|CombinedSkillPoint|SkillPoint
     */
    abstract protected function createSkillPoint(): SkillPoint;

    /**
     * @param int $skillRankValue
     * @return int
     * @throws \LogicException
     */
    protected function getExpectedBonusFromSkill(int $skillRankValue): int
    {
        return 3 * $skillRankValue;
    }
}