<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Armaments\ArmorCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\ProtectiveArmamentCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\SameTypeSkills;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Tables\Armaments\Armourer;
use DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable;
use DrdPlus\Tables\Armaments\Weapons\WeaponSkillTable;

/**
 * @ORM\Entity()
 */
class PhysicalSkills extends SameTypeSkills
{
    const PHYSICAL = SkillTypeCode::PHYSICAL;

    /**
     * @var ArmorWearing
     * @ORM\OneToOne(targetEntity="ArmorWearing", cascade={"persist"}, orphanRemoval=true)
     */
    private $armorWearing;
    /**
     * @var Athletics
     * @ORM\OneToOne(targetEntity="Athletics", cascade={"persist"}, orphanRemoval=true)
     */
    private $athletics;
    /**
     * @var Blacksmithing
     * @ORM\OneToOne(targetEntity="Blacksmithing", cascade={"persist"}, orphanRemoval=true)
     */
    private $blacksmithing;
    /**
     * @var BoatDriving
     * @ORM\OneToOne(targetEntity="BoatDriving", cascade={"persist"}, orphanRemoval=true)
     */
    private $boatDriving;
    /**
     * @var CartDriving
     * @ORM\OneToOne(targetEntity="CartDriving", cascade={"persist"}, orphanRemoval=true)
     */
    private $cartDriving;
    /**
     * @var CityMoving
     * @ORM\OneToOne(targetEntity="CityMoving", cascade={"persist"}, orphanRemoval=true)
     */
    private $cityMoving;
    /**
     * @var ClimbingAndHillwalking
     * @ORM\OneToOne(targetEntity="ClimbingAndHillwalking", cascade={"persist"}, orphanRemoval=true)
     */
    private $climbingAndHillwalking;
    /**
     * @var FastMarsh
     * @ORM\OneToOne(targetEntity="FastMarsh", cascade={"persist"}, orphanRemoval=true)
     */
    private $fastMarsh;
    /**
     * @var FightUnarmed
     * @ORM\OneToOne(targetEntity="FightUnarmed", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightUnarmed;
    /**
     * @var FightWithAxes
     * @ORM\OneToOne(targetEntity="FightWithAxes", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithAxes;
    /**
     * @var FightWithKnifesAndDaggers
     * @ORM\OneToOne(targetEntity="FightWithKnifesAndDaggers", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithKnifesAndDaggers;
    /**
     * @var FightWithMacesAndClubs
     * @ORM\OneToOne(targetEntity="FightWithMacesAndClubs", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithMacesAndClubs;
    /**
     * @var FightWithMorningstarsAndMorgensterns
     * @ORM\OneToOne(targetEntity="FightWithMorningStarsAndMorgensterns", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithMorningstarsAndMorgensterns;
    /**
     * @var FightWithSabersAndBowieKnifes
     * @ORM\OneToOne(targetEntity="FightWithSabersAndBowieKnifes", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithSabersAndBowieKnifes;
    /**
     * @var FightWithStaffsAndSpears
     * @ORM\OneToOne(targetEntity="FightWithStaffsAndSpears", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithStaffsAndSpears;
    /**
     * @var FightWithShields
     * @ORM\OneToOne(targetEntity="FightWithShields", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithShields;
    /**
     * @var FightWithSwords
     * @ORM\OneToOne(targetEntity="FightWithSwords", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithSwords;
    /**
     * @var FightWithThrowingWeapons
     * @ORM\OneToOne(targetEntity="FightWithThrowingWeapons", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithThrowingWeapons;
    /**
     * @var FightWithTwoWeapons
     * @ORM\OneToOne(targetEntity="FightWithTwoWeapons", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithTwoWeapons;
    /**
     * @var FightWithVoulgesAndTridents
     * @ORM\OneToOne(targetEntity="FightWithVoulgesAndTridents", cascade={"persist"}, orphanRemoval=true)
     */
    private $fightWithVoulgesAndTridents;
    /**
     * @var Flying
     * @ORM\OneToOne(targetEntity="Flying", cascade={"persist"}, orphanRemoval=true)
     */
    private $flying;
    /**
     * @var ForestMoving
     * @ORM\OneToOne(targetEntity="ForestMoving", cascade={"persist"}, orphanRemoval=true)
     */
    private $forestMoving;
    /**
     * @var MovingInMountains
     * @ORM\OneToOne(targetEntity="MovingInMountains", cascade={"persist"}, orphanRemoval=true)
     */
    private $movingInMountains;
    /**
     * @var Riding
     * @ORM\OneToOne(targetEntity="Riding", cascade={"persist"}, orphanRemoval=true)
     */
    private $riding;
    /**
     * @var Sailing
     * @ORM\OneToOne(targetEntity="Sailing", cascade={"persist"}, orphanRemoval=true)
     */
    private $sailing;
    /**
     * @var ShieldUsage
     * @ORM\OneToOne(targetEntity="ShieldUsage", cascade={"persist"}, orphanRemoval=true)
     */
    private $shieldUsage;
    /**
     * @var Swimming
     * @ORM\OneToOne(targetEntity="Swimming", cascade={"persist"}, orphanRemoval=true)
     */
    private $swimming;

