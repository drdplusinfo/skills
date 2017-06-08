<?php
namespace DrdPlus\Tests\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess;

class HandworkSimpleRollOnGreatSuccessTest extends HandworkSimpleRollOnSuccessTest
{
    /**
     * @return int
     */
    protected function getExpectedDifficulty(): int
    {
        return 20;
    }

    /**
     * @return string
     */
    protected function getExpectedSuccessValue(): string
    {
        return 'bravura';
    }

    /**
     * @return string
     */
    protected function getExpectedFailureValue(): string
    {
        return 'handy';
    }
}