<?php
namespace DrdPlus\Tests\Person\Skills\Exceptions;

use DrdPlus\Person\Skills\PersonSkills;
use Granam\Exceptions\Tests\Tools\AbstractTestOfExceptionsHierarchy;

class ExceptionsHierarchyTest extends AbstractTestOfExceptionsHierarchy
{
    protected function getTestedNamespace()
    {
        $reflection = new \ReflectionClass(PersonSkills::class);

        return $reflection->getNamespaceName();
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

}
