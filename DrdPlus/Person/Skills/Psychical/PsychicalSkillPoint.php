<?php
namespace DrdPlus\Person\Skills\Psychical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\SkillCodes;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Person\Skills\PersonSkillPoint;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class PsychicalSkillPoint extends PersonSkillPoint
{

    const PSYCHICAL = SkillCodes::PSYCHICAL;

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