    protected function populateAllSkills(ProfessionLevel $professionLevel)
    {
        $this->armorWearing = new ArmorWearing($professionLevel);
        $this->athletics = new Athletics($professionLevel);
        $this->blacksmithing = new Blacksmithing($professionLevel);
        $this->boatDriving = new BoatDriving($professionLevel);
        $this->cartDriving = new CartDriving($professionLevel);
        $this->cityMoving = new CityMoving($professionLevel);
        $this->climbingAndHillwalking = new ClimbingAndHillwalking($professionLevel);
        $this->fastMarsh = new FastMarsh($professionLevel);
        $this->fightUnarmed = new FightUnarmed($professionLevel);
        $this->fightWithAxes = new FightWithAxes($professionLevel);
        $this->fightWithKnifesAndDaggers = new FightWithKnifesAndDaggers($professionLevel);
        $this->fightWithMacesAndClubs = new FightWithMacesAndClubs($professionLevel);
        $this->fightWithMorningstarsAndMorgensterns = new FightWithMorningstarsAndMorgensterns($professionLevel);
        $this->fightWithSabersAndBowieKnifes = new FightWithSabersAndBowieKnifes($professionLevel);
        $this->fightWithStaffsAndSpears = new FightWithStaffsAndSpears($professionLevel);
        $this->fightWithShields = new FightWithShields($professionLevel);
        $this->fightWithSwords = new FightWithSwords($professionLevel);
        $this->fightWithThrowingWeapons = new FightWithThrowingWeapons($professionLevel);
        $this->fightWithTwoWeapons = new FightWithTwoWeapons($professionLevel);
        $this->fightWithVoulgesAndTridents = new FightWithVoulgesAndTridents($professionLevel);
        $this->flying = new Flying($professionLevel);
        $this->forestMoving = new ForestMoving($professionLevel);
        $this->movingInMountains = new MovingInMountains($professionLevel);
        $this->riding = new Riding($professionLevel);
        $this->sailing = new Sailing($professionLevel);
        $this->shieldUsage = new ShieldUsage($professionLevel);
        $this->swimming = new Swimming($professionLevel);
    }

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
        return new \ArrayIterator([
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
            $this->getFightWithMorningstarsAndMorgensterns(),
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
        ]);
    }

    /**
     * @return ArmorWearing
     */
    public function getArmorWearing()
    {
        return $this->armorWearing;
    }

    /**
     * @return Athletics
     */
    public function getAthletics()
    {
        return $this->athletics;
    }

    /**
     * @return Blacksmithing
     */
    public function getBlacksmithing()
    {
        return $this->blacksmithing;
    }

    /**
     * @return BoatDriving
     */
    public function getBoatDriving()
    {
        return $this->boatDriving;
    }

    /**
     * @return CartDriving
     */
    public function getCartDriving()
    {
        return $this->cartDriving;
    }

    /**
     * @return CityMoving
     */
    public function getCityMoving()
    {
        return $this->cityMoving;
    }

    /**
     * @return ClimbingAndHillwalking
     */
    public function getClimbingAndHillwalking()
    {
        return $this->climbingAndHillwalking;
    }

    /**
     * @return FastMarsh
     */
    public function getFastMarsh()
    {
        return $this->fastMarsh;
    }

    /**
     * @return FightWithWeaponsUsingPhysicalSkill
     */
    public function getFightUnarmed()
    {
        return $this->fightUnarmed;
    }

    /**
     * @return FightWithAxes
     */
    public function getFightWithAxes()
    {
        return $this->fightWithAxes;
    }

    /**
     * @return FightWithKnifesAndDaggers
     */
    public function getFightWithKnifesAndDaggers()
    {
        return $this->fightWithKnifesAndDaggers;
    }

    /**
     * @return FightWithMacesAndClubs
     */
    public function getFightWithMacesAndClubs()
    {
        return $this->fightWithMacesAndClubs;
    }

    /**
     * @return FightWithMorningstarsAndMorgensterns
     */
    public function getFightWithMorningstarsAndMorgensterns()
    {
        return $this->fightWithMorningstarsAndMorgensterns;
    }

    /**
     * @return FightWithSabersAndBowieKnifes
     */
    public function getFightWithSabersAndBowieKnifes()
    {
        return $this->fightWithSabersAndBowieKnifes;
    }

    /**
     * @return FightWithStaffsAndSpears
     */
    public function getFightWithStaffsAndSpears()
    {
        return $this->fightWithStaffsAndSpears;
    }

    /**
     * This skill is not part of PPH, but is as crazy as well as possible.
     *
     * @return FightWithShields
     */
    public function getFightWithShields()
    {
        return $this->fightWithShields;
    }

    /**
     * @return FightWithSwords
     */
    public function getFightWithSwords()
    {
        return $this->fightWithSwords;
    }

    /**
     * @return FightWithThrowingWeapons
     */
    public function getFightWithThrowingWeapons()
    {
        return $this->fightWithThrowingWeapons;
    }

    /**
     * @return FightWithTwoWeapons
     */
    public function getFightWithTwoWeapons()
    {
        return $this->fightWithTwoWeapons;
    }

    /**
     * @return FightWithVoulgesAndTridents
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
            $this->getFightWithMorningstarsAndMorgensterns(),
            $this->getFightWithSabersAndBowieKnifes(),
            $this->getFightWithStaffsAndSpears(),
            $this->getFightWithSwords(),
            $this->getFightWithThrowingWeapons(),
            $this->getFightWithTwoWeapons(),
            $this->getFightWithVoulgesAndTridents(),
            $this->getFightWithShields(),
        ];
    }

    /**
     * @return Flying
     */
    public function getFlying()
    {
        return $this->flying;
    }

    /**
     * @return ForestMoving
     */
    public function getForestMoving()
    {
        return $this->forestMoving;
    }

    /**
     * @return MovingInMountains
     */
    public function getMovingInMountains()
    {
        return $this->movingInMountains;
    }

    /**
     * @return Riding
     */
    public function getRiding()
    {
        return $this->riding;
    }

    /**
     * @return Sailing
     */
    public function getSailing()
    {
        return $this->sailing;
    }

    /**
     * @return ShieldUsage
     */
    public function getShieldUsage()
    {
        return $this->shieldUsage;
    }

    /**
     * @return Swimming
     */
    public function getSwimming()
    {
        return $this->swimming;
    }

    /**
     * Note about SHIELD: "weaponlike" means for attacking. If you provide a shield, it will considered as a weapon for
     * direct attack. If yu want fight number malus with shield as a protective armament, use @see
     * \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective And one more note: RESTRICTION from
     * shield is NOT applied if the shield is used as a weapon
     * (malus is already included in FightWithShields skill).
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToFightNumberWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        WeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $missingWeaponSkillsTable->getFightNumberMalusForSkillRank($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $missingWeaponSkillsTable->getFightNumberMalusForSkillRank(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
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
                $rankValues[] = $this->getFightWithAxes()->getCurrentSkillRank()->getValue();
            }
            if ($weaponlikeCode->isKnifeOrDagger()) {
                $rankValues[] = $this->getFightWithKnifesAndDaggers()->getCurrentSkillRank()->getValue();
            }
            if ($weaponlikeCode->isMaceOrClub()) {
                $rankValues[] = $this->getFightWithMacesAndClubs()->getCurrentSkillRank()->getValue();
            }
            if ($weaponlikeCode->isMorningstarOrMorgenstern()) {
                $rankValues[] = $this->getFightWithMorningstarsAndMorgensterns()->getCurrentSkillRank()->getValue();
            }
            if ($weaponlikeCode->isSaberOrBowieKnife()) {
                $rankValues[] = $this->getFightWithSabersAndBowieKnifes()->getCurrentSkillRank()->getValue();
            }
            if ($weaponlikeCode->isStaffOrSpear()) {
                $rankValues[] = $this->getFightWithStaffsAndSpears()->getCurrentSkillRank()->getValue();
            }
            if ($weaponlikeCode->isSword()) {
                $rankValues[] = $this->getFightWithSwords()->getCurrentSkillRank()->getValue();
            }
            if ($weaponlikeCode->isUnarmed()) {
                $rankValues[] = $this->getFightUnarmed()->getCurrentSkillRank()->getValue();
            }
            if ($weaponlikeCode->isVoulgeOrTrident()) {
                $rankValues[] = $this->getFightWithVoulgesAndTridents()->getCurrentSkillRank()->getValue();
            }
            if ($weaponlikeCode->isShield()) { // shield as a weapon
                $rankValues[] = $this->getFightWithShields()->getCurrentSkillRank()->getValue();
            }
        }
        if ($weaponlikeCode->isThrowingWeapon()) {
            $rankValues[] = $this->getFightWithThrowingWeapons()->getCurrentSkillRank()->getValue();
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
            return $armourer->getProtectiveArmamentRestrictionForSkillRank(
                $protectiveArmamentCode,
                $this->getArmorWearing()->getCurrentSkillRank()
            );
        }
        if ($protectiveArmamentCode instanceof ShieldCode) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $armourer->getProtectiveArmamentRestrictionForSkillRank(
                $protectiveArmamentCode,
                $this->getShieldUsage()->getCurrentSkillRank()
            );
        }
        throw new Exceptions\PhysicalSkillsDoNotKnowHowToUseThatArmament(
            "Given protective armament '{$protectiveArmamentCode}' is not usable by any physical skill"
        );
    }

    /**
     * Note about SHIELD: "weaponlike" means for attacking - for shield standard usage as
     * a protective armament @see \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToAttackNumberWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        WeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $missingWeaponSkillsTable->getAttackNumberMalusForSkillRank($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $missingWeaponSkillsTable->getAttackNumberMalusForSkillRank(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
            );
        }

        return $malus;
    }

    /**
     * For SHIELD use @see getMalusToFightNumberWithProtective
     *
     * @param WeaponCode $weaponCode
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToCoverWithWeapon(
        WeaponCode $weaponCode,
        WeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $missingWeaponSkillsTable->getCoverMalusForSkillRank($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $missingWeaponSkillsTable->getCoverMalusForSkillRank(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
            );
        }

        return $malus;
    }

    /**
     * Warning: PPH gives you false info about malus to cover with shield (see PPH page 86 right column).
     * Correct is as gives @see \DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable
     *
     * @param ShieldUsageSkillTable $missingShieldSkillTable
     * @return int
     */
    public function getMalusToCoverWithShield(ShieldUsageSkillTable $missingShieldSkillTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingShieldSkillTable->getCoverMalusForSkillRank($this->getShieldUsage()->getCurrentSkillRank());
    }

    /**
     * Note about SHIELD: "weaponlike" means for attacking - for shield standard usage as
     * a protective armament @see \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToBaseOfWoundsWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        WeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $missingWeaponSkillsTable->getBaseOfWoundsMalusForSkillRank($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $missingWeaponSkillsTable->getBaseOfWoundsMalusForSkillRank(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
            );
        }

        return $malus;
    }
}