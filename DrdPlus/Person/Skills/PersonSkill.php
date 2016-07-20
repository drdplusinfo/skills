<?php
namespace DrdPlus\Person\Skills;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrineum\Entity\Entity;
use Granam\Strict\Object\StrictObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class PersonSkill extends StrictObject implements Entity
{
    /**
     * @var int
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue(strategy="AUTO")
     **/
    private $id;

    /**
     * @param PersonSkillRank $personSkillRank
     * @throws \DrdPlus\Person\Skills\Exceptions\UnexpectedRankValue
     */
    protected function addTypeVerifiedSkillRank(PersonSkillRank $personSkillRank)
    {
        $this->guardSkillRankSequence($personSkillRank);
        $this->getSkillRanks()->offsetSet($personSkillRank->getValue(), $personSkillRank);
    }

    /**
     * @param PersonSkillRank $personSkillRank
     * @throws \DrdPlus\Person\Skills\Exceptions\UnexpectedRankValue
     */
    private function guardSkillRankSequence(PersonSkillRank $personSkillRank)
    {
        if (($this->getMaxSkillRankValue() + 1) !== $personSkillRank->getValue()) {
            throw new Exceptions\UnexpectedRankValue(
                'New skill rank has to follow rank sequence, expected '
                . ($this->getMaxSkillRankValue() + 1) . ", got {$personSkillRank->getValue()}"
            );
        }
    }

    /**
     * @return int
     */
    private function getMaxSkillRankValue()
    {
        if (!$this->getCurrentSkillRank()) {
            return 0;
        }

        return $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PersonSkillRank[]|ArrayCollection
     */
    abstract public function getSkillRanks();

    /**
     * @return PersonSkillRank|false
     */
    public function getCurrentSkillRank()
    {
        return $this->getSkillRanks()->last();
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string[]
     */
    abstract public function getRelatedPropertyCodes();

    /**
     * @return bool
     */
    abstract public function isPhysical();

    /**
     * @return bool
     */
    abstract public function isPsychical();

    /**
     * @return bool
     */
    abstract public function isCombined();

}
