<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class DuskSight extends CombinedSkill
{
    const DUSK_SIGHT = CombinedSkillCode::DUSK_SIGHT;

    /**
     * @return string
     */
    public function getName()
    {
        return self::DUSK_SIGHT;
    }
}