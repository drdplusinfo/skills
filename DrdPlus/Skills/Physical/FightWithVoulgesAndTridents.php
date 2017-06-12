<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightWithVoulgesAndTridents extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_VOULGES_AND_TRIDENTS = 'fight_with_voulges_and_tridents';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_VOULGES_AND_TRIDENTS;
    }

}