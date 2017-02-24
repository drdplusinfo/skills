<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class BigHandwork extends CombinedSkill
{
    const BIG_HANDWORK = CombinedSkillCode::BIG_HANDWORK;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::BIG_HANDWORK;
    }

}