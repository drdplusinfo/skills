<?php
namespace DrdPlus\Person\Skills\Physical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Swimming extends PersonPhysicalSkill
{
    const SWIMMING = SkillCodes::SWIMMING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SWIMMING;
    }
}
