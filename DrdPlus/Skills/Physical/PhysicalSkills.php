<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\MeleeWeaponCode;
use DrdPlus\Codes\WeaponlikeCode;
use DrdPlus\Codes\PhysicalSkillCode;
use DrdPlus\Codes\SkillTypeCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\SameTypeSkills;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;

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

    public function getIterator()
    {
        return new \ArrayIterator(
            array_filter([
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
        );
    }

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
        return [
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
        ];
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
     * @param WeaponlikeCode $weaponlikeCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToFightNumber(WeaponlikeCode $weaponlikeCode, MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        $rankValue = $this->getSuitableFightWithWeaponHighestRank($weaponlikeCode);

        return $missingWeaponSkillsTable->getFightNumberForWeaponSkill($rankValue);
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    private function getSuitableFightWithWeaponHighestRank(WeaponlikeCode $weaponlikeCode)
    {
        $rankValues = [];
        if ($weaponlikeCode->isMeleeArmament()) {
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
        }
        /** @var WeaponlikeCode $weaponlikeCode */
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
     * @param WeaponlikeCode $weaponlikeCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToAttackNumber(WeaponlikeCode $weaponlikeCode, MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        $rankValue = $this->getSuitableFightWithWeaponHighestRank($weaponlikeCode);

        return $missingWeaponSkillsTable->getAttackNumberForWeaponSkill($rankValue);
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToCover(WeaponlikeCode $weaponlikeCode, MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        $rankValue = $this->getSuitableFightWithWeaponHighestRank($weaponlikeCode);

        return $missingWeaponSkillsTable->getCoverForWeaponSkill($rankValue);
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToBaseOfWounds(WeaponlikeCode $weaponlikeCode, MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        $rankValue = $this->getSuitableFightWithWeaponHighestRank($weaponlikeCode);

        return $missingWeaponSkillsTable->getBaseOfWoundsForWeaponSkill($rankValue);
    }
}