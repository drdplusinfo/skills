<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithSwords extends FightWithMeleeWeapon
{
    const FIGHT_WITH_SWORDS = 'fight_with_swords';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_SWORDS;
    }

}