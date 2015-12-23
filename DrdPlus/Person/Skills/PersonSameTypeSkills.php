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
                $this->findNextLevelSkillRanks($this->getSkillsIterator()->getArrayCopy())
            )
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

    protected function findNextLevelSkillRanks(array $skillRanks)
    {
        return array_filter(
            $skillRanks,
            function (PersonSkillRank $skillRank) {
                return $skillRank->getProfessionLevel()->isNextLevel();
            }
        );
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
