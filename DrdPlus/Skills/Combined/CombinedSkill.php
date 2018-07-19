<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

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
 * @method CombinedSkillRank|SkillRank getCurrentSkillRank(): SkillRank
 */
abstract class CombinedSkill extends Skill
{

    /**
     * @var CombinedSkillRank[]|ArrayCollection
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="CombinedSkillRank", mappedBy="combinedSkill", cascade={"persist"})
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
    public function increaseSkillRank(CombinedSkillPoint $combinedSkillPoint): void
    {
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