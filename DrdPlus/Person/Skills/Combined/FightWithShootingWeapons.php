<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithShootingWeapons extends PersonCombinedSkill
{
    const FIGHT_WITH_SHOOTING_WEAPONS = SkillCodes::FIGHT_WITH_SHOOTING_WEAPONS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_SHOOTING_WEAPONS;
    }

}
