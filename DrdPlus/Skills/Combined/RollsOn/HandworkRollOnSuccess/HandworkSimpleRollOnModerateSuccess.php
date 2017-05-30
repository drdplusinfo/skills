<?php
namespace DrdPlus\Skills\Combined\RollsOn\HandworkRollOnSuccess;

use DrdPlus\RollsOn\QualityAndSuccess\SimpleRollOnSuccess;
use DrdPlus\Skills\Combined\RollsOn\HandworkQuality;

class HandworkSimpleRollOnModerateSuccess extends SimpleRollOnSuccess
{
    const HANDY = 'handy';

    /**
     * @param HandworkQuality $handworkQuality
     */
    public function __construct(HandworkQuality $handworkQuality)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct(17, $handworkQuality, self::HANDY, HandworkSimpleRollOnLowSuccess::USABLE);
    }

}