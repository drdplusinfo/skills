<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightWithMorningstarsAndMorgensterns extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_MORNINGSTARS_AND_MORGENSTERNS = 'fight_with_morningstars_and_morgensterns';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_MORNINGSTARS_AND_MORGENSTERNS;
    }

}