<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Statuary extends CombinedSkill
{
    const STATUARY = CombinedSkillCode::STATUARY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::STATUARY;
    }
}