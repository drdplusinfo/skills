<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Skills\Skill;
use DrdPlus\Skills\SkillPoint;
use DrdPlus\Skills\SkillRank;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\PositiveInteger;

/**
 * @ORM\Entity()
 */
class CombinedSkillRank extends SkillRank
{
    /**
     * @var CombinedSkill
     * @ORM\ManyToOne(targetEntity="CombinedSkill", inversedBy="combinedSkillRanks", cascade={"persist"})
     */
    private $combinedSkill;
    /**
     * @var CombinedSkillPoint
     * @ORM\OneToOne(targetEntity="CombinedSkillPoint", cascade={"persist"})
     */
    private $combinedSkillPoint;

    /**
     * @param CombinedSkill $combinedSkill
     * @param CombinedSkillPoint $combinedSkillPoint
     * @param PositiveInteger $requiredRankValue
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\WastedSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\CanNotUseZeroSkillPointForNonZeroSkillRank
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedRankValue
     */
    public function __construct(
        CombinedSkill $combinedSkill,
        CombinedSkillPoint $combinedSkillPoint,
        PositiveInteger $requiredRankValue
    )
    {
        $this->combinedSkill = $combinedSkill;
        $this->combinedSkillPoint = $combinedSkillPoint;
        parent::__construct($combinedSkill, $combinedSkillPoint, $requiredRankValue);
    }

    /**
     * @return Skill|CombinedSkill
     */
    public function getSkill(): Skill
    {
        return $this->combinedSkill;
    }

    /**
     * @return SkillPoint|CombinedSkillPoint
     */
    public function getSkillPoint(): SkillPoint
    {
        return $this->combinedSkillPoint;
    }

}