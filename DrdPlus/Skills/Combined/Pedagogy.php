<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Skills\WithBonusToCharisma;

/**
 * @link https://pph.drdplus.info/#vychovatelstvi
 * @Doctrine\ORM\Mapping\Entity()
 */
class Pedagogy extends CombinedSkill implements WithBonusToCharisma
{
    public const PEDAGOGY = CombinedSkillCode::PEDAGOGY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::PEDAGOGY;
    }

    /**
     * @return int
     */
    public function getBonusToCharisma(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }

}