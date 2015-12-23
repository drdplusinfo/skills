<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use DrdPlus\Person\Skills\PersonSameTypeSkills;

/**
 * CombinedSkills
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class PersonCombinedSkills extends PersonSameTypeSkills
{
    const COMBINED = SkillCodes::COMBINED;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var BigHandwork|null
     * @ORM\OneToOne(targetEntity="BigHandwork")
     */
    private $bigHandwork;
    /** @var Cooking|null
     * @ORM\OneToOne(targetEntity="Cooking")
     */
    private $cooking;
    /** @var Dancing|null
     * @ORM\OneToOne(targetEntity="Dancing")
     */
    private $dancing;
    /** @var DuskSight|null
     * @ORM\OneToOne(targetEntity="DuskSight")
     */
    private $duskSight;
    /** @var FightWithShootingWeapons|null
     * @ORM\OneToOne(targetEntity="FightWithShootingWeapons")
     */
    private $fightWithShootingWeapons;
    /** @var FirstAid|null
     * @ORM\OneToOne(targetEntity="FirstAid")
     */
    private $firstAid;
    /** @var HandingWithAnimals|null
     * @ORM\OneToOne(targetEntity="HandingWithAnimals")
     */
    private $handingWithAnimals;
    /** @var Handwork|null
     * @ORM\OneToOne(targetEntity="Handwork")
     */
    private $handwork;
    /** @var Gambling|null
     * @ORM\OneToOne(targetEntity="Gambling")
     */
    private $gambling;
    /** @var Herbalism|null
     * @ORM\OneToOne(targetEntity="Herbalism")
     */
    private $herbalism;
    /** @var HuntingAndFishing|null
     * @ORM\OneToOne(targetEntity="HuntingAndFishing")
     */
    private $huntingAndFishing;
    /** @var Knotting|null
     * @ORM\OneToOne(targetEntity="Knotting")
     */
    private $knotting;
    /** @var Painting|null
     * @ORM\OneToOne(targetEntity="Painting")
     */
    private $painting;
    /** @var Pedagogy|null
     * @ORM\OneToOne(targetEntity="Pedagogy")
     */
    private $pedagogy;
    /** @var PlayingOnMusicInstrument|null
     * @ORM\OneToOne(targetEntity="PlayingOnMusicInstrument")
     */
    private $playingOnMusicInstrument;
    /** @var Seduction|null
     * @ORM\OneToOne(targetEntity="Seduction")
     */
    private $seduction;
    /** @var Showmanship|null
     * @ORM\OneToOne(targetEntity="Showmanship")
     */
    private $showmanship;
    /** @var Singing|null
     * @ORM\OneToOne(targetEntity="Singing")
     */
    private $singing;
    /** @var Statuary|null
     * @ORM\OneToOne(targetEntity="Statuary")
     */
    private $statuary;

    protected function createSkillsIterator()
    {
        return new \ArrayIterator(
            array_filter([
                $this->getBigHandwork(),
                $this->getCooking(),
                $this->getDancing(),
                $this->getDuskSight(),
                $this->getFightWithShootingWeapons(),
                $this->getFirstAid(),
                $this->getGambling(),
                $this->getHandingWithAnimals(),
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
                $this->getStatuary()
            ])
        );
    }

    /**
     * @return string
     */
    public function getSkillsGroupName()
    {
        return self::COMBINED;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    public function addCombinedSkill(PersonCombinedSkill $combinedSkill)
    {
        switch (true) {
            case is_a($combinedSkill, BigHandwork::class) :
                $this->bigHandwork = $combinedSkill;
                break;
            case is_a($combinedSkill, Cooking::class) :
                $this->cooking = $combinedSkill;
                break;
            case is_a($combinedSkill, Dancing::class) :
                $this->dancing = $combinedSkill;
                break;
            case is_a($combinedSkill, DuskSight::class) :
                $this->duskSight = $combinedSkill;
                break;
            case is_a($combinedSkill, FightWithShootingWeapons::class) :
                $this->fightWithShootingWeapons = $combinedSkill;
                break;
            case is_a($combinedSkill, FirstAid::class) :
                $this->firstAid = $combinedSkill;
                break;
            case is_a($combinedSkill, HandingWithAnimals::class) :
                $this->handingWithAnimals = $combinedSkill;
                break;
            case is_a($combinedSkill, Handwork::class) :
                $this->handwork = $combinedSkill;
                break;
            case is_a($combinedSkill, Gambling::class) :
                $this->gambling = $combinedSkill;
                break;
            case is_a($combinedSkill, Herbalism::class) :
                $this->herbalism = $combinedSkill;
                break;
            case is_a($combinedSkill, HuntingAndFishing::class) :
                $this->huntingAndFishing = $combinedSkill;
                break;
            case is_a($combinedSkill, Knotting::class) :
                $this->knotting = $combinedSkill;
                break;
            case is_a($combinedSkill, Painting::class) :
                $this->painting = $combinedSkill;
                break;
            case is_a($combinedSkill, Pedagogy::class) :
                $this->pedagogy = $combinedSkill;
                break;
            case is_a($combinedSkill, PlayingOnMusicInstrument::class) :
                $this->playingOnMusicInstrument = $combinedSkill;
                break;
            case is_a($combinedSkill, Seduction::class) :
                $this->seduction = $combinedSkill;
                break;
            case is_a($combinedSkill, Showmanship::class) :
                $this->showmanship = $combinedSkill;
                break;
            case is_a($combinedSkill, Singing::class) :
                $this->singing = $combinedSkill;
                break;
            case is_a($combinedSkill, Statuary::class) :
                $this->statuary = $combinedSkill;
                break;
            default :
                throw new Exceptions\UnknownCombinedSkill(
                    'Unknown combined skill ' . get_class($combinedSkill)
                );
        }
    }

    /**
     * @return BigHandwork|null
     */
    public function getBigHandwork()
    {
        return $this->bigHandwork;
    }

    /**
     * @return Cooking|null
     */
    public function getCooking()
    {
        return $this->cooking;
    }

    /**
     * @return Dancing|null
     */
    public function getDancing()
    {
        return $this->dancing;
    }

    /**
     * @return DuskSight|null
     */
    public function getDuskSight()
    {
        return $this->duskSight;
    }

    /**
     * @return FightWithShootingWeapons|null
     */
    public function getFightWithShootingWeapons()
    {
        return $this->fightWithShootingWeapons;
    }

    /**
     * @return FirstAid|null
     */
    public function getFirstAid()
    {
        return $this->firstAid;
    }

    /**
     * @return HandingWithAnimals|null
     */
    public function getHandingWithAnimals()
    {
        return $this->handingWithAnimals;
    }

    /**
     * @return Handwork|null
     */
    public function getHandwork()
    {
        return $this->handwork;
    }

    /**
     * @return Gambling|null
     */
    public function getGambling()
    {
        return $this->gambling;
    }

    /**
     * @return Herbalism|null
     */
    public function getHerbalism()
    {
        return $this->herbalism;
    }

    /**
     * @return HuntingAndFishing|null
     */
    public function getHuntingAndFishing()
    {
        return $this->huntingAndFishing;
    }

    /**
     * @return Knotting|null
     */
    public function getKnotting()
    {
        return $this->knotting;
    }

    /**
     * @return Painting|null
     */
    public function getPainting()
    {
        return $this->painting;
    }

    /**
     * @return Pedagogy|null
     */
    public function getPedagogy()
    {
        return $this->pedagogy;
    }

    /**
     * @return PlayingOnMusicInstrument|null
     */
    public function getPlayingOnMusicInstrument()
    {
        return $this->playingOnMusicInstrument;
    }

    /**
     * @return Seduction|null
     */
    public function getSeduction()
    {
        return $this->seduction;
    }

    /**
     * @return Showmanship|null
     */
    public function getShowmanship()
    {
        return $this->showmanship;
    }

    /**
     * @return Singing|null
     */
    public function getSinging()
    {
        return $this->singing;
    }

    /**
     * @return Statuary|null
     */
    public function getStatuary()
    {
        return $this->statuary;
    }

}
