<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Historiography extends PersonPsychicalSkill
{
    const HISTORIOGRAPHY = PsychicalSkillCode::HISTORIOGRAPHY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HISTORIOGRAPHY;
    }
}
