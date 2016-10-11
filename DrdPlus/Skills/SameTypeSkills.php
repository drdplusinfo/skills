<?php
namespace DrdPlus\Skills;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
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
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @param ProfessionLevel $professionLevel
     */
    public function __construct(ProfessionLevel $professionLevel)
    {
        $this->populateAllSkills($professionLevel);
    }

    /**
     * @param ProfessionLevel $professionLevel
     */
    abstract protected function populateAllSkills(ProfessionLevel $professionLevel);

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \ArrayIterator|Skill[]
     */
    abstract public function getIterator();

    /**
     * @return int
     */
    public function getFirstLevelSkillRankSummary()
    {
        $firstLevelSkillRankValues = array_map(
            function (SkillRank $skillRank) {
                return $skillRank->getValue();
            },
            $this->getFirstLevelSkillRanks()
        );

        return (int)array_sum($firstLevelSkillRankValues);
    }

    /**
     * @return array|SkillRank[]
     */
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

    /**
     * @return array|SkillRank[]
     */
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

    /**
     * @return int
     */
    public function count()
    {
        return $this->getIterator()->count();
    }
}