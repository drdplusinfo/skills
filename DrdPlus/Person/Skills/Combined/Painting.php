<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Painting extends PersonCombinedSkill
{
    const PAINTING = CombinedSkillCode::PAINTING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::PAINTING;
    }
}
