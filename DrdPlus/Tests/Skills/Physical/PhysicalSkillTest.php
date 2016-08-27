<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tests\Skills\SkillTest;

class PhysicalSkillTest extends SkillTest
{

    protected function getExpectedRelatedPropertyCodes()
    {
        return [Strength::STRENGTH, Agility::AGILITY];
    }

}