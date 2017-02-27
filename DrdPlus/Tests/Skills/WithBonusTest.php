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
use DrdPlus\Skills\WithBonus;
use Granam\Tests\Tools\TestWithMockery;

abstract class WithBonusTest extends TestWithMockery
{
    /**
     * @test
     */
    public function It_has_expected_interface()
    {
        self::assertTrue(
            is_a(self::getSutClass(), WithBonus::class, true),
            self::getSutClass() . ' should implement ' . WithBonus::class
        );
    }

    /**
     * @test
     */
    public function I_can_get_its_bonus()
    {
        /** @var CombinedSkill|PhysicalSkill|PsychicalSkill $sutClass */
        $sutClass = self::getSutClass();
        /** @var CombinedSkill|PhysicalSkill|PsychicalSkill|WithBonus $sut */
        $sut = new $sutClass($this->createProfessionLevel());

        self::assertSame(0, $sut->getCurrentSkillRank()->getValue());
        self::assertSame(0, $sut->getBonus());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(1, $sut->getCurrentSkillRank()->getValue());
        self::assertSame($this->getExpectedBonus(1), $sut->getBonus());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(2, $sut->getCurrentSkillRank()->getValue());
        self::assertSame($this->getExpectedBonus(2), $sut->getBonus());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(3, $sut->getCurrentSkillRank()->getValue());
        self::assertSame($this->getExpectedBonus(3), $sut->getBonus());
    }

    /**
     * @param int $skillRankValue
     * @return int
     */
    abstract protected function getExpectedBonus(int $skillRankValue): int;

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
}