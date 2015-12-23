<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class GeographyOfACountry extends PersonPsychicalSkill
{
    const GEOGRAPHY_OF_A_COUNTRY = SkillCodes::GEOGRAPHY_OF_A_COUNTRY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::GEOGRAPHY_OF_A_COUNTRY;
    }
}
