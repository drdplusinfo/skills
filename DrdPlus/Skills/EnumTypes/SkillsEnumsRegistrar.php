<?php
namespace DrdPlus\Skills\EnumTypes;

use Doctrineum\DateTimeImmutable\DateTimeImmutableType;
use DrdPlus\Person\Background\EnumTypes\PersonBackgroundEnumRegistrar;
use DrdPlus\Person\ProfessionLevels\EnumTypes\ProfessionLevelsEnumRegistrar;

class SkillsEnumsRegistrar
{
    public static function registerAll()
    {
        PersonBackgroundEnumRegistrar::registerAll();
        ProfessionLevelsEnumRegistrar::registerAll();
        DateTimeImmutableType::registerSelf();
    }
}