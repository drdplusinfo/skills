<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\SameTypeSkills;
use DrdPlus\Tables\Tables;

/**
 * @Doctrine\ORM\Mapping\Entity()
 */
class CombinedSkills extends SameTypeSkills
{
    public const COMBINED = SkillTypeCode::COMBINED;

    /**
     * @var BigHandwork
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="BigHandwork", cascade={"persist"}, orphanRemoval=true)
     */
    private $bigHandwork;
    /**
     * @var Cooking
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Cooking", cascade={"persist"}, orphanRemoval=true)
     */
    private $cooking;
    /**
     * @var Dancing
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Dancing", cascade={"persist"}, orphanRemoval=true)
     */
    private $dancing;
    /**
     * @var DuskSight
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="DuskSight", cascade={"persist"}, orphanRemoval=true)
     */
    private $duskSight;
    /**
     * @var FightWithBows
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="FightWithBows", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithBows;
    /**
     * @var FightWithCrossbows
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="FightWithCrossbows", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithCrossbows;
    /**
     * @var FirstAid
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="FirstAid", cascade={"persist"}, orphanRemoval=true)
     */
    private $firstAid;
    /**
     * @var HandlingWithAnimals
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="HandlingWithAnimals", cascade={"persist"}, orphanRemoval=true)
     */
    private $handlingWithAnimals;
    /**
     * @var Handwork
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Handwork", cascade={"persist"}, orphanRemoval=true)
     */
    private $handwork;
    /**
     * @var Gambling
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Gambling", cascade={"persist"}, orphanRemoval=true)
     */
    private $gambling;
    /**
     * @var Herbalism
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Herbalism", cascade={"persist"}, orphanRemoval=true)
     */
    private $herbalism;
    /**
     * @var HuntingAndFishing
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="HuntingAndFishing", cascade={"persist"}, orphanRemoval=true)
     */
    private $huntingAndFishing;
    /**
     * @var Knotting
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Knotting", cascade={"persist"}, orphanRemoval=true)
     */
    private $knotting;
    /**
     * @var Painting
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Painting", cascade={"persist"}, orphanRemoval=true)
     */
    private $painting;
    /**
     * @var Pedagogy
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Pedagogy", cascade={"persist"}, orphanRemoval=true)
     */
    private $pedagogy;
    /**
     * @var PlayingOnMusicInstrument
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="PlayingOnMusicInstrument", cascade={"persist"}, orphanRemoval=true)
     */
    private $playingOnMusicInstrument;
    /**
     * @var Seduction
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Seduction", cascade={"persist"}, orphanRemoval=true)
     */
    private $seduction;
    /**
     * @var Showmanship
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Showmanship", cascade={"persist"}, orphanRemoval=true)
     */
    private $showmanship;
    /**
     * @var Singing
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Singing", cascade={"persist"}, orphanRemoval=true)
     */
    private $singing;
    /**
     * @var Statuary
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Statuary", cascade={"persist"}, orphanRemoval=true)
     */
    private $statuary;
    /**
     * @var Teaching
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Teaching", cascade={"persist"}, orphanRemoval=true)
     */
    private $teaching;

