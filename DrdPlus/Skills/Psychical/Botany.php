<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Botany extends PsychicalSkill
{
    const BOTANY = PsychicalSkillCode::BOTANY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::BOTANY;
    }
}