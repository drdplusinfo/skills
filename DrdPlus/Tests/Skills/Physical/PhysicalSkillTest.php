<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Codes\PropertyCode;
use DrdPlus\Tests\Skills\SkillTest;

class PhysicalSkillTest extends SkillTest
{

    protected function getExpectedRelatedPropertyCodes()
    {
        return [PropertyCode::STRENGTH, PropertyCode::AGILITY];
    }

}