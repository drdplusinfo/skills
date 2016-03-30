<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tests\Person\Skills\PersonSkillTest;

class PhysicalSkillTest extends PersonSkillTest
{

    protected function getExpectedRelatedPropertyCodes()
    {
        return [Strength::STRENGTH, Agility::AGILITY];
    }

    protected function isCombined()
    {
        return false;
    }

    protected function isPhysical()
    {
        return true;
    }

    protected function isPsychical()
    {
        return false;
    }

}