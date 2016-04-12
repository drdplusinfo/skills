<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Theology extends PersonPsychicalSkill
{
    const THEOLOGY = SkillCodes::THEOLOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::THEOLOGY;
    }
}
