<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Properties\PropertyCode;
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
    public function getTypeName(): string
    {
        return static::PHYSICAL;
    }

    /**
     * @return string[]
     */
    public function getRelatedProperties(): array
    {
        return [PropertyCode::STRENGTH, PropertyCode::AGILITY];
    }

}