<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @ORM\Entity()
 */
class Historiography extends PsychicalSkill implements WithBonusToIntelligence
{
    const HISTORIOGRAPHY = PsychicalSkillCode::HISTORIOGRAPHY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HISTORIOGRAPHY;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }
}