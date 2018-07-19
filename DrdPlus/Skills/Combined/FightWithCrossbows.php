<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;


/**
 * @link https://pph.drdplus.info/#boj_se_strelnymi_zbranemi
 * @Doctrine\ORM\Mapping\Entity()
 */
class FightWithCrossbows extends FightWithShootingWeapons
{
    public const FIGHT_WITH_CROSSBOWS = 'fight_with_crossbows';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_CROSSBOWS;
    }
}