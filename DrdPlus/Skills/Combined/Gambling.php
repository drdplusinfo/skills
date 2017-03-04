<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToCharisma;

/**
 * @ORM\Entity()
 */
class Gambling extends CombinedSkill implements WithBonusToCharisma
{
    const GAMBLING = CombinedSkillCode::GAMBLING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::GAMBLING;
    }

    /**
     * @return int
     */
    public function getBonusToCharisma(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }

}