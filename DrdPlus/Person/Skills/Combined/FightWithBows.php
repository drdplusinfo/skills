<?php
namespace DrdPlus\Person\Skills\Combined;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithBows extends FightWithShootingWeapons
{
    const FIGHT_WITH_BOWS = 'fight_with_bows';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_BOWS;
    }
}