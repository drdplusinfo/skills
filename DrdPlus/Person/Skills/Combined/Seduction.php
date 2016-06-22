<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Seduction extends PersonCombinedSkill
{
    const SEDUCTION = CombinedSkillCode::SEDUCTION;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SEDUCTION;
    }
}
