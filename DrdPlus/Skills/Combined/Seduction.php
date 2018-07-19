<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Skills\WithBonusToCharisma;

/**
 * @link https://pph.drdplus.info/#svadeni
 * @Doctrine\ORM\Mapping\Entity()
 */
class Seduction extends CombinedSkill implements WithBonusToCharisma
{
    public const SEDUCTION = CombinedSkillCode::SEDUCTION;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::SEDUCTION;
    }

    /**
     * @return int
     */
    public function getBonusToCharisma(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}