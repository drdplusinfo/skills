<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Technology extends PersonPsychicalSkill
{
    const TECHNOLOGY = SkillCodes::TECHNOLOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::TECHNOLOGY;
    }
}
