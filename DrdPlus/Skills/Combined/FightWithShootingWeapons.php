<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Skills\FightWithWeaponsMissingSkillMalusesTrait;

abstract class FightWithShootingWeapons extends CombinedSkill implements CausingMalusesToWeaponUsage
{
    use FightWithWeaponsMissingSkillMalusesTrait;
}