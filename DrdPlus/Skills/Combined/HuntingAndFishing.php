<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HuntingAndFishing extends CombinedSkill
{
    const HUNTING_AND_FISHING = CombinedSkillCode::HUNTING_AND_FISHING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HUNTING_AND_FISHING;
    }
}