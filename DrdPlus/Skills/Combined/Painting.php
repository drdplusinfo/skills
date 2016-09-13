<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Painting extends CombinedSkill
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