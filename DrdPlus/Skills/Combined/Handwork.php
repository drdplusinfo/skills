<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkQuality;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess\HandworkRollOnSuccess;
use DrdPlus\Skills\WithBonusToKnack;

/**
 * @link https://pph.drdplus.info/#rucni_prace
 * @Doctrine\ORM\Mapping\Entity()
 */
class Handwork extends CombinedSkill implements WithBonusToKnack
{
    public const HANDWORK = CombinedSkillCode::HANDWORK;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HANDWORK;
    }

    /**
     * @return int
     */
    public function getBonusToKnack(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @param Knack $knack
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @return HandworkQuality
     */
    public function createHandworkQuality(Knack $knack, Roll2d6DrdPlus $roll2D6DrdPlus): HandworkQuality
    {
        return new HandworkQuality($knack, $this, $roll2D6DrdPlus);
    }

    /**
     * @param Knack $knack
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @param int $difficultyModification
     * @return HandworkRollOnSuccess
     */
    public function createHandworkRollOnSuccess(
        Knack $knack,
        Roll2d6DrdPlus $roll2D6DrdPlus,
        int $difficultyModification
    ): HandworkRollOnSuccess
    {
        return HandworkRollOnSuccess::createIt(
            $this->createHandworkQuality($knack, $roll2D6DrdPlus),
            $difficultyModification
        );
    }

}