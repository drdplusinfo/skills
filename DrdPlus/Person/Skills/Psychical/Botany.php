<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Botany extends PersonPsychicalSkill
{
    const BOTANY = SkillCodes::BOTANY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::BOTANY;
    }
}
