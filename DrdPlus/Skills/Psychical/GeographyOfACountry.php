<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @link https://pph.drdplus.info/#zemepis
 * @ORM\Entity()
 */
class GeographyOfACountry extends PsychicalSkill implements WithBonusToIntelligence
{
    const GEOGRAPHY_OF_A_COUNTRY = PsychicalSkillCode::GEOGRAPHY_OF_A_COUNTRY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::GEOGRAPHY_OF_A_COUNTRY;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }

}