<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\Skills\PersonSameTypeSkills;

/**
 * PhysicalSkills
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class PersonPhysicalSkills extends PersonSameTypeSkills
{
    const PHYSICAL = SkillCodes::PHYSICAL;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @var ArmorWearing|null
     * @ORM\OneToOne(targetEntity="ArmorWearing")
     */
    private $armorWearing;
    /** @var Athletics|null
     * @ORM\OneToOne(targetEntity="Athletics")
     */
    private $athletics;
    /** @var Blacksmithing|null
     * @ORM\OneToOne(targetEntity="Blacksmithing")
     */
    private $blacksmithing;
    /** @var BoatDriving|null
     * @ORM\OneToOne(targetEntity="BoatDriving")
     */
    private $boatDriving;
    /** @var CartDriving|null
     * @ORM\OneToOne(targetEntity="CartDriving")
     */
    private $cartDriving;
    /** @var CityMoving|null
     * @ORM\OneToOne(targetEntity="CityMoving")
     */
    private $cityMoving;
    /** @var ClimbingAndHillwalking|null
     * @ORM\OneToOne(targetEntity="ClimbingAndHillwalking")
     */
    private $climbingAndHillwalking;
    /** @var FastMarsh|null
     * @ORM\OneToOne(targetEntity="FastMarsh")
     */
    private $fastMarsh;
    /** @var FightWithWeapon|null
     * @ORM\OneToOne(targetEntity="FightWithWeapon")
     */
    private $fightWithWeapon;
    /** @var Flying|null
     * @ORM\OneToOne(targetEntity="Flying")
     */
    private $flying;
    /** @var ForestMoving|null
     * @ORM\OneToOne(targetEntity="ForestMoving")
     */
    private $forestMoving;
    /** @var MovingInMountains|null
     * @ORM\OneToOne(targetEntity="MovingInMountains")
     */
    private $movingInMountain;
    /** @var Riding|null
     * @ORM\OneToOne(targetEntity="Riding")
     */
    private $riding;
    /** @var Sailing|null
     * @ORM\OneToOne(targetEntity="Sailing")
     */
    private $sailing;
    /** @var ShieldUsage|null
     * @ORM\OneToOne(targetEntity="ShieldUsage")
     */
    private $shieldUsage;
    /** @var Swimming|null
     * @ORM\OneToOne(targetEntity="Swimming")
     */
    private $swimming;

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getFreeFirstLevelPhysicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        return $this->getFreeFirstLevelSkillPointsValue($this->getFirstLevelPhysicalPropertiesSum($professionLevels));
    }

    private function getFirstLevelPhysicalPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getFirstLevelStrengthModifier() + $professionLevels->getFirstLevelAgilityModifier();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getFreeNextLevelsPhysicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        return $this->getFreeNextLevelsSkillPointsValue($this->getNextLevelsPhysicalPropertiesSum($professionLevels));
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
                $this->getFightWithWeapon(),
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

    public function addPhysicalSkill(PersonPhysicalSkill $physicalSkill)
    {
        switch (true) {
            case is_a($physicalSkill, ArmorWearing::class) :
                if (isset($this->armorWearing)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('armorWearing  is already set');
                }
                $this->armorWearing = $physicalSkill;    
                break;
            case is_a($physicalSkill, Athletics::class) :
                if (isset($this->athletics)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('athletics  is already set');
                }
                $this->athletics = $physicalSkill;    
                break;
            case is_a($physicalSkill, Blacksmithing::class) :
                if (isset($this->blacksmithing)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('blacksmithing  is already set');
                }
                $this->blacksmithing = $physicalSkill;    
                break;
            case is_a($physicalSkill, BoatDriving::class) :
                if (isset($this->boatDriving)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('boatDriving  is already set');
                }
                $this->boatDriving = $physicalSkill;    
                break;
            case is_a($physicalSkill, CartDriving::class) :
                if (isset($this->cartDriving)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('cartDriving  is already set');
                }
                $this->cartDriving = $physicalSkill;    
                break;
            case is_a($physicalSkill, CityMoving::class) :
                if (isset($this->cityMoving)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('cityMoving  is already set');
                }
                $this->cityMoving = $physicalSkill;    
                break;
            case is_a($physicalSkill, ClimbingAndHillwalking::class) :
                if (isset($this->climbingAndHillwalking)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('climbingAndHillwalking  is already set');
                }
                $this->climbingAndHillwalking = $physicalSkill;    
                break;
            case is_a($physicalSkill, FastMarsh::class) :
                if (isset($this->fastMarsh)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fastMarsh  is already set');
                }
                $this->fastMarsh = $physicalSkill;    
                break;
            case is_a($physicalSkill, FightWithWeapon::class) :
                if (isset($this->fightWithWeapon)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('fightWithWeapon  is already set');
                }
                $this->fightWithWeapon = $physicalSkill;    
                break;
            case is_a($physicalSkill, Flying::class) :
                if (isset($this->flying)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('flying  is already set');
                }
                $this->flying = $physicalSkill;    
                break;
            case is_a($physicalSkill, ForestMoving::class) :
                if (isset($this->forestMoving)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('forestMoving  is already set');
                }
                $this->forestMoving = $physicalSkill;    
                break;
            case is_a($physicalSkill, MovingInMountains::class) :
                if (isset($this->movingInMountain)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('movingInMountain  is already set');
                }
                $this->movingInMountain = $physicalSkill;    
                break;
            case is_a($physicalSkill, Riding::class) :
                if (isset($this->riding)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('riding  is already set');
                }
                $this->riding = $physicalSkill;    
                break;
            case is_a($physicalSkill, Sailing::class) :
                if (isset($this->sailing)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('sailing  is already set');
                }
                $this->sailing = $physicalSkill;    
                break;
            case is_a($physicalSkill, ShieldUsage::class) :
                if (isset($this->shieldUsage)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('shieldUsage  is already set');
                }
                $this->shieldUsage = $physicalSkill;    
                break;
            case is_a($physicalSkill, Swimming::class) :
                if (isset($this->swimming)) {
                    throw new Exceptions\PhysicalSkillAlreadySet('swimming  is already set');
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
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
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
     * @return FightWithWeapon|null
     */
    public function getFightWithWeapon()
    {
        return $this->fightWithWeapon;
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
        return $this->movingInMountain;
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
}
