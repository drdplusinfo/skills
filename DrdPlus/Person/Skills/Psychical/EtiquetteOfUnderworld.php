<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class EtiquetteOfUnderworld extends PersonPsychicalSkill
{
    const ETIQUETTE_OF_UNDERWORLD = PsychicalSkillCode::ETIQUETTE_OF_UNDERWORLD;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ETIQUETTE_OF_UNDERWORLD;
    }
}
