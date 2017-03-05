<?php
namespace DrdPlus\Skills\Combined;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Skills\Combined\RollsOn\PaintingQuality;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class Painting extends CombinedSkill implements WithBonus
{
    const PAINTING = CombinedSkillCode::PAINTING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::PAINTING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @param Knack $knack
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @return PaintingQuality
     */
    public function getPaintingQuality(Knack $knack, Roll2d6DrdPlus $roll2D6DrdPlus): PaintingQuality
    {
        return new PaintingQuality($knack, $this, $roll2D6DrdPlus);
    }
}