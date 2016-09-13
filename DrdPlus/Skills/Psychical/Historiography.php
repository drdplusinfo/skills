<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Historiography extends PsychicalSkill
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