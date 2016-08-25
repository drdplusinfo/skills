<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Person\Skills\PersonSkillRank;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\PositiveInteger;

/**
 * @ORM\Entity
 */
class PsychicalSkillRank extends PersonSkillRank
{
    /**
     * @var PersonPsychicalSkill
     * @ORM\ManyToOne(targetEntity="PersonPsychicalSkill", inversedBy="psychicalSkillRanks", cascade={"persist"})
     */
    private $personPsychicalSkill;
    /**
     * @var PsychicalSkillPoint
     * @ORM\OneToOne(targetEntity="\DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint", cascade={"persist"})
     */
    private $psychicalSkillPoint;

    /**
     * @param PersonPsychicalSkill $personPsychicalSkill
     * @param PsychicalSkillPoint $psychicalSkillPoint
     * @param PositiveInteger $requiredRankValue
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyPaidPersonSkillPoint
     */
    public function __construct(
        PersonPsychicalSkill $personPsychicalSkill,
        PsychicalSkillPoint $psychicalSkillPoint,
        PositiveInteger $requiredRankValue
    )
    {
        $this->personPsychicalSkill = $personPsychicalSkill;
        $this->psychicalSkillPoint = $psychicalSkillPoint;
        parent::__construct($personPsychicalSkill, $psychicalSkillPoint, $requiredRankValue);
    }

    /**
     * @return PersonPsychicalSkill
     */
    public function getPersonSkill()
    {
        return $this->personPsychicalSkill;
    }

    /**
     * @return PsychicalSkillPoint
     */
    public function getPersonSkillPoint()
    {
        return $this->psychicalSkillPoint;
    }
}
