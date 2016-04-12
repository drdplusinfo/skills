<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HandlingWithMagicalItems extends PersonPsychicalSkill
{
    const HANDLING_WITH_MAGICAL_ITEMS = SkillCodes::HANDLING_WITH_MAGICAL_ITEMS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HANDLING_WITH_MAGICAL_ITEMS;
    }
}
