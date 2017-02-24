<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
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