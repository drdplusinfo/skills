<?php
namespace DrdPlus\Skills\Combined\RollsOn;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\RollsOn\QualityAndSuccess\RollOnQuality;
use DrdPlus\Skills\Combined\PlayingOnMusicInstrument;

/**
 * @method Roll2d6DrdPlus getRoll()
 */
class PlayingOnMusicInstrumentGameQuality extends RollOnQuality
{
    /**
     * @param Knack $knack
     * @param PlayingOnMusicInstrument $playingOnMusicInstrument
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     */
    public function __construct(
        Knack $knack,
        PlayingOnMusicInstrument $playingOnMusicInstrument,
        Roll2d6DrdPlus $roll2D6DrdPlus
    )
    {
        parent::__construct($knack->getValue() + $playingOnMusicInstrument->getBonus(), $roll2D6DrdPlus);
    }

}