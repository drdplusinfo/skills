<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Pedagogy extends CombinedSkill
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