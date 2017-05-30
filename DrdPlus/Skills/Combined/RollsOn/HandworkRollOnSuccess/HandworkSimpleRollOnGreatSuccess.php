<?php
namespace DrdPlus\Skills\Combined\RollsOn\HandworkRollOnSuccess;

use DrdPlus\RollsOn\QualityAndSuccess\SimpleRollOnSuccess;
use DrdPlus\Skills\Combined\RollsOn\HandworkQuality;

class HandworkSimpleRollOnGreatSuccess extends SimpleRollOnSuccess
{
    const BRAVURA = 'bravura';

    /**
     * @param HandworkQuality $handworkQuality
     */
    public function __construct(HandworkQuality $handworkQuality)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct(20, $handworkQuality, self::BRAVURA, HandworkSimpleRollOnModerateSuccess::HANDY);
    }

}