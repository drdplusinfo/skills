<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class Cooking extends CombinedSkill implements WithBonus, \DrdPlus\HuntingAndFishing\Cooking
{
    const COOKING = CombinedSkillCode::COOKING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::COOKING;
    }

    /**
     * @link https://pph.drdplus.info/#vareni
     * @link https://pph.drdplus.info/#hod_na_zpracovani_ulovku
     * @return int
     */
    public function getBonus(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }

}