<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class DuskSight extends PersonCombinedSkill
{
    const DUSK_SIGHT = SkillCodes::DUSK_SIGHT;

    /**
     * @return string
     */
    public function getName()
    {
        return self::DUSK_SIGHT;
    }
}
