<?php
namespace DrdPlus\Person\Skills\Physical;
use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class ShieldUsage extends PersonPhysicalSkill
{
    const SHIELD_USAGE = PhysicalSkillCode::SHIELD_USAGE;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SHIELD_USAGE;
    }
}
