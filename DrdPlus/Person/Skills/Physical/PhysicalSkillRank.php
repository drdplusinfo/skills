<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Person\Skills\PersonSkillRank;
use Granam\Integer\IntegerInterface;

/**
 * @ORM\Entity
 */
class PhysicalSkillRank extends PersonSkillRank
{
    public function __construct(PhysicalSkillPoint $skillPoint, IntegerInterface $requiredRankValue)
    {
        parent::__construct($skillPoint, $requiredRankValue);
    }
}
