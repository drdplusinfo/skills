<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Skills\SkillPoint;

/**
 * @Doctrine\ORM\Mapping\Entity()
 */
class PhysicalSkillPoint extends SkillPoint
{

    public const PHYSICAL = SkillTypeCode::PHYSICAL;

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