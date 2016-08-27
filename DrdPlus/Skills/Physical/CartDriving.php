<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CartDriving extends PhysicalSkill
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
