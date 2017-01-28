<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\Physical\Athletics;
use Granam\Tests\Tools\TestWithMockery;

class AthleticsTest extends TestWithMockery
{
    /**
     * @test
     */
    public function Can_be_used_as_properties_athletic_requirement()
    {
        $athletics = new Athletics($this->createProfessionLevel());
        self::assertInstanceOf(
            \DrdPlus\Properties\Derived\Athletics::class,
            $athletics,
            Athletics::class . ' should implement ' . \DrdPlus\Properties\Derived\Athletics::class . ' interface'
        );
        self::assertSame($athletics->getCurrentSkillRank(), $athletics->getAthleticsBonus());
    }

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    private function createProfessionLevel()
    {
        return $this->mockery(ProfessionFirstLevel::class);
    }
}