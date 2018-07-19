<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;


/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @Doctrine\ORM\Mapping\Entity()
 */
class FightWithSwords extends FightWithWeaponsUsingPhysicalSkill
{
    public const FIGHT_WITH_SWORDS = 'fight_with_swords';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_SWORDS;
    }

}