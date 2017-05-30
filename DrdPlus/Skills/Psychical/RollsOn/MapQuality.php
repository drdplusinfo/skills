<?php
namespace DrdPlus\Skills\Psychical\RollsOn;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;
use DrdPlus\Skills\Psychical\MapsDrawing;

/**
 * See PHP page 150 right column, @link https://pph.drdplus.info/#vypocet_kvality_mapy
 * @method Roll2d6DrdPlus getRoll()
 */
class MapQuality extends RollOnQuality
{
    /**
     * @param Knack $knack
     * @param MapsDrawing $mapsDrawing
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     */
    public function __construct(Knack $knack, MapsDrawing $mapsDrawing, Roll2d6DrdPlus $roll2D6DrdPlus)
    {
        parent::__construct($knack->getValue() + $mapsDrawing->getBonus(), $roll2D6DrdPlus);
    }

}