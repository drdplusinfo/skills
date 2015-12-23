<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\PersonSkillRank;
use Doctrine\Common\Annotations as ORM;
use Granam\Integer\IntegerInterface;

/**
 * @ORM\Table
 * @ORM\Entity
 */
class CombinedSkillRank extends PersonSkillRank
{
    public function __construct(ProfessionLevel $professionLevel, CombinedSkillPoint $skillPoint, IntegerInterface $requiredRankValue)
    {
        parent::__construct($professionLevel, $skillPoint, $requiredRankValue);
    }
}
