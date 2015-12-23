<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Zoology extends PersonPsychicalSkill
{
    const ZOOLOGY = SkillCodes::ZOOLOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ZOOLOGY;
    }
}
