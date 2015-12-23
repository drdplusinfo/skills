<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Historiography extends PersonPsychicalSkill
{
    const HISTORIOGRAPHY = SkillCodes::HISTORIOGRAPHY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HISTORIOGRAPHY;
    }
}
