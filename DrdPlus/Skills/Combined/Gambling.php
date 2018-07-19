<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Skills\WithBonusToCharisma;

/**
 * @link https://pph.drdplus.info/#hazardni_hry
 * @Doctrine\ORM\Mapping\Entity()
 */
class Gambling extends CombinedSkill implements WithBonusToCharisma
{
    public const GAMBLING = CombinedSkillCode::GAMBLING;

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