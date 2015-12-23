<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Mythology extends PersonPsychicalSkill
{
    const MYTHOLOGY = SkillCodes::MYTHOLOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::MYTHOLOGY;
    }
}
