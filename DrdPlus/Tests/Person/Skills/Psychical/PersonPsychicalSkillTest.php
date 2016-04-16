<?php
namespace DrdPlus\Tests\Person\Skills\Psychical;

use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Tests\Person\Skills\PersonSkillTest;

class PersonPsychicalSkillTest extends PersonSkillTest
{
    protected function getExpectedRelatedPropertyCodes()
    {
        return [Will::WILL, Intelligence::INTELLIGENCE];
    }

}