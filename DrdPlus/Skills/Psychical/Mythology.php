<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Mythology extends PsychicalSkill
{
    const MYTHOLOGY = PsychicalSkillCode::MYTHOLOGY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::MYTHOLOGY;
    }
}