<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\EnumTypes;

use DrdPlus\Background\EnumTypes\BackgroundEnumRegistrar;
use DrdPlus\Person\ProfessionLevels\EnumTypes\ProfessionLevelsEnumRegistrar;

class SkillsEnumsRegistrar
{
    public static function registerAll()
    {
        BackgroundEnumRegistrar::registerAll();
        ProfessionLevelsEnumRegistrar::registerAll();
    }
}