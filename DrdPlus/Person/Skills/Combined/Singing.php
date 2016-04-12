<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Singing extends PersonCombinedSkill
{
    const SINGING = SkillCodes::SINGING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SINGING;
    }
}
