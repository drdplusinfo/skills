<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Knotting extends CombinedSkill
{
    const KNOTTING = CombinedSkillCode::KNOTTING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::KNOTTING;
    }
}
