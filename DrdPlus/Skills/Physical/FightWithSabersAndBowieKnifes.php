<?php
namespace DrdPlus\Skills\Physical;


/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @Doctrine\ORM\Mapping\Entity()
 */
class FightWithSabersAndBowieKnifes extends FightWithWeaponsUsingPhysicalSkill
{
    public const FIGHT_WITH_SABERS_AND_BOWIE_KNIFES = 'fight_with_sabers_and_bowie_knifes';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_SABERS_AND_BOWIE_KNIFES;
    }

}