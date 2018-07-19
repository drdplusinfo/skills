<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess;

use DrdPlus\Skills\Combined\RollsOnQuality\HandworkQuality;

class HandworkSimpleRollOnLowSuccess extends HandworkSimpleRollOnSuccess
{
    public const USABLE = 'usable';
    public const USELESS = 'useless';

    /**
     * @param HandworkQuality $handworkQuality
     * @param int $difficultyModification
     */
    public function __construct(HandworkQuality $handworkQuality, int $difficultyModification)
    {
        parent::__construct(14, $difficultyModification, $handworkQuality, self::USABLE, self::USELESS);
    }

}