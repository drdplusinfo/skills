<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightWithSabersAndBowieKnifes extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_SABERS_AND_BOWIE_KNIFES = 'fight_with_sabers_and_bowie_knifes';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_SABERS_AND_BOWIE_KNIFES;
    }

}