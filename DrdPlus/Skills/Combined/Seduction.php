<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Seduction extends CombinedSkill
{
    const SEDUCTION = CombinedSkillCode::SEDUCTION;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::SEDUCTION;
    }
}