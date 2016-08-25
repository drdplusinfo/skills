<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Person\Skills\PersonSkillRank;
use Granam\Integer\PositiveInteger;

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

    /**
     * @param PersonPhysicalSkill $personPhysicalSkill
     * @param PhysicalSkillPoint $physicalSkillPoint
     * @param PositiveInteger $requiredRankValue
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyPaidPersonSkillPoint
     */
    public function __construct(
        PersonPhysicalSkill $personPhysicalSkill,
        PhysicalSkillPoint $physicalSkillPoint,
        PositiveInteger $requiredRankValue
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
