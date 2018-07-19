<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Skills\WithBonusToCharisma;

/**
 * @link https://pph.drdplus.info/#etiketa_podsveti
 * @Doctrine\ORM\Mapping\Entity()
 */
class EtiquetteOfGangland extends PsychicalSkill implements WithBonusToCharisma
{
    public const ETIQUETTE_OF_GANGLAND = PsychicalSkillCode::ETIQUETTE_OF_GANGLAND;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::ETIQUETTE_OF_GANGLAND;
    }

    /**
     * @return int
     */
    public function getBonusToCharisma(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }
}