<?php
namespace DrdPlus\Tests\Person\Skills\Psychical;

use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Tests\Person\Skills\AbstractTestOfPersonSkill;

class PsychicalSkillTest extends AbstractTestOfPersonSkill
{
    protected function getExpectedRelatedPropertyCodes()
    {
        return [Will::WILL, Intelligence::INTELLIGENCE];
    }

    protected function isCombined()
    {
        return false;
    }

    protected function isPhysical()
    {
        return false;
    }

    protected function isPsychical()
    {
        return true;
    }

}