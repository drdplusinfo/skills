<?php
namespace DrdPlus\Tests\Person\Skills\EnumTypes;

use Doctrine\DBAL\Types\Type;
use DrdPlus\Person\Skills\EnumTypes\PersonSkillsEnumsRegistrar;
use Granam\Tests\Tools\TestWithMockery;

class PersonSkillsEnumsRegistrarTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_register_all_enums_at_once()
    {
        $registered = Type::getTypesMap();
        PersonSkillsEnumsRegistrar::registerAll();
        self::assertGreaterThan(count($registered), Type::getTypesMap());
    }
}