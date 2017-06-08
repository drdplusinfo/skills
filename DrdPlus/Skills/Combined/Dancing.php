<?php
namespace DrdPlus\Skills\Combined;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Skills\Combined\RollsOnQuality\DanceQuality;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class Dancing extends CombinedSkill implements WithBonus
{
    const DANCING = CombinedSkillCode::DANCING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::DANCING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @param Agility $agility
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @return DanceQuality
     */
    public function createDanceQuality(Agility $agility, Roll2d6DrdPlus $roll2D6DrdPlus): DanceQuality
    {
        return new DanceQuality($agility, $this, $roll2D6DrdPlus);
    }

}