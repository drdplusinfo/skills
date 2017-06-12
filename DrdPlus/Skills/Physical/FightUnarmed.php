<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightUnarmed extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_UNARMED = 'fight_unarmed';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_UNARMED;
    }

}