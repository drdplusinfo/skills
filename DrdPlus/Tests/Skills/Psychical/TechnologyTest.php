<?php
namespace DrdPlus\Tests\Skills\Psychical;

use DrdPlus\Skills\Psychical\PsychicalSkill;
use DrdPlus\Skills\WithBonusToSenses;

class TechnologyTest extends WithBonusToIntelligenceFromPsychicalTest
{
    /**
     * @test
     */
    public function It_has_expected_interface()
    {
        parent::It_has_expected_interface();
        self::assertTrue(
            is_a(self::getSutClass(), WithBonusToSenses::class, true),
            self::getSutClass() . ' should implement ' . WithBonusToSenses::class
        );
    }

    /**
     * @test
     */
    public function I_can_get_bonus_to_intelligence()
    {
        $sutClass = self::getSutClass();
        /** @var WithBonusToSenses|PsychicalSkill $sut */
        $sut = new $sutClass($this->createProfessionLevel());

        self::assertSame(0, $sut->getCurrentSkillRank()->getValue());
        self::assertSame(0, $sut->getBonusToSenses());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(1, $sut->getCurrentSkillRank()->getValue());
        self::assertSame(1, $sut->getBonusToSenses());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(2, $sut->getCurrentSkillRank()->getValue());
        self::assertSame(2, $sut->getBonusToSenses());

        $sut->increaseSkillRank($this->createSkillPoint());
        self::assertSame(3, $sut->getCurrentSkillRank()->getValue());
        self::assertSame(3, $sut->getBonusToSenses());
    }
}