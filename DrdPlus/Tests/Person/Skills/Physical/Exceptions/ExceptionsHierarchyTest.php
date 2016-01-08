<?php
namespace DrdPlus\Tests\Person\Skills\Physical\Exceptions;

use DrdPlus\Person\Skills\PersonSkills;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use Granam\Exceptions\Tests\Tools\AbstractTestOfExceptionsHierarchy;

class ExceptionsHierarchyTest extends AbstractTestOfExceptionsHierarchy
{
    protected function getTestedNamespace()
    {
        $combinedSkills = new \ReflectionClass(PersonPhysicalSkills::class);

        return $combinedSkills->getNamespaceName();
    }

    protected function getRootNamespace()
    {
        $skills = new \ReflectionClass(PersonSkills::class);

        return $skills->getNamespaceName();
    }

}
