<?php
namespace DrdPlus\Tests\Person\Skills\Combined\Exceptions;

use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\PersonSkills;
use Granam\Tests\Exceptions\Tools\AbstractExceptionsHierarchyTest;

class ExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        $combinedSkills = new \ReflectionClass(PersonCombinedSkills::class);

        return $combinedSkills->getNamespaceName();
    }

    protected function getRootNamespace()
    {
        $skills = new \ReflectionClass(PersonSkills::class);

        return $skills->getNamespaceName();
    }

}
