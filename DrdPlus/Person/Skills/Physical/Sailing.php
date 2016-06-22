<?php
namespace DrdPlus\Person\Skills\Physical;
use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Sailing extends PersonPhysicalSkill
{
    const SAILING = PhysicalSkillCode::SAILING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SAILING;
    }
}
