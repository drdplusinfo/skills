<?php
namespace DrdPlus\Person\Skills\Combined;

use Doctrine\Common\Collections\ArrayCollection;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Knack;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="skillName", type="string")
 * @ORM\DiscriminatorMap({
 * "bigHandwork" = "BigHandwork",
 * "cooking" = "Cooking",
 * "dancing" = "Dancing",
 * "duskSight" = "DuskSight",
 * "fightWithBows" = "FightWithBows",
 * "fightWithCrossbows" = "FightWithCrossbows",
 * "firstAid" = "FirstAid",
 * "gambling" = "Gambling",
 * "handingWithAnimals" = "HandlingWithAnimals",
 * "handwork" = "Handwork",
 * "herbalism" = "Herbalism",
 * "huntingAndFishing" = "HuntingAndFishing",
 * "knotting" = "Knotting",
 * "painting" = "Painting",
 * "pedagogy" = "Pedagogy",
 * "flayingOnMusicInstrument" = "PlayingOnMusicInstrument",
 * "seduction" = "Seduction",
 * "showmanship" = "Showmanship",
 * "singing" = "Singing",
 * "statuary" = "Statuary"
 * })
 */
abstract class PersonCombinedSkill extends PersonSkill
{

    /**
     * @var CombinedSkillRank[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="CombinedSkillRank", mappedBy="personCombinedSkill", cascade={"persist"})
     */
    private $combinedSkillRanks;

    public function __construct()
    {
        $this->combinedSkillRanks = new ArrayCollection();
    }

    /**
     * @param CombinedSkillRank $combinedSkillRank
     * @throws \DrdPlus\Person\Skills\Combined\Exceptions\CombinedSkillRankExpected
     */
    public function addSkillRank(CombinedSkillRank $combinedSkillRank)
    {
        parent::addTypeVerifiedSkillRank($combinedSkillRank);
    }

    /**
     * @return ArrayCollection|CombinedSkillRank[]
     */
    public function getSkillRanks()
    {
        return $this->combinedSkillRanks;
    }

    /**
     * @return string[]
     */
    public function getRelatedPropertyCodes()
    {
        return [Knack::KNACK, Charisma::CHARISMA];
    }

    /**
     * @return bool
     */
    public function isPhysical()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isPsychical()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isCombined()
    {
        return true;
    }

}
