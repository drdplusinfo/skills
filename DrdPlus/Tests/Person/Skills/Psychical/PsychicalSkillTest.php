<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Tests\Person\Skills\AbstractTestOfPersonSkill;

abstract class PsychicalSkillTest extends AbstractTestOfPersonSkill
{

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