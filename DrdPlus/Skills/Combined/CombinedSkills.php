<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\SameTypeSkills;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Tables\Tables;

/**
 * @ORM\Entity()
 */
class CombinedSkills extends SameTypeSkills
{
    const COMBINED = SkillTypeCode::COMBINED;

    /**
     * @var BigHandwork
     * @ORM\OneToOne(targetEntity="BigHandwork", cascade={"persist"}, orphanRemoval=true)
     */
    private $bigHandwork;
    /**
     * @var Cooking
     * @ORM\OneToOne(targetEntity="Cooking", cascade={"persist"}, orphanRemoval=true)
     */
    private $cooking;
    /**
     * @var Dancing
     * @ORM\OneToOne(targetEntity="Dancing", cascade={"persist"}, orphanRemoval=true)
     */
    private $dancing;
    /**
     * @var DuskSight
     * @ORM\OneToOne(targetEntity="DuskSight", cascade={"persist"}, orphanRemoval=true)
     */
    private $duskSight;
    /**
     * @var FightWithBows
     * @ORM\OneToOne(targetEntity="FightWithBows", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithBows;
    /**
     * @var FightWithCrossbows
     * @ORM\OneToOne(targetEntity="FightWithCrossbows", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithCrossbows;
    /**
     * @var FirstAid
     * @ORM\OneToOne(targetEntity="FirstAid", cascade={"persist"}, orphanRemoval=true)
     */
    private $firstAid;
    /**
     * @var HandlingWithAnimals
     * @ORM\OneToOne(targetEntity="HandlingWithAnimals", cascade={"persist"}, orphanRemoval=true)
     */
    private $handlingWithAnimals;
    /**
     * @var Handwork
     * @ORM\OneToOne(targetEntity="Handwork", cascade={"persist"}, orphanRemoval=true)
     */
    private $handwork;
    /**
     * @var Gambling
     * @ORM\OneToOne(targetEntity="Gambling", cascade={"persist"}, orphanRemoval=true)
     */
    private $gambling;
    /**
     * @var Herbalism
     * @ORM\OneToOne(targetEntity="Herbalism", cascade={"persist"}, orphanRemoval=true)
     */
    private $herbalism;
    /**
     * @var HuntingAndFishing
     * @ORM\OneToOne(targetEntity="HuntingAndFishing", cascade={"persist"}, orphanRemoval=true)
     */
    private $huntingAndFishing;
    /**
     * @var Knotting
     * @ORM\OneToOne(targetEntity="Knotting", cascade={"persist"}, orphanRemoval=true)
     */
    private $knotting;
    /**
     * @var Painting
     * @ORM\OneToOne(targetEntity="Painting", cascade={"persist"}, orphanRemoval=true)
     */
    private $painting;
    /**
     * @var Pedagogy
     * @ORM\OneToOne(targetEntity="Pedagogy", cascade={"persist"}, orphanRemoval=true)
     */
    private $pedagogy;
    /**
     * @var PlayingOnMusicInstrument
     * @ORM\OneToOne(targetEntity="PlayingOnMusicInstrument", cascade={"persist"}, orphanRemoval=true)
     */
    private $playingOnMusicInstrument;
    /**
     * @var Seduction
     * @ORM\OneToOne(targetEntity="Seduction", cascade={"persist"}, orphanRemoval=true)
     */
    private $seduction;
    /**
     * @var Showmanship
     * @ORM\OneToOne(targetEntity="Showmanship", cascade={"persist"}, orphanRemoval=true)
     */
    private $showmanship;
    /**
     * @var Singing
     * @ORM\OneToOne(targetEntity="Singing", cascade={"persist"}, orphanRemoval=true)
     */
    private $singing;
    /**
     * @var Statuary
     * @ORM\OneToOne(targetEntity="Statuary", cascade={"persist"}, orphanRemoval=true)
     */
    private $statuary;

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
     * @return \ArrayIterator|CombinedSkill[]
     */
    public function getIterator()
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
        ]);
    }

    /**
     * @return BigHandwork
     */
    public function getBigHandwork()
    {
        return $this->bigHandwork;
    }

    /**
     * @return Cooking
     */
    public function getCooking()
    {
        return $this->cooking;
    }

    /**
     * @return Dancing
     */
    public function getDancing()
    {
        return $this->dancing;
    }

    /**
     * @return DuskSight
     */
    public function getDuskSight()
    {
        return $this->duskSight;
    }

    /**
     * @return FightWithBows
     */
    public function getFightWithBows()
    {
        return $this->fightWithBows;
    }

    /**
     * @return FightWithCrossbows
     */
    public function getFightWithCrossbows()
    {
        return $this->fightWithCrossbows;
    }

    /**
     * @return FirstAid
     */
    public function getFirstAid()
    {
        return $this->firstAid;
    }

    /**
     * @return HandlingWithAnimals
     */
    public function getHandlingWithAnimals()
    {
        return $this->handlingWithAnimals;
    }

    /**
     * @return Handwork
     */
    public function getHandwork()
    {
        return $this->handwork;
    }

    /**
     * @return Gambling
     */
    public function getGambling()
    {
        return $this->gambling;
    }

    /**
     * @return Herbalism
     */
    public function getHerbalism()
    {
        return $this->herbalism;
    }

    /**
     * @return HuntingAndFishing
     */
    public function getHuntingAndFishing()
    {
        return $this->huntingAndFishing;
    }

    /**
     * @return Knotting
     */
    public function getKnotting()
    {
        return $this->knotting;
    }

    /**
     * @return Painting
     */
    public function getPainting()
    {
        return $this->painting;
    }

    /**
     * @return Pedagogy
     */
    public function getPedagogy()
    {
        return $this->pedagogy;
    }

    /**
     * @return PlayingOnMusicInstrument
     */
    public function getPlayingOnMusicInstrument()
    {
        return $this->playingOnMusicInstrument;
    }

    /**
     * @return Seduction
     */
    public function getSeduction()
    {
        return $this->seduction;
    }

    /**
     * @return Showmanship
     */
    public function getShowmanship()
    {
        return $this->showmanship;
    }

    /**
     * @return Singing
     */
    public function getSinging()
    {
        return $this->singing;
    }

    /**
     * @return Statuary
     */
    public function getStatuary()
    {
        return $this->statuary;
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @param Tables $tables
     * @return int
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     */
    public function getMalusToFightNumberWithShootingWeapon(RangedWeaponCode $rangeWeaponCode, Tables $tables)
    {
        $rankValue = $this->getFightWithShootingWeaponRankValue($rangeWeaponCode);

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getWeaponSkillTable()->getFightNumberMalusForSkillRank($rankValue);
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @return int
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     */
    private function getFightWithShootingWeaponRankValue(RangedWeaponCode $rangeWeaponCode)
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
    public function getMalusToAttackNumberWithShootingWeapon(RangedWeaponCode $rangeWeaponCode, Tables $tables)
    {
        $rankValue = $this->getFightWithShootingWeaponRankValue($rangeWeaponCode);

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getWeaponSkillTable()->getAttackNumberMalusForSkillRank($rankValue);
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @param Tables $tables
     * @return int
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     */
    public function getMalusToCoverWithShootingWeapon(RangedWeaponCode $rangeWeaponCode, Tables $tables)
    {
        $rankValue = $this->getFightWithShootingWeaponRankValue($rangeWeaponCode);

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getWeaponSkillTable()->getCoverMalusForSkillRank($rankValue);
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @param Tables $tables
     * @return int
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     */
    public function getMalusToBaseOfWoundsWithShootingWeapon(RangedWeaponCode $rangeWeaponCode, Tables $tables)
    {
        $rankValue = $this->getFightWithShootingWeaponRankValue($rangeWeaponCode);

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getWeaponSkillTable()->getBaseOfWoundsMalusForSkillRank($rankValue);
    }
}