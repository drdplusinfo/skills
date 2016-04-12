<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Person\Skills\PersonSkillRank;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\IntegerInterface;

/**
 * @ORM\Entity
 */
class PsychicalSkillRank extends PersonSkillRank
{
    public function __construct(PsychicalSkillPoint $skillPoint, IntegerInterface $requiredRankValue)
    {
        parent::__construct($skillPoint, $requiredRankValue);
    }
}
