<?php
namespace DrdPlus\Skills\Psychical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\PropertyCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Skills\SkillPoint;

/**
 * @ORM\Entity()
 */
class PsychicalSkillPoint extends SkillPoint
{

    const PSYCHICAL = SkillTypeCode::PSYCHICAL;

    /**
     * return @string
     */
    public function getTypeName()
    {
        return static::PSYCHICAL;
    }

    /**
     * @return string[]
     */
    public function getRelatedProperties()
    {
        return [PropertyCode::WILL, PropertyCode::INTELLIGENCE];
    }

}