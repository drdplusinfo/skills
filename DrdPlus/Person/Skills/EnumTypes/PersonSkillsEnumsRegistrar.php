<?php
namespace DrdPlus\Person\Skills\EnumTypes;

use DrdPlus\Person\Background\EnumTypes\PersonBackgroundEnumsRegistrar;

class PersonSkillsEnumsRegistrar
{
    public static function registerAll()
    {
        PersonBackgroundEnumsRegistrar::registerAll();
    }
}