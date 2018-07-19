<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Skills\Skill;
use DrdPlus\Skills\SkillRank;
use Granam\Integer\PositiveIntegerObject;

/**
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\InheritanceType("SINGLE_TABLE")
 * @Doctrine\ORM\Mapping\DiscriminatorColumn(name="skillName", type="string")
 * @Doctrine\ORM\Mapping\DiscriminatorMap({
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
 *  "fightWithKnivesAndDaggers" = "FightWithKnivesAndDaggers",
 *  "fightWithMacesAndClubs" = "FightWithMacesAndClubs",
 *  "fightWithMorningStarsAndMorgensterns" = "FightWithMorningStarsAndMorgensterns",
 *  "fightWithSabersAndBowieKnives" = "FightWithSabersAndBowieKnives",
 *  "fightWithStaffsAndSpears" = "FightWithStaffsAndSpears",
 *  "fightWithShields" = "FightWithShields",
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
 * @method PhysicalSkillRank|SkillRank getCurrentSkillRank(): SkillRank
 */
abstract class PhysicalSkill extends Skill
{

    /**
     * @var PhysicalSkillRank[]|ArrayCollection
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="PhysicalSkillRank", mappedBy="physicalSkill", cascade={"persist"})
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
     * @return PhysicalSkillRank|SkillRank
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     */
    protected function createZeroSkillRank(ProfessionLevel $professionLevel): SkillRank
    {
        return new PhysicalSkillRank(
            $this,
            PhysicalSkillPoint::createZeroSkillPoint($professionLevel),
            new PositiveIntegerObject(0)
        );
    }

    /**
     * @param PhysicalSkillPoint $physicalSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedRankValue
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     */
    public function increaseSkillRank(PhysicalSkillPoint $physicalSkillPoint): void
    {
        parent::addTypeVerifiedSkillRank(
            new PhysicalSkillRank(
                $this,
                $physicalSkillPoint,
                new PositiveIntegerObject($this->getCurrentSkillRank()->getValue() + 1)
            )
        );
    }

    /**
     * @return Collection|ArrayCollection|PhysicalSkillRank[]
     */
    protected function getInnerSkillRanks(): Collection
    {
        return $this->physicalSkillRanks;
    }

    /**
     * @return string[]
     */
    public function getRelatedPropertyCodes(): array
    {
        return [PropertyCode::STRENGTH, PropertyCode::AGILITY];
    }

    /**
     * @return bool
     */
    public function isPhysical(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isPsychical(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isCombined(): bool
    {
        return false;
    }

}