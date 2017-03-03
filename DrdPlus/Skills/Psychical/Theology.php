<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @ORM\Entity()
 */
class Theology extends PsychicalSkill implements WithBonusToIntelligence
{
    const THEOLOGY = PsychicalSkillCode::THEOLOGY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::THEOLOGY;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }
}