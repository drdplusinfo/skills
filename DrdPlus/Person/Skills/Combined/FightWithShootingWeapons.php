<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

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
