<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\PropertyCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Skills\SkillPoint;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PhysicalSkillPoint extends SkillPoint
{

    const PHYSICAL = SkillTypeCode::PHYSICAL;

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
        return [PropertyCode::STRENGTH, PropertyCode::AGILITY];
    }

}