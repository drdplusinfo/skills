<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithKnifesAndDaggers extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_KNIFES_AND_DAGGERS = 'fight_with_knifes_and_daggers';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_KNIFES_AND_DAGGERS;
    }

}