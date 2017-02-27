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
use DrdPlus\Skills\WithBonusFromSkill;
use Granam\Tests\Tools\TestWithMockery;

abstract class WithBonusFromSkillTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_its_bonus()
    {
        /** @var CombinedSkill|PhysicalSkill|PsychicalSkill $sutClass */
        $sutClass = self::getSutClass();
        /** @var CombinedSkill|PhysicalSkill|PsychicalSkill|WithBonusFromSkill $sut */
        $sut = new $sutClass($this->createProfessionLevel());
        self::assertInstanceOf(WithBonusFromSkill::class, $sut);

        self::assertSame(0, $sut->getCurrentSkillRank()->getValue());
        self::assertSame(0, $sut->getBonusFromSkill());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(1, $sut->getCurrentSkillRank()->getValue());
        self::assertSame($this->getExpectedBonusFromSkill(1), $sut->getBonusFromSkill());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(2, $sut->getCurrentSkillRank()->getValue());
        self::assertSame($this->getExpectedBonusFromSkill(2), $sut->getBonusFromSkill());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(3, $sut->getCurrentSkillRank()->getValue());
        self::assertSame($this->getExpectedBonusFromSkill(3), $sut->getBonusFromSkill());
    }

    /**
     * @param int $skillRankValue
     * @return int
     */
    abstract protected function getExpectedBonusFromSkill(int $skillRankValue): int;

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    private function createProfessionLevel()
    {
        return $this->mockery(ProfessionFirstLevel::class);
    }

    /**
     * @return \Mockery\MockInterface|PhysicalSkillPoint|PsychicalSkillPoint|CombinedSkillPoint|SkillPoint
     */
    abstract protected function createSkillPoint(): SkillPoint;
}