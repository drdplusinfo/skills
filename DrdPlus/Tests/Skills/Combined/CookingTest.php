<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Skills\Combined\Cooking;

class CookingTest extends WithBonusFromCombinedTest
{
    /**
     * @test
     */
    public function I_can_use_it_as_cooking_for_hunting_and_fishing_catch_processing()
    {
        self::assertTrue(
            is_a(Cooking::class, \DrdPlus\HuntingAndFishing\Cooking::class, true),
            'Skill ' . Cooking::class . ' has to implement ' . \DrdPlus\HuntingAndFishing\Cooking::class
        );
    }
}