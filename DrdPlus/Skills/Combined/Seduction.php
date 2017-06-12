<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToCharisma;

/**
 * @link https://pph.drdplus.info/#svadeni
 * @ORM\Entity()
 */
class Seduction extends CombinedSkill implements WithBonusToCharisma
{
    const SEDUCTION = CombinedSkillCode::SEDUCTION;

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