<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

abstract class PersonSkillRank extends StrictObject implements IntegerInterface
{
    const MIN_RANK_VALUE = 0; // heard about it
    const MAX_RANK_VALUE = 3; // perfect knowledge

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var ProfessionLevel
     *
     * @ORM\ManyToOne(targetEntity="\DrdPlus\Person\ProfessionLevels\ProfessionLevel")
     */
    private $professionLevel;
    /**
     * @var PersonSkillPoint
     *
     * @ORM\OneToOne(targetEntity="\DrdPlus\Person\Skills\AbstractSkillPoint")
     */
    private $personSkillPoint;
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @param ProfessionLevel $professionLevel
     * @param PersonSkillPoint $personSkillPoint
     * @param IntegerInterface $requiredRankValue
     */
    protected function __construct(
        ProfessionLevel $professionLevel,
        PersonSkillPoint $personSkillPoint,
        IntegerInterface $requiredRankValue
    )
    {
        $this->professionLevel = $professionLevel; // skill rank has been achieved on this profession level
        $this->personSkillPoint = $personSkillPoint; // this skill point has been consumed to achieve this rank
        $this->checkRequiredRankValue($requiredRankValue);
        $this->value = $requiredRankValue->getValue();
    }

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
        return $this->professionLevel;
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
