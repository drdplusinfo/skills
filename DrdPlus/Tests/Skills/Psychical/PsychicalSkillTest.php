<?php
namespace DrdPlus\Tests\Skills\Psychical;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tests\Skills\SkillTest;

class PsychicalSkillTest extends SkillTest
{
    protected function getExpectedRelatedPropertyCodes()
    {
        return [PropertyCode::WILL, PropertyCode::INTELLIGENCE];
    }

}