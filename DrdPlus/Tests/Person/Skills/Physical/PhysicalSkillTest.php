<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tests\Person\Skills\AbstractTestOfPersonSkill;

class PhysicalSkillTest extends AbstractTestOfPersonSkill
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