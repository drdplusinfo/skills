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
use DrdPlus\Tables\Tables;

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

    /**
     * @param ProfessionLevel $professionLevel
     */
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
    public function getUnusedFirstLevelPhysicalSkillPointsValue(ProfessionLevels $professionLevels): int
    {
        return $this->getUnusedFirstLevelSkillPointsValue($this->getFirstLevelPhysicalPropertiesSum($professionLevels));
    }

    private function getFirstLevelPhysicalPropertiesSum(ProfessionLevels $professionLevels): int
    {
        return $professionLevels->getFirstLevelStrengthModifier() + $professionLevels->getFirstLevelAgilityModifier();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getUnusedNextLevelsPhysicalSkillPointsValue(ProfessionLevels $professionLevels): int
    {
        return $this->getUnusedNextLevelsSkillPointsValue($this->getNextLevelsPhysicalPropertiesSum($professionLevels));
    }

    private function getNextLevelsPhysicalPropertiesSum(ProfessionLevels $professionLevels): int
    {
        return $professionLevels->getNextLevelsStrengthModifier() + $professionLevels->getNextLevelsAgilityModifier();
    }

    /**
     * @return \Traversable|\ArrayIterator|PhysicalSkill[]
     */
    public function getIterator(): \Traversable
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
    public function getArmorWearing(): ArmorWearing
    {
        return $this->armorWearing;
    }

    /**
     * @return Athletics
     */
    public function getAthletics(): Athletics
    {
        return $this->athletics;
    }

    /**
     * @return Blacksmithing
     */
    public function getBlacksmithing(): Blacksmithing
    {
        return $this->blacksmithing;
    }

    /**
     * @return BoatDriving
     */
    public function getBoatDriving(): BoatDriving
    {
        return $this->boatDriving;
    }

    /**
     * @return CartDriving
     */
    public function getCartDriving(): CartDriving
    {
        return $this->cartDriving;
    }

    /**
     * @return CityMoving
     */
    public function getCityMoving(): CityMoving
    {
        return $this->cityMoving;
    }

    /**
     * @return ClimbingAndHillwalking
     */
    public function getClimbingAndHillwalking(): ClimbingAndHillwalking
    {
        return $this->climbingAndHillwalking;
    }

    /**
     * @return FastMarsh
     */
    public function getFastMarsh(): FastMarsh
    {
        return $this->fastMarsh;
    }

    /**
     * @return FightUnarmed|FightWithWeaponsUsingPhysicalSkill
     */
    public function getFightUnarmed(): FightUnarmed
    {
        return $this->fightUnarmed;
    }

    /**
     * @return FightWithAxes
     */
    public function getFightWithAxes(): FightWithAxes
    {
        return $this->fightWithAxes;
    }

    /**
     * @return FightWithKnifesAndDaggers
     */
    public function getFightWithKnifesAndDaggers(): FightWithKnifesAndDaggers
    {
        return $this->fightWithKnifesAndDaggers;
    }

    /**
     * @return FightWithMacesAndClubs
     */
    public function getFightWithMacesAndClubs(): FightWithMacesAndClubs
    {
        return $this->fightWithMacesAndClubs;
    }

    /**
     * @return FightWithMorningstarsAndMorgensterns
     */
    public function getFightWithMorningstarsAndMorgensterns(): FightWithMorningstarsAndMorgensterns
    {
        return $this->fightWithMorningstarsAndMorgensterns;
    }

    /**
     * @return FightWithSabersAndBowieKnifes
     */
    public function getFightWithSabersAndBowieKnifes(): FightWithSabersAndBowieKnifes
    {
        return $this->fightWithSabersAndBowieKnifes;
    }

    /**
     * @return FightWithStaffsAndSpears
     */
    public function getFightWithStaffsAndSpears(): FightWithStaffsAndSpears
    {
        return $this->fightWithStaffsAndSpears;
    }

    /**
     * This skill is not part of PPH, but is as crazy as well as possible.
     *
     * @return FightWithShields
     */
    public function getFightWithShields(): FightWithShields
    {
        return $this->fightWithShields;
    }

    /**
     * @return FightWithSwords
     */
    public function getFightWithSwords(): FightWithSwords
    {
        return $this->fightWithSwords;
    }

    /**
     * @return FightWithThrowingWeapons
     */
    public function getFightWithThrowingWeapons(): FightWithThrowingWeapons
    {
        return $this->fightWithThrowingWeapons;
    }

    /**
     * @return FightWithTwoWeapons
     */
    public function getFightWithTwoWeapons(): FightWithTwoWeapons
    {
        return $this->fightWithTwoWeapons;
    }

    /**
     * @return FightWithVoulgesAndTridents
     */
    public function getFightWithVoulgesAndTridents(): FightWithVoulgesAndTridents
    {
        return $this->fightWithVoulgesAndTridents;
    }

    /**
     * @return array|FightWithWeaponsUsingPhysicalSkill[]
     */
    public function getFightWithWeaponsUsingPhysicalSkills(): array
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
    public function getFlying(): Flying
    {
        return $this->flying;
    }

    /**
     * @return ForestMoving
     */
    public function getForestMoving(): ForestMoving
    {
        return $this->forestMoving;
    }

    /**
     * @return MovingInMountains
     */
    public function getMovingInMountains(): MovingInMountains
    {
        return $this->movingInMountains;
    }

    /**
     * @return Riding
     */
    public function getRiding(): Riding
    {
        return $this->riding;
    }

    /**
     * @return Sailing
     */
    public function getSailing(): Sailing
    {
        return $this->sailing;
    }

    /**
     * @return ShieldUsage
     */
    public function getShieldUsage(): ShieldUsage
    {
        return $this->shieldUsage;
    }

    /**
     * @return Swimming
     */
    public function getSwimming(): Swimming
    {
        return $this->swimming;
    }

    /**
     * Note about SHIELD: "weaponlike" means for attacking. If you provide a shield, it will considered as a weapon for
     * direct attack. If you want malus to a fight number with shield as a protective armament,
     * use @see \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective
     * And one more note: RESTRICTION from shield is NOT applied (and SHOULD NOT be) if the shield is used as a weapon
     * (total malus is already included in the @see FightWithShields skill).
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param Tables $tables
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToFightNumberWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        Tables $tables,
        $fightsWithTwoWeapons
    ): int
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $tables->getMissingWeaponSkillTable()->getFightNumberMalusForSkillRank($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $tables->getMissingWeaponSkillTable()->getFightNumberMalusForSkillRank(
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
    private function getHighestRankForSuitableFightWithWeapon(WeaponlikeCode $weaponlikeCode): int
    {
        $rankValues = [];
        if ($weaponlikeCode->isShield()) { // shield as a weapon
            $rankValues[] = $this->getFightWithShields()->getCurrentSkillRank()->getValue();
        }
        if ($weaponlikeCode->isWeapon() && $weaponlikeCode->isMelee()) {
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
    ): int
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
     * @param Tables $tables
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToAttackNumberWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        Tables $tables,
        $fightsWithTwoWeapons
    ): int
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $tables->getMissingWeaponSkillTable()->getAttackNumberMalusForSkillRank($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $tables->getMissingWeaponSkillTable()->getAttackNumberMalusForSkillRank(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
            );
        }

        return $malus;
    }

    /**
     * For SHIELD use @see getMalusToFightNumberWithProtective
     *
     * @param WeaponCode $weaponCode
     * @param Tables $tables
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToCoverWithWeapon(
        WeaponCode $weaponCode,
        Tables $tables,
        $fightsWithTwoWeapons
    ): int
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $tables->getMissingWeaponSkillTable()->getCoverMalusForSkillRank($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $tables->getMissingWeaponSkillTable()->getCoverMalusForSkillRank(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
            );
        }

        return $malus;
    }

    /**
     * Warning: PPH gives you false info about malus to cover with shield (see PPH page 86 right column).
     * Correct is as gives @see \DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable
     *
     * @param Tables $tables
     * @return int
     */
    public function getMalusToCoverWithShield(Tables $tables): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getShieldUsageSkillTable()->getCoverMalusForSkillRank($this->getShieldUsage()->getCurrentSkillRank());
    }

    /**
     * Note about SHIELD: "weaponlike" means for attacking - for shield standard usage as
     * a protective armament @see \DrdPlus\Skills\Physical\PhysicalSkills::getMalusToFightNumberWithProtective
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param Tables $tables
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     */
    public function getMalusToBaseOfWoundsWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        Tables $tables,
        $fightsWithTwoWeapons
    ): int
    {
        $fightWithWeaponRankValue = $this->getHighestRankForSuitableFightWithWeapon($weaponlikeCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malus = $tables->getMissingWeaponSkillTable()->getBaseOfWoundsMalusForSkillRank($fightWithWeaponRankValue);
        if ($fightsWithTwoWeapons) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $malus += $tables->getMissingWeaponSkillTable()->getBaseOfWoundsMalusForSkillRank(
                $this->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue()
            );
        }

        return $malus;
    }

    /**
     * @return int
     */
    public function getMalusToFightNumberWhenRiding(): int
    {
        return $this->getRiding()->getMalusToFightAttackAndDefenseNumber();
    }

    /**
     * @return int
     */
    public function getMalusToAttackNumberWhenRiding(): int
    {
        return $this->getRiding()->getMalusToFightAttackAndDefenseNumber();
    }

    /**
     * @return int
     */
    public function getMalusToDefenseNumberWhenRiding(): int
    {
        return $this->getRiding()->getMalusToFightAttackAndDefenseNumber();
    }
}