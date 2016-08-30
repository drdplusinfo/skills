<?php
namespace DrdPlus\Skills;

use Granam\Strict\Object\StrictObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class SameTypeSkills extends StrictObject implements \IteratorAggregate, \Countable
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
     * @return \ArrayIterator|Skill[]
     */
    abstract public function getIterator();

    /**
     * @return int
     */
    public function getFirstLevelSkillRankSummary()
    {
        return (int)array_sum(
            array_map(
                function (SkillRank $skillRank) {
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
            function (SkillRank $skillRank) {
                return $skillRank->getProfessionLevel()->isFirstLevel();
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
                function (SkillRank $skillRank) {
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
            function (SkillRank $skillRank) {
                return $skillRank->getProfessionLevel()->isNextLevel();
            }
        );
    }

    /**
     * @return array|SkillRank[]
     */
    private function getSkillRanks()
    {
        $skillRanks = [];
        foreach ($this->getIterator() as $skill) {
            foreach ($skill->getSkillRanks() as $skillRank) {
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
