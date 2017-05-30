<?php
namespace DrdPlus\Skills\Combined\RollsOn\HandworkRollOnSuccess;

use DrdPlus\RollsOn\QualityAndSuccess\SimpleRollOnSuccess;
use DrdPlus\Skills\Combined\RollsOn\HandworkQuality;

class HandworkSimpleRollOnLowSuccess extends SimpleRollOnSuccess
{
    const USABLE = 'usable';
    const USELESS = 'useless';

    /**
     * @param HandworkQuality $handworkQuality
     */
    public function __construct(HandworkQuality $handworkQuality)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct(14, $handworkQuality, self::USABLE, self::USELESS);
    }

}