<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightUnarmed extends FightWithWeaponUsingPhysicalSkill
{
    const FIGHT_UNARMED = 'fight_unarmed';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_UNARMED;
    }

}