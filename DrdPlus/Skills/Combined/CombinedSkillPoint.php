<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\SkillTypeCode;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Knack;
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
        return [Knack::KNACK, Charisma::CHARISMA];
    }

}