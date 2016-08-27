<?php
namespace DrdPlus\Tests\Skills\Psychical\Exceptions;

use DrdPlus\Skills\Skills;
use DrdPlus\Skills\Psychical\PsychicalSkills;
use Granam\Tests\Exceptions\Tools\AbstractExceptionsHierarchyTest;

class ExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        $combinedSkills = new \ReflectionClass(PsychicalSkills::class);

        return $combinedSkills->getNamespaceName();
    }

    protected function getRootNamespace()
    {
        $skills = new \ReflectionClass(Skills::class);

        return $skills->getNamespaceName();
    }

}
