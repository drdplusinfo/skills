<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Person\Skills\PersonSkillPoint;
use DrdPlus\Person\Skills\PersonSkillRank;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\IntegerInterface;

/**
 * @ORM\Entity()
 */
class CombinedSkillRank extends PersonSkillRank
{
    /**
     * @var PersonCombinedSkill
     * @ORM\ManyToOne(targetEntity="PersonCombinedSkill", inversedBy="combinedSkillRanks", cascade={"persist"})
     */
    private $personCombinedSkill;
    /**
     * @var CombinedSkillPoint
     * @ORM\OneToOne(targetEntity="\DrdPlus\Person\Skills\Combined\CombinedSkillPoint", cascade={"persist"})
     */
    private $combinedSkillPoint;

    public function __construct(
        PersonCombinedSkill $personCombinedSkill,
        CombinedSkillPoint $combinedSkillPoint,
        IntegerInterface $requiredRankValue
    )
    {
        $this->personCombinedSkill = $personCombinedSkill;
        $this->combinedSkillPoint = $combinedSkillPoint;
        parent::__construct($personCombinedSkill, $combinedSkillPoint, $requiredRankValue);
    }

    /**
     * @return PersonCombinedSkill
     */
    public function getPersonSkill()
    {
        return $this->personCombinedSkill;
    }

    /**
     * @return PersonCombinedSkill
     */
    public function getPersonSkillPoint()
    {
        return $this->combinedSkillPoint;
    }

}
