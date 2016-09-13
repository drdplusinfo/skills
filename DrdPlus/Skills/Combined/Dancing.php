<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Dancing extends CombinedSkill
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