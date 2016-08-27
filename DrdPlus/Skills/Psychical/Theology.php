<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Theology extends PsychicalSkill
{
    const THEOLOGY = PsychicalSkillCode::THEOLOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::THEOLOGY;
    }
}
