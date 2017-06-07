<?php
namespace DrdPlus\Tests\Skills\Combined\RollsOn\HandworkRollOnSuccess;

use Drd\DiceRolls\Templates\Rollers\Roller2d6DrdPlus;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\RollsOn\QualityAndSuccess\ExtendedRollOnSuccess;
use DrdPlus\Skills\Combined\Handwork;
use DrdPlus\Skills\Combined\RollsOn\HandworkQuality;
use DrdPlus\Skills\Combined\RollsOn\HandworkRollOnSuccess\HandworkExtendedRollOnSuccess;
use DrdPlus\Skills\Combined\RollsOn\HandworkRollOnSuccess\HandworkSimpleRollOnGreatSuccess;
use DrdPlus\Skills\Combined\RollsOn\HandworkRollOnSuccess\HandworkSimpleRollOnLowSuccess;
use DrdPlus\Skills\Combined\RollsOn\HandworkRollOnSuccess\HandworkSimpleRollOnModerateSuccess;
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
        $handworkExtendedRollOnSuccess = HandworkExtendedRollOnSuccess::createIt($handworkQuality);
        self::assertInstanceOf(HandworkExtendedRollOnSuccess::class, $handworkExtendedRollOnSuccess);
        self::assertSame($handworkQuality, $handworkExtendedRollOnSuccess->getRollOnQuality());
        $reflection = new \ReflectionClass(ExtendedRollOnSuccess::class);
        $rollsOnSuccess = $reflection->getProperty('rollsOnSuccess');
        $rollsOnSuccess->setAccessible(true);
        self::assertEquals(
            [
                new HandworkSimpleRollOnGreatSuccess($handworkQuality),
                new HandworkSimpleRollOnModerateSuccess($handworkQuality),
                new HandworkSimpleRollOnLowSuccess($handworkQuality),
            ],
            $rollsOnSuccess->getValue($handworkExtendedRollOnSuccess)
        );

        self::assertEquals(
            $handworkExtendedRollOnSuccess,
            new HandworkExtendedRollOnSuccess(
                new HandworkSimpleRollOnLowSuccess($handworkQuality),
                new HandworkSimpleRollOnModerateSuccess($handworkQuality),
                new HandworkSimpleRollOnGreatSuccess($handworkQuality)
            )
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