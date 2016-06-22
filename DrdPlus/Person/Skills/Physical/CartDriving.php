<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CartDriving extends PersonPhysicalSkill
{
    const CART_DRIVING = PhysicalSkillCode::CART_DRIVING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::CART_DRIVING;
    }
}
