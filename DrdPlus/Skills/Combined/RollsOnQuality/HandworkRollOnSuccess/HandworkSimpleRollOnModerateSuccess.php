<?php
namespace DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess;

use DrdPlus\Skills\Combined\RollsOnQuality\HandworkQuality;

class HandworkSimpleRollOnModerateSuccess extends HandworkSimpleRollOnSuccess
{
    const HANDY = 'handy';

    /**
     * @param HandworkQuality $handworkQuality
     * @param int $difficultyModification
     */
    public function __construct(HandworkQuality $handworkQuality, int $difficultyModification)
    {
        parent::__construct(
            17,
            $difficultyModification,
            $handworkQuality,
            self::HANDY,
            HandworkSimpleRollOnLowSuccess::USABLE
        );
    }

}