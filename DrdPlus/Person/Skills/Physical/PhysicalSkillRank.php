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
    /**
     * @var PhysicalSkillPoint
     * @ORM\OneToOne(targetEntity="\DrdPlus\Person\Skills\Physical\PhysicalSkillPoint", cascade={"persist"})
     */
    private $physicalSkillPoint;

    public function __construct(
        PersonPhysicalSkill $personPhysicalSkill,
        PhysicalSkillPoint $physicalSkillPoint,
        IntegerInterface $requiredRankValue
    )
    {
        $this->personPhysicalSkill = $personPhysicalSkill;
        $this->physicalSkillPoint = $physicalSkillPoint;
        parent::__construct($personPhysicalSkill, $physicalSkillPoint, $requiredRankValue);
    }

    /**
     * @return PersonPhysicalSkill
     */
    public function getPersonSkill()
    {
        return $this->personPhysicalSkill;
    }

    /**
     * @return PhysicalSkillPoint
     */
    public function getPersonSkillPoint()
    {
        return $this->physicalSkillPoint;
    }
}
