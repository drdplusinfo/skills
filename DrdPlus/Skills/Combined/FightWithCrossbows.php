<?php
namespace DrdPlus\Skills\Combined;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_strelnymi_zbranemi
 * @ORM\Entity()
 */
class FightWithCrossbows extends FightWithShootingWeapons
{
    const FIGHT_WITH_CROSSBOWS = 'fight_with_crossbows';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_CROSSBOWS;
    }
}