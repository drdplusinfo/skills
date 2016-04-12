<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Painting extends PersonCombinedSkill
{
    const PAINTING = SkillCodes::PAINTING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::PAINTING;
    }
}
