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
    public function getTypeName()
    {
        return static::COMBINED;
    }

    /**
     * @return string[]
     */
    public function getRelatedProperties()
    {
        return [PropertyCode::KNACK, PropertyCode::CHARISMA];
    }

}