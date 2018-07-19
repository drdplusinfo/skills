<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;

use DrdPlus\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Skills\FightWithWeaponSkill;
use DrdPlus\Skills\FightWithWeaponsMissingSkillMalusesTrait;

/**
 * For maluses see PPH page 93 left column
 *
 * @link https://pph.drdplus.info/#tabulka_postihu_za_chybejici_dovednost
 */
abstract class FightWithWeaponsUsingPhysicalSkill extends PhysicalSkill implements CausingMalusesToWeaponUsage, FightWithWeaponSkill
{
    use FightWithWeaponsMissingSkillMalusesTrait;
}