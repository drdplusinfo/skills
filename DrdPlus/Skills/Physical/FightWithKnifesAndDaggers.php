<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightWithKnifesAndDaggers extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_KNIFES_AND_DAGGERS = 'fight_with_knifes_and_daggers';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_KNIFES_AND_DAGGERS;
    }

}