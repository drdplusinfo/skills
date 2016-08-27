<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Cooking extends CombinedSkill
{
    const COOKING = CombinedSkillCode::COOKING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::COOKING;
    }

}