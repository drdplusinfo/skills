<?php
namespace DrdPlus\Tests\Skills\Psychical;

use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Tests\Skills\SkillTest;

class PsychicalSkillTest extends SkillTest
{
    protected function getExpectedRelatedPropertyCodes()
    {
        return [Will::WILL, Intelligence::INTELLIGENCE];
    }

}