<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\Common\Annotations as ORM;
use DrdPlus\Person\Skills\PersonSkillRank;
use Granam\Integer\IntegerInterface;

/**
 * @ORM\Table
 * @ORM\Entity
 */
class PhysicalSkillRank extends PersonSkillRank
{
    public function __construct(PhysicalSkillPoint $skillPoint, IntegerInterface $requiredRankValue)
    {
        parent::__construct($skillPoint, $requiredRankValue);
    }
}
