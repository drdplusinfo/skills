<?php
namespace DrdPlus\Skills\Combined;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Skills\Combined\RollsOnQuality\ShowmanshipGameQuality;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#herectvi
 * @ORM\Entity()
 */
class Showmanship extends CombinedSkill implements WithBonus
{
    const SHOWMANSHIP = CombinedSkillCode::SHOWMANSHIP;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::SHOWMANSHIP;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @link https://pph.drdplus.info/#vypocet_kvality_hry_pri_herectvi
     * @param Charisma $charisma
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @return ShowmanshipGameQuality
     */
    public function getShowmanshipGameQuality(Charisma $charisma, Roll2d6DrdPlus $roll2D6DrdPlus): ShowmanshipGameQuality
    {
        return new ShowmanshipGameQuality($charisma, $this, $roll2D6DrdPlus);
    }
}