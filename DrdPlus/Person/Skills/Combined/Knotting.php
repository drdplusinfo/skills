<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Knotting extends PersonCombinedSkill
{
    const KNOTTING = CombinedSkillCode::KNOTTING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::KNOTTING;
    }
}
