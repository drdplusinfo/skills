<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#socharstvi
 * @ORM\Entity()
 */
class Statuary extends CombinedSkill implements WithBonus
{
    const STATUARY = CombinedSkillCode::STATUARY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::STATUARY;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 3;
    }

}