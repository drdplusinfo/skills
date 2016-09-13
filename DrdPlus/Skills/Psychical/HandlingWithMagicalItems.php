<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HandlingWithMagicalItems extends PsychicalSkill
{
    const HANDLING_WITH_MAGICAL_ITEMS = PsychicalSkillCode::HANDLING_WITH_MAGICAL_ITEMS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HANDLING_WITH_MAGICAL_ITEMS;
    }
}