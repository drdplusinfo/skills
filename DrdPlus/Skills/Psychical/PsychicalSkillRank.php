<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Skills\SkillRank;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\PositiveInteger;

/**
 * @ORM\Entity
 */
class PsychicalSkillRank extends SkillRank
{
    /**
     * @var PsychicalSkill
     * @ORM\ManyToOne(targetEntity="PsychicalSkill", inversedBy="psychicalSkillRanks", cascade={"persist"})
     */
    private $psychicalSkill;
    /**
     * @var PsychicalSkillPoint
     * @ORM\OneToOne(targetEntity="PsychicalSkillPoint", cascade={"persist"})
     */
    private $psychicalSkillPoint;

    /**
     * @param PsychicalSkill $psychicalSkill
     * @param PsychicalSkillPoint $psychicalSkillPoint
     * @param PositiveInteger $requiredRankValue
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\WastedSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\CanNotUseZeroSkillPointForNonZeroSkillRank
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedRankValue
     */
    public function __construct(
        PsychicalSkill $psychicalSkill,
        PsychicalSkillPoint $psychicalSkillPoint,
        PositiveInteger $requiredRankValue
    )
    {
        $this->psychicalSkill = $psychicalSkill;
        $this->psychicalSkillPoint = $psychicalSkillPoint;
        parent::__construct($psychicalSkill, $psychicalSkillPoint, $requiredRankValue);
    }

    /**
     * @return PsychicalSkill
     */
    public function getSkill()
    {
        return $this->psychicalSkill;
    }

    /**
     * @return PsychicalSkillPoint
     */
    public function getSkillPoint()
    {
        return $this->psychicalSkillPoint;
    }
}