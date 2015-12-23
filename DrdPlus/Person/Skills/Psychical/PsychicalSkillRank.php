<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\PersonSkillRank;
use Doctrine\Common\Annotations as ORM;
use Granam\Integer\IntegerInterface;

/**
 * @ORM\Table
 * @ORM\Entity
 */
class PsychicalSkillRank extends PersonSkillRank
{
    public function __construct(ProfessionLevel $professionLevel, PsychicalSkillPoint $skillPoint, IntegerInterface $requiredRankValue)
    {
        parent::__construct($professionLevel, $skillPoint, $requiredRankValue);
    }
}
