<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class GeographyOfACountry extends PsychicalSkill
{
    const GEOGRAPHY_OF_A_COUNTRY = PsychicalSkillCode::GEOGRAPHY_OF_A_COUNTRY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::GEOGRAPHY_OF_A_COUNTRY;
    }
}