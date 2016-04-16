<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tests\Person\Skills\PersonSkillTest;

class PersonPhysicalSkillTest extends PersonSkillTest
{

    protected function getExpectedRelatedPropertyCodes()
    {
        return [Strength::STRENGTH, Agility::AGILITY];
    }

}