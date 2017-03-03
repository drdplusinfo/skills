<?php
namespace DrdPlus\Tests\Skills\Psychical;

use DrdPlus\Skills\Psychical\Zoology;

class ZoologyTest extends WithBonusToIntelligenceFromPsychicalTest
{
    /**
     * @test
     */
    public function I_can_get_bonuses_when_fighting_natural_animal()
    {
        $zoology = new Zoology($this->createProfessionLevel());
        self::assertSame(0, $zoology->getBonusToAttackNumberAgainstNaturalAnimal());
        self::assertSame(0, $zoology->getBonusToBaseOfWoundsAgainstNaturalAnimal());
        self::assertSame(0, $zoology->getBonusToCoverAgainstNaturalAnimal());

        for ($rank = 1; $rank <= 3; $rank++) {
            $zoology->increaseSkillRank($this->createSkillPoint());
            self::assertSame($rank, $zoology->getCurrentSkillRank()->getValue());
            self::assertSame($rank, $zoology->getBonusToAttackNumberAgainstNaturalAnimal());
            self::assertSame($rank, $zoology->getBonusToBaseOfWoundsAgainstNaturalAnimal());
            self::assertSame($rank, $zoology->getBonusToCoverAgainstNaturalAnimal());
        }
    }
}