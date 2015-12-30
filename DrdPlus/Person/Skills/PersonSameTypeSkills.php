<?php
namespace DrdPlus\Person\Skills;

use Granam\Strict\Object\StrictObject;

abstract class PersonSameTypeSkills extends StrictObject implements \IteratorAggregate, \Countable
{

    /**
     * @return string
     */
    abstract public function getSkillsGroupName();

    /** @return int|null */
    abstract public function getId();

    /**
     * @return \ArrayIterator|PersonSkill[]
     */
    abstract public function getIterator();

    /**
     * @return int
     */
    public function getFirstLevelSkillRankSummary()
    {
        return (int)array_sum(
            array_map(
                function (PersonSkillRank $skillRank) {
                    return $skillRank->getValue();
                },
                $this->getFirstLevelSkillRanks()
            )
        );
    }

    protected function getFirstLevelSkillRanks()
    {
        return array_filter(
            $this->getSkillRanks(),
            function (PersonSkillRank $personSkillRank) {
                return $personSkillRank->getProfessionLevel()->isFirstLevel();
            }
        );
    }

    /**
     * @return int
     */
    public function getNextLevelsSkillRankSummary()
    {
        return (int)array_sum(
            array_map(
                function (PersonSkillRank $skillRank) {
                    return $skillRank->getValue();
                },
                $this->getNextLevelSkillRanks()
            )
        );
    }

    protected function getNextLevelSkillRanks()
    {
        return array_filter(
            $this->getSkillRanks(),
            function (PersonSkillRank $personSkillRank) {
                return $personSkillRank->getProfessionLevel()->isNextLevel();
            }
        );
    }


    /**
     * @return array|PersonSkillRank[]
     */
    protected function getSkillRanks()
    {
        $skillRanksPerSkill = [];
        foreach ($this as $personSkill) {
            /** @var PersonSkill $personSkill */
            $skillRanksPerSkill[] = $personSkill->getSkillRanks();
        }

        $skillRanks = [];
        foreach ($skillRanksPerSkill as $skillRanksOfSingleSkill) {
            $skillRanks = array_merge($skillRanks, $skillRanksOfSingleSkill);
        }

        return $skillRanks;
    }

    /**
     * @param int $firstLevelPropertiesSum as a potential of skill points
     * @return int
     */
    protected function getFreeFirstLevelSkillPointsValue($firstLevelPropertiesSum)
    {
        return $firstLevelPropertiesSum - $this->getFirstLevelSkillRankSummary();
    }

    /**
     * @param int $nextLevelsPropertiesSum as a potential of skill points
     * @return int
     */
    protected function getFreeNextLevelsSkillPointsValue($nextLevelsPropertiesSum)
    {
        return $nextLevelsPropertiesSum - $this->getNextLevelsSkillRankSummary();
    }

    public function count()
    {
        return $this->getIterator()->count();
    }
}
