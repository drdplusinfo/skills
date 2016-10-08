<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Armaments\ArmorCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\ProtectiveArmamentCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\SameTypeSkills;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\SkillRank;
use DrdPlus\Tables\Armaments\Armourer;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use Granam\Integer\PositiveInteger;
use Granam\Integer\PositiveIntegerObject;

/**
 * @ORM\Entity()
 */
class PhysicalSkills extends SameTypeSkills
{
    const PHYSICAL = SkillTypeCode::PHYSICAL;

    /**
     * @var ArmorWearing|null
     * @ORM\OneToOne(targetEntity="ArmorWearing")
     */
    private $armorWearing;
    /**
     * @var Athletics|null
     * @ORM\OneToOne(targetEntity="Athletics")
     */
    private $athletics;
    /**
     * @var Blacksmithing|null
     * @ORM\OneToOne(targetEntity="Blacksmithing")
     */
    private $blacksmithing;
    /**
     * @var BoatDriving|null
     * @ORM\OneToOne(targetEntity="BoatDriving")
     */
    private $boatDriving;
    /**
     * @var CartDriving|null
     * @ORM\OneToOne(targetEntity="CartDriving")
     */
    private $cartDriving;
    /**
     * @var CityMoving|null
     * @ORM\OneToOne(targetEntity="CityMoving")
     */
    private $cityMoving;
    /**
     * @var ClimbingAndHillwalking|null
     * @ORM\OneToOne(targetEntity="ClimbingAndHillwalking")
     */
    private $climbingAndHillwalking;
    /**
     * @var FastMarsh|null
     * @ORM\OneToOne(targetEntity="FastMarsh")
     */
    private $fastMarsh;
    /**
     * @var FightUnarmed|null
     * @ORM\OneToOne(targetEntity="FightUnarmed")
     */
    private $fightUnarmed;
    /**
     * @var FightWithAxes|null
     * @ORM\OneToOne(targetEntity="FightWithAxes")
     */
    private $fightWithAxes;
    /**
     * @var FightWithKnifesAndDaggers|null
     * @ORM\OneToOne(targetEntity="FightWithKnifesAndDaggers")
     */
    private $fightWithKnifesAndDaggers;
    /**
     * @var FightWithMacesAndClubs|null
     * @ORM\OneToOne(targetEntity="FightWithMacesAndClubs")
     */
    private $fightWithMacesAndClubs;
    /**
     * @var FightWithMorningstarsAndMorgensterns|null
     * @ORM\OneToOne(targetEntity="FightWithMorningStarsAndMorgensterns")
     */
    private $fightWithMorningStarsAndMorgensterns;
    /**
     * @var FightWithSabersAndBowieKnifes|null
     * @ORM\OneToOne(targetEntity="FightWithSabersAndBowieKnifes")
     */
    private $fightWithSabersAndBowieKnifes;
    /**
     * @var FightWithStaffsAndSpears|null
     * @ORM\OneToOne(targetEntity="FightWithStaffsAndSpears")
     */
    private $fightWithStaffsAndSpears;
    /**
     * @var FightWithShields|null
     * @ORM\OneToOne(targetEntity="FightWithShields")
     */
    private $fightWithShields;
    /**
     * @var FightWithSwords|null
     * @ORM\OneToOne(targetEntity="FightWithSwords")
     */
    private $fightWithSwords;
    /**
     * @var FightWithThrowingWeapons|null
     * @ORM\OneToOne(targetEntity="FightWithThrowingWeapons")
     */
    private $fightWithThrowingWeapons;
    /**
     * @var FightWithTwoWeapons|null
     * @ORM\OneToOne(targetEntity="FightWithTwoWeapons")
     */
    private $fightWithTwoWeapons;
    /**
     * @var FightWithVoulgesAndTridents|null
     * @ORM\OneToOne(targetEntity="FightWithVoulgesAndTridents")
     */
    private $fightWithVoulgesAndTridents;
    /**
     * @var Flying|null
     * @ORM\OneToOne(targetEntity="Flying")
     */
    private $flying;
    /**
     * @var ForestMoving|null
     * @ORM\OneToOne(targetEntity="ForestMoving")
     */
    private $forestMoving;
    /**
     * @var MovingInMountains|null
     * @ORM\OneToOne(targetEntity="MovingInMountains")
     */
    private $movingInMountains;
    /**
     * @var Riding|null
     * @ORM\OneToOne(targetEntity="Riding")
     */
    private $riding;
    /**
     * @var Sailing|null
     * @ORM\OneToOne(targetEntity="Sailing")
     */
    private $sailing;
    /**
     * @var ShieldUsage|null
     * @ORM\OneToOne(targetEntity="ShieldUsage")
     */
    private $shieldUsage;
    /**
     * @var Swimming|null
     * @ORM\OneToOne(targetEntity="Swimming")
     */
    private $swimming;

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getUnusedFirstLevelPhysicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        return $this->getUnusedFirstLevelSkillPointsValue($this->getFirstLevelPhysicalPropertiesSum($professionLevels));
    }

    private function getFirstLevelPhysicalPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getFirstLevelStrengthModifier() + $professionLevels->getFirstLevelAgilityModifier();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getUnusedNextLevelsPhysicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        return $this->getUnusedNextLevelsSkillPointsValue($this->getNextLevelsPhysicalPropertiesSum($professionLevels));
    }

    private function getNextLevelsPhysicalPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getNextLevelsStrengthModifier() + $professionLevels->getNextLevelsAgilityModifier();
    }

    /**
     * @return \ArrayIterator|PhysicalSkill[]
     */
    public function getIterator()
    {
        return new \ArrayIterator(
            array_values( // rebuild indexes sequence
                array_filter([ // remove null
                    $this->getArmorWearing(),
                    $this->getAthletics(),
                    $this->getBlacksmithing(),
                    $this->getBoatDriving(),
                    $this->getCartDriving(),
                    $this->getCityMoving(),
                    $this->getClimbingAndHillwalking(),
                    $this->getFastMarsh(),
                    $this->getFightUnarmed(),
                    $this->getFightWithAxes(),
                    $this->getFightWithKnifesAndDaggers(),
                    $this->getFightWithMacesAndClubs(),
                    $this->getFightWithMorningStarsAndMorgensterns(),
                    $this->getFightWithSabersAndBowieKnifes(),
                    $this->getFightWithStaffsAndSpears(),
                    $this->getFightWithShields(),
                    $this->getFightWithSwords(),
                    $this->getFightWithThrowingWeapons(),
                    $this->getFightWithTwoWeapons(),
                    $this->getFightWithVoulgesAndTridents(),
                    $this->getFlying(),
                    $this->getForestMoving(),
                    $this->getMovingInMountains(),
                    $this->getRiding(),
                    $this->getSailing(),
                    $this->getShieldUsage(),
                    $this->getSwimming(),
                ])
            )
        );
    }

    /**
     * @param PhysicalSkill $physicalSkill
     * @throws Exceptions\PhysicalSkillAlreadySet
     * @throws Exceptions\UnknownPhysicalSkill
     */
    public function addPhysicalSkill(PhysicalSkill $physicalSkill)
    {
        switch (true) {
            case is_a($physicalSkill, ArmorWearing::class) :
                if ($this->armorWearing !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('armorWearing is already set');
                }
                $this->armorWearing = $physicalSkill;
                break;
            case is_a($physicalSkill, Athletics::class) :
                if ($this->athletics !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('athletics is already set');
                }
                $this->athletics = $physicalSkill;
                break;
            case is_a($physicalSkill, Blacksmithing::class) :
                if ($this->blacksmithing !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('blacksmithing is already set');
                }
                $this->blacksmithing = $physicalSkill;
                break;
            case is_a($physicalSkill, BoatDriving::class) :
                if ($this->boatDriving !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('boatDriving is already set');
                }
                $this->boatDriving = $physicalSkill;
                break;
            case is_a($physicalSkill, CartDriving::class) :
                if ($this->cartDriving !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('cartDriving is already set');
                }
                $this->cartDriving = $physicalSkill;
                break;
            case is_a($physicalSkill, CityMoving::class) :
                if ($this->cityMoving !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('cityMoving is already set');
                }
                $this->cityMoving = $physicalSkill;
                break;
            case is_a($physicalSkill, ClimbingAndHillwalking::class) :
                if ($this->climbingAndHillwalking !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('climbingAndHillwalking is already set');
                }
                $this->climbingAndHillwalking = $physicalSkill;
                break;
            case is_a($physicalSkill, FastMarsh::class) :
                if ($this->fastMarsh !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fastMarsh is already set');
                }
                $this->fastMarsh = $physicalSkill;
                break;
            case is_a($physicalSkill, FightUnarmed::class) :
                if ($this->fightUnarmed !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightUnarmed is already set');
                }
                $this->fightUnarmed = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithAxes::class) :
                if ($this->fightWithAxes !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithAxes is already set');
                }
                $this->fightWithAxes = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithKnifesAndDaggers::class) :
                if ($this->fightWithKnifesAndDaggers !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithKnifesAndDaggers is already set');
                }
                $this->fightWithKnifesAndDaggers = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithMacesAndClubs::class) :
                if ($this->fightWithMacesAndClubs !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithMacesAndClubs is already set');
                }
                $this->fightWithMacesAndClubs = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithMorningstarsAndMorgensterns::class) :
                if ($this->fightWithMorningStarsAndMorgensterns !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithMorningStarsAndMorgensterns is already set');
                }
                $this->fightWithMorningStarsAndMorgensterns = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithSabersAndBowieKnifes::class) :
                if ($this->fightWithSabersAndBowieKnifes !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithSabersAndBowieKnifes is already set');
                }
                $this->fightWithSabersAndBowieKnifes = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithStaffsAndSpears::class) :
                if ($this->fightWithStaffsAndSpears !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithStaffsAndSpears is already set');
                }
                $this->fightWithStaffsAndSpears = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithShields::class) :
                if ($this->fightWithShields !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithShields is already set');
                }
                $this->fightWithShields = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithSwords::class) :
                if ($this->fightWithSwords !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithSwords is already set');
                }
                $this->fightWithSwords = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithThrowingWeapons::class) :
                if ($this->fightWithThrowingWeapons !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithThrowingWeapons is already set');
                }
                $this->fightWithThrowingWeapons = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithTwoWeapons::class) :
                if ($this->fightWithTwoWeapons !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithTwoWeapons is already set');
                }
                $this->fightWithTwoWeapons = $physicalSkill;
                break;
            case is_a($physicalSkill, FightWithVoulgesAndTridents::class) :
                if ($this->fightWithVoulgesAndTridents !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithVoulgesAndTridents is already set');
                }
                $this->fightWithVoulgesAndTridents = $physicalSkill;
                break;
            case is_a($physicalSkill, Flying::class) :
                if ($this->flying !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('flying is already set');
                }
                $this->flying = $physicalSkill;
                break;
            case is_a($physicalSkill, ForestMoving::class) :
                if ($this->forestMoving !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('forestMoving is already set');
                }
                $this->forestMoving = $physicalSkill;
                break;
            case is_a($physicalSkill, MovingInMountains::class) :
                if ($this->movingInMountains !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('movingInMountain is already set');
                }
                $this->movingInMountains = $physicalSkill;
                break;
            case is_a($physicalSkill, Riding::class) :
                if ($this->riding !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('riding is already set');
                }
                $this->riding = $physicalSkill;
                break;
            case is_a($physicalSkill, Sailing::class) :
                if ($this->sailing !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('sailing is already set');
                }
                $this->sailing = $physicalSkill;
                break;
            case is_a($physicalSkill, ShieldUsage::class) :
                if ($this->shieldUsage !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('shieldUsage is already set');
                }
                $this->shieldUsage = $physicalSkill;
                break;
            case is_a($physicalSkill, Swimming::class) :
                if ($this->swimming !== null) {
                    throw new Exceptions\PhysicalSkillAlreadySet('swimming is already set');
                }
                $this->swimming = $physicalSkill;
                break;
            default :
                throw new Exceptions\UnknownPhysicalSkill(
                    'Unknown physical skill ' . get_class($physicalSkill)
                );
        }
    }

    /**
     * @return string
     */
    public function getSkillsGroupName()
    {
        return self::PHYSICAL;
    }

    /**
     * @return ArmorWearing|null
     */
    public function getArmorWearing()
    {
        return $this->armorWearing;
    }

    /**
     * @return Athletics|null
     */
    public function getAthletics()
    {
        return $this->athletics;
    }

    /**
     * @return Blacksmithing|null
     */
    public function getBlacksmithing()
    {
        return $this->blacksmithing;
    }

    /**
     * @return BoatDriving|null
     */
    public function getBoatDriving()
    {
        return $this->boatDriving;
    }

    /**
     * @return CartDriving|null
     */
    public function getCartDriving()
    {
        return $this->cartDriving;
    }

    /**
     * @return CityMoving|null
     */
    public function getCityMoving()
    {
        return $this->cityMoving;
    }

    /**
     * @return ClimbingAndHillwalking|null
     */
    public function getClimbingAndHillwalking()
    {
        return $this->climbingAndHillwalking;
    }

    /**
     * @return FastMarsh|null
     */
    public function getFastMarsh()
    {
        return $this->fastMarsh;
    }

    /**
     * @return FightWithWeaponsUsingPhysicalSkill|null
     */
    public function getFightUnarmed()
    {
        return $this->fightUnarmed;
    }

    /**
     * @return FightWithAxes|null
     */
    public function getFightWithAxes()
    {
        return $this->fightWithAxes;
    }

    /**
     * @return FightWithKnifesAndDaggers|null
     */
    public function getFightWithKnifesAndDaggers()
    {
        return $this->fightWithKnifesAndDaggers;
    }

    /**
     * @return FightWithMacesAndClubs|null
     */
    public function getFightWithMacesAndClubs()
    {
        return $this->fightWithMacesAndClubs;
    }

    /**
     * @return FightWithMorningstarsAndMorgensterns|null
     */
    public function getFightWithMorningStarsAndMorgensterns()
    {
        return $this->fightWithMorningStarsAndMorgensterns;
    }

    /**
     * @return FightWithSabersAndBowieKnifes|null
     */
    public function getFightWithSabersAndBowieKnifes()
    {
        return $this->fightWithSabersAndBowieKnifes;
    }

    /**
     * @return FightWithStaffsAndSpears|null
     */
    public function getFightWithStaffsAndSpears()
    {
        return $this->fightWithStaffsAndSpears;
    }

    /**
     * This skill is not part of PPH, but is as crazy as well as possible.
     *
     * @return FightWithShields|null
     */
    public function getFightWithShields()
    {
        return $this->fightWithShields;
    }

    /**
     * @return FightWithSwords|null
     */
    public function getFightWithSwords()
    {
        return $this->fightWithSwords;
    }

    /**
     * @return FightWithThrowingWeapons|null
     */
    public function getFightWithThrowingWeapons()
    {
        return $this->fightWithThrowingWeapons;
    }

    /**
     * @return FightWithTwoWeapons|null
     */
    public function getFightWithTwoWeapons()
    {
        return $this->fightWithTwoWeapons;
    }

    /**
     * @return FightWithVoulgesAndTridents|null
     */
    public function getFightWithVoulgesAndTridents()
    {
        return $this->fightWithVoulgesAndTridents;
    }

    /**
     * @return array|FightWithWeaponsUsingPhysicalSkill[]
     */
    public function getFightWithMeleeWeaponSkills()
    {
        return array_values( // rebuild indexes sequence
            array_filter([ // remove nulls
                $this->getFightUnarmed(),
                $this->getFightWithAxes(),
                $this->getFightWithKnifesAndDaggers(),
                $this->getFightWithMacesAndClubs(),
                $this->getFightWithMorningStarsAndMorgensterns(),
                $this->getFightWithSabersAndBowieKnifes(),
                $this->getFightWithStaffsAndSpears(),
                $this->getFightWithSwords(),
                $this->getFightWithThrowingWeapons(),
                $this->getFightWithTwoWeapons(),
                $this->getFightWithVoulgesAndTridents(),
            ])
        );
    }

    /**
     * @return Flying|null
     */
    public function getFlying()
    {
        return $this->flying;
    }

    /**
     * @return ForestMoving|null
     */
    public function getForestMoving()
    {
        return $this->forestMoving;
    }

    /**
     * @return MovingInMountains|null
     */
    public function getMovingInMountains()
    {
        return $this->movingInMountains;
    }

    /**
     * @return Riding|null
     */
    public function getRiding()
    {
        return $this->riding;
    }

    /**
     * @return Sailing|null
     */
    public function getSailing()
    {
        return $this->sailing;
    }

    /**
     * @return ShieldUsage|null
     */
    public function getShieldUsage()
    {
        return $this->shieldUsage;
    }

    /**
     * @return Swimming|null
     */
    public function getSwimming()
    {
        return $this->swimming;
    }

    /**
     * @return array|\string[]
     */
    public function getCodesOfAllSameTypeSkills()
    {
        return PhysicalSkillCode::getPhysicalSkillCodes();
    }

    /**
     * Note about SHIELD: "weaponlike" means for attacking. If you provide a shield, it will considered as a weapon for
     * direct attack. If yu want fight number malus with shield as a protective armament, use @see
     * \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective And one more note: RESTRICTION from
     * shield is NOT applied if the shield is used as a weapon
     * (malus is already included in FightWithShields skill).
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToFightNumberWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        MissingWeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $missingWeaponSkillsTable->getFightNumberMalusForSkill($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $missingWeaponSkillsTable->getFightNumberMalusForSkill(
                $this->determineCurrentSkillRankValue($this->getFightWithTwoWeapons())
            );
        }

        return $malus;
    }

    /**
     * Note about SHIELD: "fight with" means attacking - for shield standard usage as
     * a protective armament @see \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    private function getHighestRankForSuitableFightWithWeapon(WeaponlikeCode $weaponlikeCode)
    {
        $rankValues = [];
        if ($weaponlikeCode->isMelee()) {
            $weaponlikeCode = $weaponlikeCode->convertToMeleeWeaponCodeEquivalent();
            /** @var MeleeWeaponCode $weaponlikeCode */
            if ($weaponlikeCode->isAxe()) {
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithAxes());
            }
            if ($weaponlikeCode->isKnifeOrDagger()) {
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithKnifesAndDaggers());
            }
            if ($weaponlikeCode->isMaceOrClub()) {
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithMacesAndClubs());
            }
            if ($weaponlikeCode->isMorningstarOrMorgenstern()) {
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithMorningStarsAndMorgensterns());
            }
            if ($weaponlikeCode->isSaberOrBowieKnife()) {
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithSabersAndBowieKnifes());
            }
            if ($weaponlikeCode->isStaffOrSpear()) {
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithStaffsAndSpears());
            }
            if ($weaponlikeCode->isSword()) {
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithSwords());
            }
            if ($weaponlikeCode->isUnarmed()) {
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightUnarmed());
            }
            if ($weaponlikeCode->isVoulgeOrTrident()) {
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithVoulgesAndTridents());
            }
            if ($weaponlikeCode->isShield()) { // shield as a weapon
                $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithShields());
            }
        }
        if ($weaponlikeCode->isThrowingWeapon()) {
            $rankValues[] = $this->determineCurrentSkillRankValue($this->getFightWithThrowingWeapons());
        }
        $rankValue = false;
        if (count($rankValues) > 0) {
            $rankValue = max($rankValues);
        }
        if (!is_int($rankValue)) {
            throw new Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon(
                "Given weapon '{$weaponlikeCode}' is not usable by any physical skill"
            );
        }

        return $rankValue;
    }

    /**
     * @param PhysicalSkill|null $physicalSkill
     * @return int
     */
    private function determineCurrentSkillRankValue(PhysicalSkill $physicalSkill = null)
    {
        return $physicalSkill
            ? $physicalSkill->getCurrentSkillRank()->getValue()
            : 0;
    }

    /**
     * @param ProtectiveArmamentCode $protectiveArmamentCode
     * @param $armourer $armourer
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatArmament
     */
    public function getMalusToFightNumberWithProtective(
        ProtectiveArmamentCode $protectiveArmamentCode,
        Armourer $armourer
    )
    {
        if ($protectiveArmamentCode instanceof ArmorCode) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $armourer->getProtectiveArmamentRestrictionForSkill(
                $protectiveArmamentCode,
                $this->determineCurrentSkillRank($this->getArmorWearing())
            );
        }
        if ($protectiveArmamentCode instanceof ShieldCode) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $armourer->getProtectiveArmamentRestrictionForSkill(
                $protectiveArmamentCode,
                $this->determineCurrentSkillRank($this->getShieldUsage())
            );
        }
        throw new Exceptions\PhysicalSkillsDoNotKnowHowToUseThatArmament(
            "Given protective armament '{$protectiveArmamentCode}' is not usable by any physical skill"
        );
    }

    /**
     * @param PhysicalSkill|null $physicalSkill
     * @return PositiveInteger
     */
    private function determineCurrentSkillRank(PhysicalSkill $physicalSkill = null)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $physicalSkill
            ? $physicalSkill->getCurrentSkillRank()
            : new PositiveIntegerObject(0);
    }

    /**
     * Note about SHIELD: "weaponlike" means for attacking - for shield standard usage as
     * a protective armament @see \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToAttackNumberWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        MissingWeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $missingWeaponSkillsTable->getAttackNumberMalusForSkill($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $missingWeaponSkillsTable->getAttackNumberMalusForSkill(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
            );
        }

        return $malus;
    }

    /**
     * Note about SHIELD: "weaponlike" means for attacking - for shield standard usage as
     * a protective armament @see \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToCoverWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        MissingWeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $missingWeaponSkillsTable->getCoverMalusForSkill($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $missingWeaponSkillsTable->getCoverMalusForSkill(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
            );
        }

        return $malus;
    }

    /**
     * Without highest skill with shield usage you have a malus to cover with it. See PPH page 86 right column.
     *
     * @return int
     */
    public function getMalusToCoverWithShield()
    {
        if (!$this->getShieldUsage() || $this->shieldUsage->getCurrentSkillRank()->getValue() < SkillRank::MAX_RANK_VALUE) {
            return -2;
        }

        return 0;
    }

    /**
     * Note about SHIELD: "weaponlike" means for attacking - for shield standard usage as
     * a protective armament @see \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToBaseOfWoundsWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        MissingWeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $missingWeaponSkillsTable->getBaseOfWoundsMalusForSkill($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $missingWeaponSkillsTable->getBaseOfWoundsMalusForSkill(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
            );
        }

        return $malus;
    }
}