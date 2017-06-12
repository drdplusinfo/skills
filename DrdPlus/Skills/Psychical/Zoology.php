<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @link https://pph.drdplus.info/#zoologie
 * @ORM\Entity()
 */
class Zoology extends PsychicalSkill implements WithBonusToIntelligence
{
    const ZOOLOGY = PsychicalSkillCode::ZOOLOGY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::ZOOLOGY;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @return int
     */
    public function getBonusToAttackNumberAgainstFreeWillAnimal(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @return int
     */
    public function getBonusToCoverAgainstFreeWillAnimal(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @return int
     */
    public function getBonusToBaseOfWoundsAgainstFreeWillAnimal(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }
}