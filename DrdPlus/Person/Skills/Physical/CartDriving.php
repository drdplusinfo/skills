<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class CartDriving extends PersonPhysicalSkill
{
    const CART_DRIVING = SkillCodes::CART_DRIVING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::CART_DRIVING;
    }
}
