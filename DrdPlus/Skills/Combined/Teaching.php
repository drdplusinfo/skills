<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Teaching extends CombinedSkill
{
    const TEACHING = CombinedSkillCode::TEACHING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::TEACHING;
    }

}