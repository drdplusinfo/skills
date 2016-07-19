<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithMorningStarsAndMorgensterns extends FightWithWeapon
{
    const FIGHT_WITH_MORNING_STARS_AND_MORGENSTERNS = 'fight_with_morning_stars_and_morgensterns';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_MORNING_STARS_AND_MORGENSTERNS;
    }

}