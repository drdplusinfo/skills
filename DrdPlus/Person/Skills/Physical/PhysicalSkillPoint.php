<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillTypeCode;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Person\Skills\PersonSkillPoint;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PhysicalSkillPoint extends PersonSkillPoint
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
        return [Strength::STRENGTH, Agility::AGILITY];
    }

}
