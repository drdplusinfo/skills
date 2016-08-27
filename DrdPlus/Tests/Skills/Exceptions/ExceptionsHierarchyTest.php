<?php
namespace DrdPlus\Tests\Skills\Exceptions;

use DrdPlus\Skills\Skills;
use Granam\Tests\Exceptions\Tools\AbstractExceptionsHierarchyTest;

class ExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace();
    }

    protected function getRootNamespace()
    {
        $reflection = new \ReflectionClass(Skills::class);

        return $reflection->getNamespaceName();
    }

}
