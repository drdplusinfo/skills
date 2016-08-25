<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillTypeCode;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Person\Skills\PersonSkillPoint;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CombinedSkillPoint extends PersonSkillPoint
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
        return [Knack::KNACK, Charisma::CHARISMA];
    }

}