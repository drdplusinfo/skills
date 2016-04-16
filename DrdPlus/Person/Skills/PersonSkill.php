<?php
namespace DrdPlus\Person\Skills;

use Doctrine\Common\Collections\ArrayCollection;
use Granam\Strict\Object\StrictObject;
use Doctrine\ORM\Mapping as ORM;

abstract class PersonSkill extends StrictObject
{
    /**
     * @var PersonSkillRank[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonSkillRank", mappedBy="personSkill", cascade={"persist"})
     */
    private $skillRanks;

    protected function __construct(PersonSkillRank $personSkillRank)
    {
        $this->skillRanks = new ArrayCollection();
        $this->addSkillRank($personSkillRank);
    }

    protected function addSkillRank(PersonSkillRank $personSkillRank)
    {
        $this->guardSkillRankSequence($personSkillRank);
        $this->skillRanks[$personSkillRank->getValue()] = $personSkillRank;
    }

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
        if (!($skillRankValues = $this->getSkillRankValues())) {
            return 0;
        }

        return max($skillRankValues);
    }

    private function getSkillRankValues()
    {
        return array_map(
            function (PersonSkillRank $skillRank) {
                return $skillRank->getValue();
            },
            $this->getSkillRanks()->toArray()
        );
    }

    /**
     * @return int
     */
    abstract public function getId();

    /**
     * @return PersonSkillRank[]|ArrayCollection
     */
    public function getSkillRanks()
    {
        return $this->skillRanks;
    }

    /**
     * @return PersonSkillRank
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
