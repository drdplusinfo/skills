<?php
namespace DrdPlus\Tests\Skills\Combined\Exceptions;

use DrdPlus\Skills\Combined\CombinedSkills;
use DrdPlus\Skills\Skills;
use Granam\Tests\Exceptions\Tools\AbstractExceptionsHierarchyTest;

class ExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        $combinedSkills = new \ReflectionClass(CombinedSkills::class);

        return $combinedSkills->getNamespaceName();
    }

    protected function getRootNamespace()
    {
        $skills = new \ReflectionClass(Skills::class);

        return $skills->getNamespaceName();
    }

}
