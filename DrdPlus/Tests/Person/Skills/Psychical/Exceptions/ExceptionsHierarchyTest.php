<?php
namespace DrdPlus\Tests\Person\Skills\Psychical\Exceptions;

use DrdPlus\Person\Skills\PersonSkills;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use Granam\Tests\Exceptions\Tools\AbstractExceptionsHierarchyTest;

class ExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        $combinedSkills = new \ReflectionClass(PersonPsychicalSkills::class);

        return $combinedSkills->getNamespaceName();
    }

    protected function getRootNamespace()
    {
        $skills = new \ReflectionClass(PersonSkills::class);

        return $skills->getNamespaceName();
    }

}
