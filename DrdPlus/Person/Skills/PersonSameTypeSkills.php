<?php
namespace DrdPlus\Person\Skills;

use Granam\Strict\Object\StrictObject;

abstract class PersonSameTypeSkills extends StrictObject implements \Iterator, \Countable
{

    /** @var \ArrayIterator */
    protected $skillsIterator;

    /**
     * @return string
     */
    abstract public function getSkillsGroupName();

    /** @return int|null */
    abstract public function getId();

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
     * @return \ArrayIterator
     */
    protected function getSkillsIterator()
    {
        if (!isset($this->skillsIterator)) {
            $this->skillsIterator = $this->createSkillsIterator();
        }

        return $this->skillsIterator;
    }

    /** @return \ArrayIterator */
    abstract protected function createSkillsIterator();

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
        $skillRanksPerSkill = array_map(
            function (PersonSkill $personSkill) {
                return $personSkill->getSkillRanks();
            },
            $this->toArray()
        );

        $skillRanks = [];
        foreach ($skillRanksPerSkill as $skillRanksOfSingleSkill) {
            $skillRanks = array_merge($skillRanks, $skillRanksOfSingleSkill);
        }

        return $skillRanks;
    }

    /**
     * @return PersonSkill[]|array
     */
    public function toArray()
    {
        return $this->getSkillsIterator()->getArrayCopy();
    }

    public function current()
    {
        return $this->getSkillsIterator()->current();
    }

    public function next()
    {
        $this->getSkillsIterator()->next();
    }

    public function key()
    {
        return $this->getSkillsIterator()->key();
    }

    public function valid()
    {
        return $this->getSkillsIterator()->valid();
    }

    public function rewind()
    {
        $this->getSkillsIterator()->rewind();
    }

    public function count()
    {
        return $this->getSkillsIterator()->count();
    }
}
