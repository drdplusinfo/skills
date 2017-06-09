<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#noseni_zbroje
 */
/**
 * @ORM\Entity()
 */
class ArmorWearing extends PhysicalSkill implements WithBonus
{
    const ARMOR_WEARING = PhysicalSkillCode::ARMOR_WEARING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::ARMOR_WEARING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}