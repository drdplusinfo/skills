<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\Common\Collections\ArrayCollection;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\PositiveIntegerObject;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="skillName", type="string")
 * @ORM\DiscriminatorMap({
 *  "armorWearing" = "ArmorWearing",
 *  "athletics" = "Athletics",
 *  "blacksmithing" = "Blacksmithing",
 *  "boatDriving" = "BoatDriving",
 *  "cartDriving" = "CartDriving",
 *  "cityMoving" = "CityMoving",
 *  "climbingAndHillwalking" = "ClimbingAndHillwalking",
 *  "fastMarsh" = "FastMarsh",
 *  "fightUnarmed" = "FightUnarmed",
 *  "fightWithAxes" = "FightWithAxes",
 *  "fightWithKnifesAndDaggers" = "FightWithKnifesAndDaggers",
 *  "fightWithMacesAndClubs" = "FightWithMacesAndClubs",
 *  "fightWithMorningStarsAndMorgensterns" = "FightWithMorningStarsAndMorgensterns",
 *  "fightWithSabersAndBowieKnifes" = "FightWithSabersAndBowieKnifes",
 *  "fightWithStaffsAndSpears" = "FightWithStaffsAndSpears",
 *  "fightWithSwords" = "FightWithSwords",
 *  "fightWithThrowingWeapons" = "FightWithThrowingWeapons",
 *  "fightWithTwoWeapons" = "FightWithTwoWeapons",
 *  "fightWithVoulgesAndTridents" = "FightWithVoulgesAndTridents",
 *  "flying" = "Flying",
 *  "forestMoving" = "ForestMoving",
 *  "movingInMountains" = "MovingInMountains",
 *  "riding" = "Riding",
 *  "sailing" = "Sailing",
 *  "shieldUsage" = "ShieldUsage",
 *  "swimming" = "Swimming",
 * })
 *
 * @method PhysicalSkillRank getCurrentSkillRank
 */
abstract class PersonPhysicalSkill extends PersonSkill
{

    /**
     * @var PhysicalSkillRank[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="PhysicalSkillRank", mappedBy="personPhysicalSkill", cascade={"persist"})
     */
    private $physicalSkillRanks;

    /**
     * @param ProfessionLevel $professionLevel
     */
    public function __construct(ProfessionLevel $professionLevel)
    {
        $this->physicalSkillRanks = new ArrayCollection();
        parent::__construct($professionLevel);
    }

    /**
     * @param ProfessionLevel $professionLevel
     * @return PhysicalSkillRank
     * @throws \DrdPlus\Person\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyPaidPersonSkillPoint
     */
    protected function createZeroSkillRank(ProfessionLevel $professionLevel)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new PhysicalSkillRank(
            $this,
            PhysicalSkillPoint::createZeroSkillPoint($professionLevel),
            new PositiveIntegerObject(0)
        );
    }

    /**
     * @param PhysicalSkillRank $physicalSkillRank
     * @throws \DrdPlus\Person\Skills\Exceptions\UnexpectedRankValue
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     */
    public function addSkillRank(PhysicalSkillRank $physicalSkillRank)
    {
        parent::addTypeVerifiedSkillRank($physicalSkillRank);
    }

    /**
     * @return ArrayCollection|PhysicalSkillRank[]
     */
    protected function getInnerSkillRanks()
    {
        return $this->physicalSkillRanks;
    }

    /**
     * @return string[]
     */
    public function getRelatedPropertyCodes()
    {
        return [Strength::STRENGTH, Agility::AGILITY];
    }

    /**
     * @return bool
     */
    public function isPhysical()
    {
        return true;
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
        return false;
    }

}