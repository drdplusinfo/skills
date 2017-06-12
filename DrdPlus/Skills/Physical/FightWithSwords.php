<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightWithSwords extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_SWORDS = 'fight_with_swords';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_SWORDS;
    }

}