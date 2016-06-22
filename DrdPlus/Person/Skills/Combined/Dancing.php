<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Dancing extends PersonCombinedSkill
{
    const DANCING = CombinedSkillCode::DANCING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::DANCING;
    }
}
