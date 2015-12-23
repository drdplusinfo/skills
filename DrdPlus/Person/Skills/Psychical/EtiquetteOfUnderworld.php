<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class EtiquetteOfUnderworld extends PersonPsychicalSkill
{
    const ETIQUETTE_OF_UNDERWORLD = SkillCodes::ETIQUETTE_OF_UNDERWORLD;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ETIQUETTE_OF_UNDERWORLD;
    }
}
