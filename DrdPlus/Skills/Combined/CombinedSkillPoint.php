<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Skills\SkillPoint;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CombinedSkillPoint extends SkillPoint
{

    const COMBINED = SkillTypeCode::COMBINED;

    /**
     * return @string
     */
    public function getTypeName(): string
    {
        return static::COMBINED;
    }

    /**
     * @return array|string[]
     */
    public function getRelatedProperties(): array
    {
        return [PropertyCode::KNACK, PropertyCode::CHARISMA];
    }

}