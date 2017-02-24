<?php
namespace DrdPlus\Skills\Combined;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Skills\Skill;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\SkillRank;
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
 * "playingOnMusicInstrument" = "PlayingOnMusicInstrument",
 * "seduction" = "Seduction",
 * "showmanship" = "Showmanship",
 * "singing" = "Singing",
 * "statuary" = "Statuary",
 * "teaching" = "Teaching"
 * })
 * @method CombinedSkillRank|SkillRank getCurrentSkillRank
 */
abstract class CombinedSkill extends Skill
{

    /**
     * @var CombinedSkillRank[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="CombinedSkillRank", mappedBy="combinedSkill", cascade={"persist"})
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
     * @param CombinedSkillPoint $combinedSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedRankValue
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     */
    public function increaseSkillRank(CombinedSkillPoint $combinedSkillPoint)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::addTypeVerifiedSkillRank(
            new CombinedSkillRank(
                $this,
                $combinedSkillPoint,
                new PositiveIntegerObject($this->getCurrentSkillRank()->getValue() + 1)
            )
        );
    }

    /**
     * @param ProfessionLevel $professionLevel
     * @return SkillRank|CombinedSkillRank
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     */
    protected function createZeroSkillRank(ProfessionLevel $professionLevel): SkillRank
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new CombinedSkillRank(
            $this,
            CombinedSkillPoint::createZeroSkillPoint($professionLevel),
            new PositiveIntegerObject(0)
        );
    }

    /**
     * @return Collection|ArrayCollection|CombinedSkillRank[]
     */
    protected function getInnerSkillRanks(): Collection
    {
        return $this->combinedSkillRanks;
    }

    /**
     * @return string[]
     */
    public function getRelatedPropertyCodes(): array
    {
        return [PropertyCode::KNACK, PropertyCode::CHARISMA];
    }

    /**
     * @return bool
     */
    public function isPhysical(): bool
    {
        return false;
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
        return true;
    }

}