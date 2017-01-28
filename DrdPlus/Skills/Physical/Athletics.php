<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Skills\PhysicalSkillCode;

/**
 * @ORM\Entity()
 */
class Athletics extends PhysicalSkill implements \DrdPlus\Properties\Derived\Athletics
{
    const ATHLETICS = PhysicalSkillCode::ATHLETICS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ATHLETICS;
    }

    /**
     * @return PhysicalSkillRank
     */
    public function getAthleticsBonus()
    {
        // bonus is equal to current rank (0 -> 3)
        return $this->getCurrentSkillRank();
    }

}