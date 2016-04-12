<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Seduction extends PersonCombinedSkill
{
    const SEDUCTION = SkillCodes::SEDUCTION;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SEDUCTION;
    }
}
