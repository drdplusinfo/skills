<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\Skill;
use DrdPlus\Skills\SkillPoint;
use DrdPlus\Skills\SkillRank;
use Granam\Integer\PositiveInteger;

/**
 * @ORM\Entity
 */
class PhysicalSkillRank extends SkillRank
{
    /**
     * @var PhysicalSkill
     * @ORM\ManyToOne(targetEntity="PhysicalSkill", inversedBy="physicalSkillRanks", cascade={"persist"})
     */
    private $physicalSkill;
    /**
     * @var PhysicalSkillPoint
     * @ORM\OneToOne(targetEntity="PhysicalSkillPoint", cascade={"persist"})
     */
    private $physicalSkillPoint;

    /**
     * @param PhysicalSkill $physicalSkill
     * @param PhysicalSkillPoint $physicalSkillPoint
     * @param PositiveInteger $requiredRankValue
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\WastedSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\CanNotUseZeroSkillPointForNonZeroSkillRank
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedRankValue
     */
    public function __construct(
        PhysicalSkill $physicalSkill,
        PhysicalSkillPoint $physicalSkillPoint,
        PositiveInteger $requiredRankValue
    )
    {
        $this->physicalSkill = $physicalSkill;
        $this->physicalSkillPoint = $physicalSkillPoint;
        parent::__construct($physicalSkill, $physicalSkillPoint, $requiredRankValue);
    }

    /**
     * @return Skill|PhysicalSkill
     */
    public function getSkill(): Skill
    {
        return $this->physicalSkill;
    }

    /**
     * @return SkillPoint|PhysicalSkillPoint
     */
    public function getSkillPoint(): SkillPoint
    {
        return $this->physicalSkillPoint;
    }
}