<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Singing extends PersonCombinedSkill
{
    const SINGING = CombinedSkillCode::SINGING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SINGING;
    }
}
