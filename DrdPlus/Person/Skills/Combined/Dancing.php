<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Dancing extends PersonCombinedSkill
{
    const DANCING = SkillCodes::DANCING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::DANCING;
    }
}
