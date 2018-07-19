<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess;

use DrdPlus\RollsOn\QualityAndSuccess\ExtendedRollOnSuccess;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkQuality;

/**
 * @method HandworkQuality getRollOnQuality
 */
class HandworkRollOnSuccess extends ExtendedRollOnSuccess
{
    /**
     * @param HandworkQuality $handworkQuality
     * @param int $difficultyModification
     * @return HandworkRollOnSuccess
     */
    public static function createIt(HandworkQuality $handworkQuality, int $difficultyModification): HandworkRollOnSuccess
    {
        return new static($handworkQuality, $difficultyModification);
    }

    /**
     * @param HandworkQuality $handworkQuality
     * @param int $difficultyModification
     */
    public function __construct(HandworkQuality $handworkQuality, int $difficultyModification)
    {
        parent::__construct(
            new HandworkSimpleRollOnLowSuccess($handworkQuality, $difficultyModification),
            new HandworkSimpleRollOnModerateSuccess($handworkQuality, $difficultyModification),
            new HandworkSimpleRollOnGreatSuccess($handworkQuality, $difficultyModification)
        );
    }
}