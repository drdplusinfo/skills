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
    /**
     * @var PersonPhysicalSkill
     * @ORM\ManyToOne(targetEntity="PersonPhysicalSkill", inversedBy="physicalSkillRanks", cascade={"persist"})
     */
    private $personPhysicalSkill;

    public function __construct(
        PersonPhysicalSkill $personPhysicalSkill,
        PhysicalSkillPoint $skillPoint,
        IntegerInterface $requiredRankValue
    )
    {
        $this->personPhysicalSkill = $personPhysicalSkill;
        parent::__construct($personPhysicalSkill, $skillPoint, $requiredRankValue);
    }

    /**
     * @return PersonPhysicalSkill
     */
    public function getPersonSkill()
    {
        return $this->personPhysicalSkill;
    }
}
