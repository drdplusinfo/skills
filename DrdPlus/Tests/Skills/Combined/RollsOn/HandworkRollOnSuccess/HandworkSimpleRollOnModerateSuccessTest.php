<?php
namespace DrdPlus\Tests\Skills\Combined\RollsOn\HandworkRollOnSuccess;

class HandworkSimpleRollOnModerateSuccessTest extends HandworkSimpleRollOnSuccessTest
{
    /**
     * @return int
     */
    protected function getExpectedDifficulty(): int
    {
        return 17;
    }

    /**
     * @return string
     */
    protected function getExpectedSuccessValue(): string
    {
        return 'handy';
    }

    /**
     * @return string
     */
    protected function getExpectedFailureValue(): string
    {
        return 'usable';
    }
}