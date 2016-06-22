<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class DuskSight extends PersonCombinedSkill
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
