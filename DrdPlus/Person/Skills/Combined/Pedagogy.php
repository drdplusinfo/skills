<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Pedagogy extends PersonCombinedSkill
{
    const PEDAGOGY = SkillCodes::PEDAGOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::PEDAGOGY;
    }

}
