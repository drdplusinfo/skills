<?php
namespace DrdPlus\Tests\Skills\Psychical\Exceptions;

use DrdPlus\Skills\Skills;
use DrdPlus\Skills\Psychical\PsychicalSkills;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class ExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        $psychicalSkills = new \ReflectionClass(PsychicalSkills::class);

        return $psychicalSkills->getNamespaceName();
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        $skills = new \ReflectionClass(Skills::class);

        return $skills->getNamespaceName();
    }

}