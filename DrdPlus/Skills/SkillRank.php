<?php
namespace DrdPlus\Skills;

use Doctrineum\Entity\Entity;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use Granam\Integer\PositiveInteger;
use Granam\Strict\Object\StrictObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="rankType", type="string")
 * @ORM\DiscriminatorMap({
 *     "combined" = "\DrdPlus\Skills\Combined\CombinedSkillRank",
 *     "physical" = "\DrdPlus\Skills\Physical\PhysicalSkillRank",
 *     "psychical" = "\DrdPlus\Skills\Psychical\PsychicalSkillRank"
 * })
 */
abstract class SkillRank extends StrictObject implements PositiveInteger, Entity
{

    /**
     * @var integer
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue()
     */
    private $id;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @param Skill $owningSkill
     * @param SkillPoint $personSkillPoint
     * @param PositiveInteger $requiredRankValue
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     */
    protected function __construct(
        Skill $owningSkill,
        SkillPoint $personSkillPoint,
        PositiveInteger $requiredRankValue
    )
    {
        if ($owningSkill !== $this->getSkill()) {
            throw new Exceptions\CanNotVerifyOwningSkill(
                'Person skill should be already set in descendant constructor'
            );
        }
        if ($personSkillPoint !== $this->getSkillPoint()) {
            throw new Exceptions\CanNotVerifyPaidSkillPoint(
                'Person skill point should be already set in descendant constructor'
            );
        }
        $this->checkRequiredRankValue($requiredRankValue);
        $this->value = $requiredRankValue->getValue();
    }

    const MIN_RANK_VALUE = 0; // heard about it
    const MAX_RANK_VALUE = 3; // great knowledge

    private function checkRequiredRankValue(PositiveInteger $requiredRankValue)
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
        return $this->getSkillPoint()->getProfessionLevel();
    }

    /**
     * @return SkillPoint
     */
    abstract public function getSkillPoint();

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Skill
     */
    abstract public function getSkill();

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getValue();
    }
}