<?php
namespace DrdPlus\Person\Skills;

use Granam\Strict\Object\StrictObject;

abstract class PersonSkill extends StrictObject
{
    /**
     * @var PersonSkillRank[]
     *
     * @ORM\OneToMany(targetEntity="PersonSkillRank")
     */
    protected $skillRanks = [];

    public function __construct(PersonSkillRank $skillRank)
    {
        $this->addSkillRank($skillRank);
    }

    public function addSkillRank(PersonSkillRank $skillRank)
    {
        if (!(count($this->getSkillRanks()) === 0 && $skillRank->getValue() === 0)
            && (($this->getMaxSkillRankValue() + 1) !== $skillRank->getValue())
        ) {
            throw new \LogicException(
                "New skill rank has to follow ranks sequence, expected "
                . (count($this->getSkillRanks()) === 0
                    ? '0'
                    : ($this->getMaxSkillRankValue() + 1))
                . ", got {$skillRank->getValue()}"
            );
        }

        $this->skillRanks[$skillRank->getValue()] = $skillRank;
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
            $this->getSkillRanks()
        );
    }

    /**
     * @return PersonSkillRank[]
     */
    public function getSkillRanks()
    {
        return $this->skillRanks;
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
