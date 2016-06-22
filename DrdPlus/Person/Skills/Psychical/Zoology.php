<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Zoology extends PersonPsychicalSkill
{
    const ZOOLOGY = PsychicalSkillCode::ZOOLOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ZOOLOGY;
    }
}
