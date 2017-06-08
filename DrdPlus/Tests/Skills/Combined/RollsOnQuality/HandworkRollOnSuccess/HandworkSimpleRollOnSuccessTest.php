<?php
namespace DrdPlus\Tests\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess;

use DrdPlus\Skills\Combined\RollsOnQuality\HandworkQuality;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess\HandworkSimpleRollOnSuccess;
use Granam\Tests\Tools\TestWithMockery;

abstract class HandworkSimpleRollOnSuccessTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_both_success_and_failure()
    {
        /** @var HandworkSimpleRollOnSuccess $sutClass */
        $sutClass = self::getSutClass();
        /** @var HandworkSimpleRollOnSuccess $success */
        $success = new $sutClass($handworkQuality = $this->createHandworkQuality($this->getExpectedDifficulty()), 0);
        self::assertSame($this->getExpectedDifficulty(), $success->getDifficulty());
        self::assertSame($handworkQuality, $success->getRollOnQuality());
        self::assertTrue($success->isSuccess());
        self::assertFalse($success->isFailure());
        self::assertSame($this->getExpectedSuccessValue(), $success->getResult());

        /** @var HandworkSimpleRollOnSuccess $failure */
        $failure = new $sutClass($handworkQuality = $this->createHandworkQuality($this->getExpectedDifficulty() - 1), 0);
        self::assertSame($this->getExpectedDifficulty(), $failure->getDifficulty());
        self::assertSame($handworkQuality, $failure->getRollOnQuality());
        self::assertFalse($failure->isSuccess());
        self::assertTrue($failure->isFailure());
        self::assertSame($this->getExpectedFailureValue(), $failure->getResult());
    }

    /**
     * @return int
     */
    abstract protected function getExpectedDifficulty(): int;

    /**
     * @return string
     */
    abstract protected function getExpectedSuccessValue(): string;

    /**
     * @return string
     */
    abstract protected function getExpectedFailureValue(): string;

    /**
     * @param int $value
     * @return \Mockery\MockInterface|HandworkQuality
     */
    private function createHandworkQuality(int $value)
    {
        $handworkQuality = $this->mockery(HandworkQuality::class);
        $handworkQuality->shouldReceive('getValue')
            ->andReturn($value);

        return $handworkQuality;
    }
}