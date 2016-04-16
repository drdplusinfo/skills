<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Person\Skills\PersonSkillRank;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\IntegerInterface;

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

    public function __construct(
        PersonPsychicalSkill $personPsychicalSkill,
        PsychicalSkillPoint $skillPoint,
        IntegerInterface $requiredRankValue
    )
    {
        $this->personPsychicalSkill = $personPsychicalSkill;
        parent::__construct($personPsychicalSkill, $skillPoint, $requiredRankValue);
    }

    /**
     * @return PersonPsychicalSkill
     */
    public function getPersonSkill()
    {
        return $this->personPsychicalSkill;
    }
}
