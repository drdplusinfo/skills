<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class BigHandwork extends PersonCombinedSkill
{
    const BIG_HANDWORK = SkillCodes::BIG_HANDWORK;

    /**
     * @return string
     */
    public function getName()
    {
        return self::BIG_HANDWORK;
    }

}
