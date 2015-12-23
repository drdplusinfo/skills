<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Person\Skills\PersonSkillPoint;
use Doctrine\ORM\Mapping as ORM;

class PhysicalSkillPoint extends PersonSkillPoint
{

    const PHYSICAL = SkillCodes::PHYSICAL;

    /**
     * return @string
     */
    public function getTypeName()
    {
        return static::PHYSICAL;
    }

    /**
     * @return string[]
     */
    public function getRelatedProperties()
    {
        return [Strength::STRENGTH, Agility::AGILITY];
    }

}
