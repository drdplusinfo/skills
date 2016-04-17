<?php
namespace DrdPlus\Person\Skills\EnumTypes;

use Doctrineum\DateTimeImmutable\DateTimeImmutableType;
use DrdPlus\Person\Background\EnumTypes\PersonBackgroundEnumRegistrar;
use DrdPlus\Person\ProfessionLevels\EnumTypes\ProfessionLevelsEnumRegistrar;

class PersonSkillsEnumsRegistrar
{
    public static function registerAll()
    {
        PersonBackgroundEnumRegistrar::registerAll();
        ProfessionLevelsEnumRegistrar::registerAll();
        DateTimeImmutableType::registerSelf();
    }
}