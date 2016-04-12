<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Knotting extends PersonCombinedSkill
{
    const KNOTTING = SkillCodes::KNOTTING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::KNOTTING;
    }
}
