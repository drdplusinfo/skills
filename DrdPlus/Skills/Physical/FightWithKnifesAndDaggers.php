<?php
namespace DrdPlus\Skills\Physical;


/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @Doctrine\ORM\Mapping\Entity()
 */
class FightWithKnifesAndDaggers extends FightWithWeaponsUsingPhysicalSkill
{
    public const FIGHT_WITH_KNIFES_AND_DAGGERS = 'fight_with_knifes_and_daggers';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_KNIFES_AND_DAGGERS;
    }

}