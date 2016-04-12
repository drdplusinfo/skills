<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Cooking extends PersonCombinedSkill
{
    const COOKING = SkillCodes::COOKING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::COOKING;
    }

}
