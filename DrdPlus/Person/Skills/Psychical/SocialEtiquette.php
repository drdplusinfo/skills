<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class SocialEtiquette extends PersonPsychicalSkill
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