    /**
     * @param ProfessionLevel $professionLevel
     */
    protected function populateAllSkills(ProfessionLevel $professionLevel)
    {
        $this->bigHandwork = new BigHandwork($professionLevel);
        $this->cooking = new Cooking($professionLevel);
        $this->dancing = new Dancing($professionLevel);
        $this->duskSight = new DuskSight($professionLevel);
        $this->fightWithBows = new FightWithBows($professionLevel);
        $this->fightWithCrossbows = new FightWithCrossbows($professionLevel);
        $this->firstAid = new FirstAid($professionLevel);
        $this->gambling = new Gambling($professionLevel);
        $this->handlingWithAnimals = new HandlingWithAnimals($professionLevel);
        $this->handwork = new Handwork($professionLevel);
        $this->herbalism = new Herbalism($professionLevel);
        $this->huntingAndFishing = new HuntingAndFishing($professionLevel);
        $this->knotting = new Knotting($professionLevel);
        $this->painting = new Painting($professionLevel);
        $this->pedagogy = new Pedagogy($professionLevel);
        $this->playingOnMusicInstrument = new PlayingOnMusicInstrument($professionLevel);
        $this->seduction = new Seduction($professionLevel);
        $this->showmanship = new Showmanship($professionLevel);
        $this->singing = new Singing($professionLevel);
        $this->statuary = new Statuary($professionLevel);
        $this->teaching = new Teaching($professionLevel);
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getUnusedFirstLevelCombinedSkillPointsValue(ProfessionLevels $professionLevels)
    {
        return $this->getUnusedFirstLevelSkillPointsValue($this->getFirstLevelCombinedPropertiesSum($professionLevels));
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    private function getFirstLevelCombinedPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getFirstLevelKnackModifier() + $professionLevels->getFirstLevelCharismaModifier();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getUnusedNextLevelsCombinedSkillPointsValue(ProfessionLevels $professionLevels)
    {
        return $this->getUnusedNextLevelsSkillPointsValue($this->getNextLevelsCombinedPropertiesSum($professionLevels));
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    private function getNextLevelsCombinedPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getNextLevelsKnackModifier() + $professionLevels->getNextLevelsCharismaModifier();
    }

    /**
     * @return \Traversable|\ArrayIterator|CombinedSkill[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator([
            $this->getBigHandwork(),
            $this->getCooking(),
            $this->getDancing(),
            $this->getDuskSight(),
            $this->getFightWithBows(),
            $this->getFightWithCrossbows(),
            $this->getFirstAid(),
            $this->getGambling(),
            $this->getHandlingWithAnimals(),
            $this->getHandwork(),
            $this->getHerbalism(),
            $this->getHuntingAndFishing(),
            $this->getKnotting(),
            $this->getPainting(),
            $this->getPedagogy(),
            $this->getPlayingOnMusicInstrument(),
            $this->getSeduction(),
            $this->getShowmanship(),
            $this->getSinging(),
            $this->getStatuary(),
            $this->getTeaching(),
        ]);
    }

    /**
     * @return BigHandwork
     */
    public function getBigHandwork(): BigHandwork
    {
        return $this->bigHandwork;
    }

    /**
     * @return Cooking
     */
    public function getCooking(): Cooking
    {
        return $this->cooking;
    }

    /**
     * @return Dancing
     */
    public function getDancing(): Dancing
    {
        return $this->dancing;
    }

    /**
     * @return DuskSight
     */
    public function getDuskSight(): DuskSight
    {
        return $this->duskSight;
    }

    /**
     * @return FightWithBows
     */
    public function getFightWithBows(): FightWithBows
    {
        return $this->fightWithBows;
    }

    /**
     * @return FightWithCrossbows
     */
    public function getFightWithCrossbows(): FightWithCrossbows
    {
        return $this->fightWithCrossbows;
    }

    /**
     * @return FirstAid
     */
    public function getFirstAid(): FirstAid
    {
        return $this->firstAid;
    }

    /**
     * @return HandlingWithAnimals
     */
    public function getHandlingWithAnimals(): HandlingWithAnimals
    {
        return $this->handlingWithAnimals;
    }

    /**
     * @return Handwork
     */
    public function getHandwork(): Handwork
    {
        return $this->handwork;
    }

    /**
     * @return Gambling
     */
    public function getGambling(): Gambling
    {
        return $this->gambling;
    }

    /**
     * @return Herbalism
     */
    public function getHerbalism(): Herbalism
    {
        return $this->herbalism;
    }

    /**
     * @return HuntingAndFishing
     */
    public function getHuntingAndFishing(): HuntingAndFishing
    {
        return $this->huntingAndFishing;
    }

    /**
     * @return Knotting
     */
    public function getKnotting(): Knotting
    {
        return $this->knotting;
    }

    /**
     * @return Painting
     */
    public function getPainting(): Painting
    {
        return $this->painting;
    }

    /**
     * @return Pedagogy
     */
    public function getPedagogy(): Pedagogy
    {
        return $this->pedagogy;
    }

    /**
     * @return PlayingOnMusicInstrument
     */
    public function getPlayingOnMusicInstrument(): PlayingOnMusicInstrument
    {
        return $this->playingOnMusicInstrument;
    }

    /**
     * @return Seduction
     */
    public function getSeduction(): Seduction
    {
        return $this->seduction;
    }

    /**
     * @return Showmanship
     */
    public function getShowmanship(): Showmanship
    {
        return $this->showmanship;
    }

    /**
     * @return Singing
     */
    public function getSinging(): Singing
    {
        return $this->singing;
    }

    /**
     * @return Statuary
     */
    public function getStatuary(): Statuary
    {
        return $this->statuary;
    }

    /**
     * @return Teaching
     */
    public function getTeaching(): Teaching
    {
        return $this->teaching;
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @param Tables $tables
     * @return int
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     */
    public function getMalusToFightNumberWithShootingWeapon(RangedWeaponCode $rangeWeaponCode, Tables $tables): int
    {
        $rankValue = $this->getFightWithShootingWeaponRankValue($rangeWeaponCode);

        return $tables->getMissingWeaponSkillTable()->getFightNumberMalusForSkillRank($rankValue);
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @return int
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     */
    private function getFightWithShootingWeaponRankValue(RangedWeaponCode $rangeWeaponCode): int
    {
        if ($rangeWeaponCode->isBow()) {
            return $this->getFightWithBows()->getCurrentSkillRank()->getValue();
        }
        if ($rangeWeaponCode->isCrossbow()) {
            return $this->getFightWithCrossbows()->getCurrentSkillRank()->getValue();
        }
        throw new Exceptions\CombinedSkillsDoNotHowToUseThatWeapon(
            "Given range weapon {$rangeWeaponCode} is not affected by combined skills"
            . ' (only shooting weapons using knack are)'
        );
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @param Tables $tables
     * @return int
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     */
    public function getMalusToAttackNumberWithShootingWeapon(RangedWeaponCode $rangeWeaponCode, Tables $tables): int
    {
        $rankValue = $this->getFightWithShootingWeaponRankValue($rangeWeaponCode);

        return $tables->getMissingWeaponSkillTable()->getAttackNumberMalusForSkillRank($rankValue);
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @param Tables $tables
     * @return int
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     */
    public function getMalusToCoverWithShootingWeapon(RangedWeaponCode $rangeWeaponCode, Tables $tables): int
    {
        $rankValue = $this->getFightWithShootingWeaponRankValue($rangeWeaponCode);

        return $tables->getMissingWeaponSkillTable()->getCoverMalusForSkillRank($rankValue);
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @param Tables $tables
     * @return int
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     */
    public function getMalusToBaseOfWoundsWithShootingWeapon(RangedWeaponCode $rangeWeaponCode, Tables $tables): int
    {
        $rankValue = $this->getFightWithShootingWeaponRankValue($rangeWeaponCode);

        return $tables->getMissingWeaponSkillTable()->getBaseOfWoundsMalusForSkillRank($rankValue);
    }
}