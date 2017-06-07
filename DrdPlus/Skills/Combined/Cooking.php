<?php
namespace DrdPlus\Skills\Combined;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\HuntingAndFishing\CatchProcessingQuality;
use DrdPlus\Properties\Base\Knack;
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

    /**
     * @link https://pph.drdplus.info/#hod_na_zpracovani_ulovku
     * @param Knack $knack
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @return CatchProcessingQuality
     */
    public function createCatchProcessingQuality(Knack $knack, Roll2d6DrdPlus $roll2D6DrdPlus): CatchProcessingQuality
    {
        return new CatchProcessingQuality($knack, $this, $roll2D6DrdPlus);
    }

}