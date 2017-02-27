<?php
namespace DrdPlus\Skills\Psychical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @ORM\Entity()
 */
class Astronomy extends PsychicalSkill implements WithBonusToIntelligence
{
    const ASTRONOMY = PsychicalSkillCode::ASTRONOMY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::ASTRONOMY;
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
    public function getBonusToOrientation(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }
}