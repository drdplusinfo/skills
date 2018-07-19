<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical\RollsOn;

use DrdPlus\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;
use DrdPlus\Skills\Psychical\MapsDrawing;

/**
 * See PPH page 150 right column, @link https://pph.drdplus.info/#vypocet_hodu_na_pouziti_mapy
 * @method Roll2d6DrdPlus getRoll()
 */
class RollOnMapUsage extends RollOnQuality
{
    /**
     * @param Intelligence $intelligence
     * @param MapsDrawing $mapsDrawing
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     */
    public function __construct(Intelligence $intelligence, MapsDrawing $mapsDrawing, Roll2d6DrdPlus $roll2D6DrdPlus)
    {
        parent::__construct($intelligence->getValue() + $mapsDrawing->getBonus(), $roll2D6DrdPlus);
    }

}