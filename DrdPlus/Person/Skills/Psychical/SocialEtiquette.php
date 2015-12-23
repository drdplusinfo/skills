<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class SocialEtiquette extends PersonPsychicalSkill
{
    const SOCIAL_ETIQUETTE = SkillCodes::SOCIAL_ETIQUETTE;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SOCIAL_ETIQUETTE;
    }
}
