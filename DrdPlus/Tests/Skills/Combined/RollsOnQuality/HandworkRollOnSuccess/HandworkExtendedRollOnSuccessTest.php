<?php
namespace DrdPlus\Tests\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess;

use Drd\DiceRolls\Templates\Rollers\Roller2d6DrdPlus;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\RollsOn\QualityAndSuccess\ExtendedRollOnSuccess;
use DrdPlus\Skills\Combined\Handwork;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkQuality;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess\HandworkExtendedRollOnSuccess;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess\HandworkSimpleRollOnGreatSuccess;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess\HandworkSimpleRollOnLowSuccess;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess\HandworkSimpleRollOnModerateSuccess;
use Granam\Tests\Tools\TestWithMockery;

class HandworkExtendedRollOnSuccessTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_it_easily_by_factory_method()
    {
        $handworkQuality = new HandworkQuality(
            Knack::getIt(10),
            new Handwork($this->createProfessionLevel()),
            (new Roller2d6DrdPlus())->roll()
        );
        $handworkExtendedRollOnSuccess = HandworkExtendedRollOnSuccess::createIt($handworkQuality, 0);
        self::assertInstanceOf(HandworkExtendedRollOnSuccess::class, $handworkExtendedRollOnSuccess);
        self::assertSame($handworkQuality, $handworkExtendedRollOnSuccess->getRollOnQuality());
        $reflection = new \ReflectionClass(ExtendedRollOnSuccess::class);
        $rollsOnSuccess = $reflection->getProperty('rollsOnSuccess');
        $rollsOnSuccess->setAccessible(true);
        self::assertEquals(
            [
                new HandworkSimpleRollOnGreatSuccess($handworkQuality, 0),
                new HandworkSimpleRollOnModerateSuccess($handworkQuality, 0),
                new HandworkSimpleRollOnLowSuccess($handworkQuality, 0),
            ],
            $rollsOnSuccess->getValue($handworkExtendedRollOnSuccess)
        );

        self::assertEquals(
            $handworkExtendedRollOnSuccess,
            new HandworkExtendedRollOnSuccess($handworkQuality, 0)
        );
    }

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    private function createProfessionLevel()
    {
        return $this->mockery(ProfessionFirstLevel::class);
    }
}