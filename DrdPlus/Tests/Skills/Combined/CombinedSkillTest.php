<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Tests\Skills\SkillTest;

class CombinedSkillTest extends SkillTest
{

    protected function getExpectedRelatedPropertyCodes()
    {
        return [Knack::KNACK, Charisma::CHARISMA];
    }
}