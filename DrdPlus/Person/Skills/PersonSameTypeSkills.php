<?php
namespace DrdPlus\Person\Skills;

use Granam\Strict\Object\StrictObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class PersonSameTypeSkills extends StrictObject implements \IteratorAggregate, \Countable
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue(strategy="AUTO")p
     */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    abstract public function getSkillsGroupName();

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
    private function getSkillRanks()
    {
        $skillRanks = [];
        foreach ($this->getIterator() as $personSkill) {
            foreach ($personSkill->getSkillRanks() as $skillRank) {
                $skillRanks[] = $skillRank;
            }
        }

        return $skillRanks;
    }

    /**
     * @param int $firstLevelPropertiesSum as a potential of skill points
     * @return int
     */
    protected function getUnusedFirstLevelSkillPointsValue($firstLevelPropertiesSum)
    {
        return $firstLevelPropertiesSum - $this->getFirstLevelSkillRankSummary();
    }

    /**
     * @param int $nextLevelsPropertiesSum as a potential of skill points
     * @return int
     */
    protected function getUnusedNextLevelsSkillPointsValue($nextLevelsPropertiesSum)
    {
        return $nextLevelsPropertiesSum - $this->getNextLevelsSkillRankSummary();
    }

    public function count()
    {
        return $this->getIterator()->count();
    }

    /**
     * @return array|string[]
     */
    public function getCodesOfNotLearnedSameTypeSkills()
    {
        $namesOfKnownSkills = [];
        foreach ($this->getIterator() as $skill) {
            if ($skill->getCurrentSkillRank()->getValue() > 0) {
                $namesOfKnownSkills[] = $skill->getName();
            }
        }

        return array_diff($this->getCodesOfAllSameTypeSkills(), $namesOfKnownSkills);
    }

    /**
     * @return string[]|array
     */
    abstract public function getCodesOfAllSameTypeSkills();
}
