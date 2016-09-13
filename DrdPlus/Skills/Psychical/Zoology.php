<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Zoology extends PsychicalSkill
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