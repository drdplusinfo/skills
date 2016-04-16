<?php
namespace DrdPlus\Person\Skills;

use Doctrineum\Entity\Entity;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="rankType", type="string")
 * @ORM\DiscriminatorMap({
 *     "combined" = "\DrdPlus\Person\Skills\Combined\CombinedSkillRank",
 *     "physical" = "\DrdPlus\Person\Skills\Physical\PhysicalSkillRank",
 *     "psychical" = "\DrdPlus\Person\Skills\Psychical\PsychicalSkillRank"
 * })
 */
abstract class PersonSkillRank extends StrictObject implements IntegerInterface, Entity
{

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    private $id;
    /**
     * @var PersonSkillPoint
     * @ORM\OneToOne(targetEntity="\DrdPlus\Person\Skills\PersonSkillPoint", cascade={"persist"})
     */
    private $personSkillPoint;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @param PersonSkillPoint $personSkillPoint
     * @param IntegerInterface $requiredRankValue
     */
    protected function __construct(PersonSkillPoint $personSkillPoint, IntegerInterface $requiredRankValue)
    {
        $this->personSkillPoint = $personSkillPoint; // this skill point has been consumed to achieve this rank
        $this->checkRequiredRankValue($requiredRankValue);
        $this->value = $requiredRankValue->getValue();
    }

    const MIN_RANK_VALUE = 0; // heard about it
    const MAX_RANK_VALUE = 3; // great knowledge

    private function checkRequiredRankValue(IntegerInterface $requiredRankValue)
    {
        if ($requiredRankValue->getValue() < self::MIN_RANK_VALUE) {
            throw new \LogicException(
                'Rank value can not be lower than ' . self::MIN_RANK_VALUE . ', got ' . $requiredRankValue
            );
        }
        if ($requiredRankValue->getValue() > self::MAX_RANK_VALUE) {
            throw new \LogicException(
                'Rank value can not be greater than ' . self::MIN_RANK_VALUE . ' got ' . $requiredRankValue
            );
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ProfessionLevel
     */
    public function getProfessionLevel()
    {
        return $this->getPersonSkillPoint()->getProfessionLevel();
    }

    /**
     * @return PersonSkillPoint
     */
    public function getPersonSkillPoint()
    {
        return $this->personSkillPoint;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getValue();
    }
}
