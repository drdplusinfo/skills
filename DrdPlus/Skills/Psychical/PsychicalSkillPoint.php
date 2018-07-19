<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Skills\SkillPoint;

/**
 * @Doctrine\ORM\Mapping\Entity()
 */
class PsychicalSkillPoint extends SkillPoint
{

    public const PSYCHICAL = SkillTypeCode::PSYCHICAL;

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