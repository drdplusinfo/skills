<?php
namespace DrdPlus\Tests\Person\Skills\Exceptions;

use DrdPlus\Person\Skills\PersonSkills;
use Granam\Tests\Exceptions\Tools\AbstractExceptionsHierarchyTest;

class ExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace();
    }

    protected function getRootNamespace()
    {
        $reflection = new \ReflectionClass(PersonSkills::class);

        return $reflection->getNamespaceName();
    }

}
