<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Pedagogy extends PersonCombinedSkill
{
    const PEDAGOGY = CombinedSkillCode::PEDAGOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::PEDAGOGY;
    }

}
