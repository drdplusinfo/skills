<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
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