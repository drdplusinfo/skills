<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithMorningstarsAndMorgensterns extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_MORNINGSTARS_AND_MORGENSTERNS = 'fight_with_morningstars_and_morgensterns';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_MORNINGSTARS_AND_MORGENSTERNS;
    }

}