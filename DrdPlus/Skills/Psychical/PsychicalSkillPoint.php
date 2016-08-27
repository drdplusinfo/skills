<?php
namespace DrdPlus\Skills\Psychical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\SkillTypeCode;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
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
        return [Will::WILL, Intelligence::INTELLIGENCE];
    }

}
