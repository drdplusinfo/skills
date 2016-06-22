<?php
namespace DrdPlus\Person\Skills\Physical;
use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Swimming extends PersonPhysicalSkill
{
    const SWIMMING = PhysicalSkillCode::SWIMMING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SWIMMING;
    }
}
