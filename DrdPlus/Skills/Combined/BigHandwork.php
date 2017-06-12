<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToKnack;

/**
 * @link https://pph.drdplus.info/#velke_rucni_prace
 * @ORM\Entity()
 */
class BigHandwork extends CombinedSkill implements WithBonusToKnack
{
    const BIG_HANDWORK = CombinedSkillCode::BIG_HANDWORK;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::BIG_HANDWORK;
    }

    /**
     * @return int
     */
    public function getBonusToKnack(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }

}