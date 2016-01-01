<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "athletics" = "Athletics",
 *  "blacksmithing" = "Blacksmithing",
 *  "boatDriving" = "BoatDriving",
 *  "cardDriving" = "CardDriving",
 *  "cityMoving" = "CityMoving",
 *  "climbingAndHillwalking" = "ClimbingAndHillwalking",
 *  "fastMarsh" = "FastMarsh",
 *  "fightWithWeapon" = "FightWithWeapon",
 *  "flying" = "Flying",
 *  "forestMoving" = "ForestMoving",
 *  "movingInMountain" = "MovingInMountain",
 *  "physicalSkills" = "PhysicalSkills",
 *  "riding" = "Riding",
 *  "sailing" = "Sailing",
 *  "shieldUsage" = "ShieldUsage",
 *  "swimming" = "Swimming",
 * })
 */
abstract class PersonPhysicalSkill extends PersonSkill
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     **/
    private $id;

    public function __construct(PhysicalSkillRank $physicalSkillRank)
    {
        parent::__construct($physicalSkillRank);
    }

    public function addPhysicalSkillRank(PhysicalSkillRank $physicalSkillRank)
    {
        parent::addSkillRank($physicalSkillRank);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getRelatedPropertyCodes()
    {
        return [Strength::STRENGTH, Agility::AGILITY];
    }

    /**
     * @return bool
     */
    public function isPhysical()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isPsychical()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isCombined()
    {
        return false;
    }

}
