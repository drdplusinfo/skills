<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class HandlingWithMagicalItems extends PersonPsychicalSkill
{
    const HANDLING_OF_MAGICAL_ITEMS = SkillCodes::HANDLING_OF_MAGICAL_ITEMS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HANDLING_OF_MAGICAL_ITEMS;
    }
}
