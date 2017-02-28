<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class MapsDrawing extends PsychicalSkill implements WithBonus
{
    const MAPS_DRAWING = PsychicalSkillCode::MAPS_DRAWING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::MAPS_DRAWING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 2;
    }

}