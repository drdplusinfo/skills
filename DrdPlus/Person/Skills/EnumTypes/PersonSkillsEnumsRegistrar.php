<?php
namespace DrdPlus\Person\Skills\EnumTypes;

use Doctrineum\DateTimeImmutable\DateTimeImmutableType;
use DrdPlus\Person\Background\EnumTypes\PersonBackgroundEnumsRegistrar;
use DrdPlus\Person\ProfessionLevels\EnumTypes\ProfessionLevelsEnumsRegistrar;

class PersonSkillsEnumsRegistrar
{
    public static function registerAll()
    {
        PersonBackgroundEnumsRegistrar::registerAll();
        ProfessionLevelsEnumsRegistrar::registerAll();
        DateTimeImmutableType::registerSelf();
    }
}