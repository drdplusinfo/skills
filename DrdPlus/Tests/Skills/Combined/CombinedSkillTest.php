<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Codes\PropertyCode;
use DrdPlus\Tests\Skills\SkillTest;

class CombinedSkillTest extends SkillTest
{

    protected function getExpectedRelatedPropertyCodes()
    {
        return [PropertyCode::KNACK, PropertyCode::CHARISMA];
    }
}