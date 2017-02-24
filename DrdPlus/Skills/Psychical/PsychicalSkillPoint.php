<?php
namespace DrdPlus\Skills\Psychical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Properties\PropertyCode;
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
    public function getTypeName(): string
    {
        return static::PSYCHICAL;
    }

    /**
     * @return array|string[]
     */
    public function getRelatedProperties(): array
    {
        return [PropertyCode::WILL, PropertyCode::INTELLIGENCE];
    }

}