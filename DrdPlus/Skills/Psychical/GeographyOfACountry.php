<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @link https://pph.drdplus.info/#zemepis
 * @Doctrine\ORM\Mapping\Entity()
 */
class GeographyOfACountry extends PsychicalSkill implements WithBonusToIntelligence
{
    public const GEOGRAPHY_OF_A_COUNTRY = PsychicalSkillCode::GEOGRAPHY_OF_A_COUNTRY;

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