<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Skills\EnumTypes;

use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\DBAL\Types\Type;
use DrdPlus\Skills\EnumTypes\SkillsEnumsRegistrar;
use Granam\Tests\Tools\TestWithMockery;

class SkillsEnumsRegistrarTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_register_all_enums_at_once()
    {
        $registered = Type::getTypesMap();
        SkillsEnumsRegistrar::registerAll();
        self::assertGreaterThan(\count($registered), Type::getTypesMap());
        self::assertTrue(Type::hasType(DateTimeImmutableType::DATETIME_IMMUTABLE));
    }
}