<?php
namespace DrdPlus\Tests\Skills\Combined\RollsOn\HandworkRollOnSuccess;

class HandworkSimpleRollOnLowSuccessTest extends HandworkSimpleRollOnSuccessTest
{
    /**
     * @return int
     */
    protected function getExpectedDifficulty(): int
    {
        return 14;
    }

    /**
     * @return string
     */
    protected function getExpectedSuccessValue(): string
    {
        return 'usable';
    }

    /**
     * @return string
     */
    protected function getExpectedFailureValue(): string
    {
        return 'useless';
    }
}