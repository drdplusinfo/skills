<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Herbalism extends PersonCombinedSkill
{
    const HERBALISM = SkillCodes::HERBALISM;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HERBALISM;
    }
}
