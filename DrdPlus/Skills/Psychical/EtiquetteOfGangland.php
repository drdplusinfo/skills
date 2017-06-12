<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToCharisma;

/**
 * @link https://pph.drdplus.info/#etiketa_podsveti
 * @ORM\Entity()
 */
class EtiquetteOfGangland extends PsychicalSkill implements WithBonusToCharisma
{
    const ETIQUETTE_OF_GANGLAND = PsychicalSkillCode::ETIQUETTE_OF_GANGLAND;

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