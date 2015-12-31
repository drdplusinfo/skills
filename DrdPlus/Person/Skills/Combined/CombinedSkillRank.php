<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Person\Skills\PersonSkillRank;
use Doctrine\Common\Annotations as ORM;
use Granam\Integer\IntegerInterface;

/**
 * @ORM\Table
 * @ORM\Entity
 */
class CombinedSkillRank extends PersonSkillRank
{
    public function __construct(CombinedSkillPoint $skillPoint, IntegerInterface $requiredRankValue)
    {
        parent::__construct($skillPoint, $requiredRankValue);
    }
}
