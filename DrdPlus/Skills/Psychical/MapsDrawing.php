<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Calculations\SumAndRound;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Skills\Psychical\RollsOn\MapQuality;
use DrdPlus\Skills\Psychical\RollsOn\RollOnMapUsage;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#kresleni_map
 * @Doctrine\ORM\Mapping\Entity()
 */
class MapsDrawing extends PsychicalSkill implements WithBonus
{
    public const MAPS_DRAWING = PsychicalSkillCode::MAPS_DRAWING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::MAPS_DRAWING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 2;
    }

    /**
     * @param Knack $knack
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @return MapQuality
     */
    public function getCreatedMapQuality(Knack $knack, Roll2d6DrdPlus $roll2D6DrdPlus): MapQuality
    {
        return new MapQuality($knack, $this, $roll2D6DrdPlus);
    }

    /**
     * @param Intelligence $intelligence
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @return RollOnMapUsage
     */
    public function getRollOnMapUsage(Intelligence $intelligence, Roll2d6DrdPlus $roll2D6DrdPlus): RollOnMapUsage
    {
        return new RollOnMapUsage($intelligence, $this, $roll2D6DrdPlus);
    }

    /**
     * @param MapQuality $usedMapQuality
     * @param RollOnMapUsage $rollOnThatMapUsage
     * @return int
     */
    public function getBonusToNavigation(MapQuality $usedMapQuality, RollOnMapUsage $rollOnThatMapUsage): int
    {
        $usefulQuality = min($usedMapQuality->getValue(), $rollOnThatMapUsage->getValue());

        return SumAndRound::round($usefulQuality / 6);
    }

}