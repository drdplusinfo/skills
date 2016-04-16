<?php
namespace DrdPlus\Person\Skills\Combined;

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

    public function __construct(
        PersonCombinedSkill $personCombinedSkill,
        CombinedSkillPoint $skillPoint,
        IntegerInterface $requiredRankValue
    )
    {
        $this->personCombinedSkill = $personCombinedSkill;
        parent::__construct($personCombinedSkill, $skillPoint, $requiredRankValue);
    }

    /**
     * @return PersonCombinedSkill
     */
    public function getPersonSkill()
    {
        return $this->personCombinedSkill;
    }
}
