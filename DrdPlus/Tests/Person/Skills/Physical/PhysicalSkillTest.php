<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Tests\Person\Skills\AbstractTestOfPersonSkill;

abstract class PhysicalSkillTest extends AbstractTestOfPersonSkill
{

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