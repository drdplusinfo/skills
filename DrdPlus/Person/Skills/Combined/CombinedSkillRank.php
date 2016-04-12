<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Person\Skills\PersonSkillRank;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\IntegerInterface;

/**
 * @ORM\Entity()
 */
class CombinedSkillRank extends PersonSkillRank
{
    public function __construct(CombinedSkillPoint $skillPoint, IntegerInterface $requiredRankValue)
    {
        parent::__construct($skillPoint, $requiredRankValue);
    }
}
