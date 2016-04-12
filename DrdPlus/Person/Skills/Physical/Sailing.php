<?php
namespace DrdPlus\Person\Skills\Physical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Sailing extends PersonPhysicalSkill
{
    const SAILING = SkillCodes::SAILING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SAILING;
    }
}
