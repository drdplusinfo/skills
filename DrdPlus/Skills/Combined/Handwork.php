<?php
namespace DrdPlus\Skills\Combined;

use Drd\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkQuality;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess\HandworkExtendedRollOnSuccess;
use DrdPlus\Skills\WithBonusToKnack;

/**
 * @link https://pph.drdplus.info/#rucni_prace
 * @ORM\Entity()
 */
class Handwork extends CombinedSkill implements WithBonusToKnack
{
    const HANDWORK = CombinedSkillCode::HANDWORK;

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
     * @return HandworkExtendedRollOnSuccess
     */
    public function createRollOnSuccess(
        Knack $knack,
        Roll2d6DrdPlus $roll2D6DrdPlus,
        int $difficultyModification
    ): HandworkExtendedRollOnSuccess
    {
        return HandworkExtendedRollOnSuccess::createIt(
            $this->createHandworkQuality($knack, $roll2D6DrdPlus),
            $difficultyModification
        );
    }

}