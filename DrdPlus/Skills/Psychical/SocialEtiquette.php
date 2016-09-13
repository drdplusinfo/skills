<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class SocialEtiquette extends PsychicalSkill
{
    const SOCIAL_ETIQUETTE = PsychicalSkillCode::SOCIAL_ETIQUETTE;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SOCIAL_ETIQUETTE;
    }
}