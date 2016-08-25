<?php
namespace DrdPlus\Person\Skills\Combined;

use Doctrine\Common\Collections\ArrayCollection;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Knack;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\PositiveIntegerObject;

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
 * @method CombinedSkillRank getCurrentSkillRank
 */
abstract class PersonCombinedSkill extends PersonSkill
{

    /**
     * @var CombinedSkillRank[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="CombinedSkillRank", mappedBy="personCombinedSkill", cascade={"persist"})
     */
    private $combinedSkillRanks;

    /**
     * @param ProfessionLevel $professionLevel
     */
    public function __construct(ProfessionLevel $professionLevel)
    {
        $this->combinedSkillRanks = new ArrayCollection();
        parent::__construct($professionLevel);
    }

    /**
     * @param CombinedSkillRank $combinedSkillRank
     * @throws \DrdPlus\Person\Skills\Exceptions\UnexpectedRankValue
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     */
    public function addSkillRank(CombinedSkillRank $combinedSkillRank)
    {
        parent::addTypeVerifiedSkillRank($combinedSkillRank);
    }

    /**
     * @param ProfessionLevel $professionLevel
     * @return CombinedSkillRank
     * @throws \DrdPlus\Person\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyPaidPersonSkillPoint
     */
    protected function createZeroSkillRank(ProfessionLevel $professionLevel)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new CombinedSkillRank(
            $this,
            CombinedSkillPoint::createZeroSkillPoint($professionLevel),
            new PositiveIntegerObject(0)
        );
    }

    /**
     * @return ArrayCollection|CombinedSkillRank[]
     */
    protected function getInnerSkillRanks()
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