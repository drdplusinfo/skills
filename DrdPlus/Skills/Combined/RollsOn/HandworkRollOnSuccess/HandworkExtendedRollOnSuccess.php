<?php
namespace DrdPlus\Skills\Combined\RollsOn\HandworkRollOnSuccess;

use DrdPlus\RollsOn\QualityAndSuccess\ExtendedRollOnSuccess;
use DrdPlus\Skills\Combined\RollsOn\HandworkQuality;

/**
 * @method HandworkQuality getRollOnQuality
 */
class HandworkExtendedRollOnSuccess extends ExtendedRollOnSuccess
{
    /**
     * @param HandworkQuality $handworkQuality
     * @return HandworkExtendedRollOnSuccess|static
     */
    public static function createIt(HandworkQuality $handworkQuality)
    {
        return new static(
            new HandworkSimpleRollOnLowSuccess($handworkQuality),
            new HandworkSimpleRollOnModerateSuccess($handworkQuality),
            new HandworkSimpleRollOnGreatSuccess($handworkQuality)
        );
    }

    /**
     * @param HandworkSimpleRollOnLowSuccess $firstSimpleRollOnSuccess
     * @param HandworkSimpleRollOnModerateSuccess $secondSimpleRollOnSuccess
     * @param HandworkSimpleRollOnGreatSuccess $thirdSimpleRollOnSuccess
     */
    public function __construct(
        HandworkSimpleRollOnLowSuccess $firstSimpleRollOnSuccess,
        HandworkSimpleRollOnModerateSuccess $secondSimpleRollOnSuccess,
        HandworkSimpleRollOnGreatSuccess $thirdSimpleRollOnSuccess
    )
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct($firstSimpleRollOnSuccess, $secondSimpleRollOnSuccess, $thirdSimpleRollOnSuccess);
    }

}