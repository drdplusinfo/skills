<?php
namespace DrdPlus\Tests\Person\Skills;

use DrdPlus\Codes\SkillCodes;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\Combined\CombinedSkillRank;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkill;
use DrdPlus\Person\Skills\PersonSameTypeSkills;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Person\Skills\PersonSkillRank;
use DrdPlus\Person\Skills\Physical\PhysicalSkillRank;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillRank;
use Granam\Tests\Tools\TestWithMockery;

abstract class PersonSameTypeSkillsTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $sutClass = $this->getSutClass();
        /** @var PersonSameTypeSkills $sut */
        $sut = new $sutClass();
        self::assertSame(0, $sut->count());
        self::assertSame(0, $sut->getFirstLevelSkillRankSummary());
        self::assertSame(0, $sut->getNextLevelsSkillRankSummary());
        self::assertSame($this->getExpectedSkillsTypeName(), $sut->getSkillsGroupName());
        self::assertNull($sut->getId());
        self::assertEquals([], $sut->getIterator()->getArrayCopy());
    }

    /**
     * @return PersonSameTypeSkills
     */
    protected function getSutClass()
    {
        return preg_replace('~[\\\]Tests([\\\].+)Test$~', '$1', static::class);
    }

    protected function getExpectedSkillsTypeName()
    {
        $sutClass = $this->getSutClass();
        self::assertSame(1, preg_match('~[\\\]?Person(?<groupName>\w+)Skills$~', $sutClass, $matches));

        return strtolower($matches['groupName']);
    }

    /**
     * @test
     * @dataProvider providePersonSkill
     * @param PersonSkill $personSkill
     */
    public function I_can_add_new_skill(PersonSkill $personSkill)
    {
        $sutClass = $this->getSutClass();
        /** @var PersonSameTypeSkills $sut */
        $sut = new $sutClass();
        self::assertSame(0, $sut->getFirstLevelSkillRankSummary());
        self::assertSame(0, $sut->getNextLevelsSkillRankSummary());

        $addSkill = $this->getSkillAdderName();
        $sut->$addSkill($personSkill);
        self::assertSame(
            $this->getSameTypeSkillCodesExcept($personSkill->getName()),
            $sut->getCodesOfNotLearnedSameTypeSkills()
        );
        foreach ($sut as $index => $placedPersonSkill) {
            self::assertSame($personSkill, $placedPersonSkill);
        }
        $skillGetter = $this->getSkillGetter($personSkill);
        self::assertSame($personSkill, $sut->$skillGetter());
        self::assertSame(
            1 + 2 /* first and second rank have been get on first level, see provider */,
            $sut->getFirstLevelSkillRankSummary()
        );
        self::assertSame(
            3 /* maximal skill rank has been get on second level, see provider */,
            $sut->getNextLevelsSkillRankSummary()
        );
    }

    protected function getSameTypeSkillCodesExcept($except)
    {
        return array_diff($this->getSameTypeSkillCodes(), [$except]);
    }

    protected function getSameTypeSkillCodes()
    {
        $type = preg_replace('~.*Person(\w+)Skills$~', '$1', $this->getSutClass());
        $sameTypeGetter = "get{$type}SkillCodes";

        return SkillCodes::$sameTypeGetter();
    }

    /**
     * @return array|PersonSkill[]
     */
    public function providePersonSkill()
    {
        $personSkillClasses = $this->getPersonSkillClasses();
        $personSkills = [];
        foreach ($personSkillClasses as $personSkillClass) {
            /** @var PersonSkill|PersonCombinedSkill $personSkill */
            $personSkill = new $personSkillClass();
            $personSkill->addSkillRank($this->createPersonSkillRank(1, true /* from first level */));
            $personSkill->addSkillRank($this->createPersonSkillRank(2, true /* from first level */));
            $personSkill->addSkillRank($this->createPersonSkillRank(3, false /* from next level */));
            $personSkills[] = [$personSkill];
        }

        return $personSkills;
    }

    /**
     * @return array|PersonSkill[]|string[]
     */
    protected function getPersonSkillClasses()
    {
        $namespace = $this->getNamespace();
        $fileBaseNames = $this->getFileBaseNames($namespace);
        $sutClassNames = array_map(
            function ($fileBasename) use ($namespace) {
                $classBasename = preg_replace('~(\w+)\.\w+~', '$1', $fileBasename);
                $className = $namespace . '\\' . $classBasename;
                if (!is_a($className, PersonSkill::class, true)) {
                    return false;
                }
                $reflection = new \ReflectionClass($className);
                if ($reflection->isAbstract()) {
                    return false;
                }

                return $className;
            },
            $fileBaseNames
        );

        return array_filter($sutClassNames);
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        return preg_replace('~[\\\]Tests([\\\].+)[\\\]\w+$~', '$1', static::class);
    }

    protected function getFileBaseNames($namespace)
    {
        $sutNamespaceToDirRelativePath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        $sutDir = rtrim($this->getProjectRootDir(), DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . $sutNamespaceToDirRelativePath;
        $files = scandir($sutDir);
        $sutFiles = array_filter($files, function ($filename) {
            return $filename !== '.' && $filename !== '..';
        });

        return $sutFiles;
    }

    private function getProjectRootDir()
    {
        $namespaceAsRelativePath = str_replace('\\', DIRECTORY_SEPARATOR, __NAMESPACE__);
        $projectRootDir = preg_replace('~' . preg_quote($namespaceAsRelativePath) . '.*~', '', __DIR__);

        return $projectRootDir;
    }

    /**
     * @param int $skillRankValue
     * @param bool $isFirstLevel
     * @return \Mockery\MockInterface|CombinedSkillRank|PsychicalSkillRank|PhysicalSkillRank
     */
    private function createPersonSkillRank($skillRankValue, $isFirstLevel)
    {
        $personSkillRank = $this->mockery($this->getPersonSkillRankClass());
        $personSkillRank->shouldReceive('getValue')
            ->andReturn($skillRankValue);
        $personSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($professionLevel = $this->mockery(ProfessionLevel::class));
        $professionLevel->shouldReceive('isFirstLevel')
            ->andReturn($isFirstLevel);
        $professionLevel->shouldReceive('isNextLevel')
            ->andReturn(!$isFirstLevel);

        return $personSkillRank;
    }

    private function getPersonSkillRankClass()
    {
        $baseClass = PersonSkillRank::class;
        $typeName = preg_quote(ucfirst($this->getExpectedSkillsTypeName()));
        $class = preg_replace(
            '~[\\\]Person(SkillRank)$~',
            '\\' . $typeName . '\\' . $typeName . '$1',
            $baseClass
        );

        return $class;
    }

    /**
     * @return string
     */
    protected function getSkillAdderName()
    {
        $groupName = $this->getExpectedSkillsTypeName();

        /**
         * @see \DrdPlus\Person\Skills\Combined\PersonCombinedSkills::addCombinedSkill
         * @see \DrdPlus\Person\Skills\Physical\PersonPhysicalSkills::addPhysicalSkill
         * @see \DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills::addPsychicalSkill
         */
        return 'add' . ucfirst($groupName) . 'Skill';
    }

    protected function getSkillGetter(PersonSkill $personSkill)
    {
        $class = get_class($personSkill);
        self::assertSame(1, preg_match('~[\\\](?<basename>\w+)$~', $class, $matches));

        return 'get' . $matches['basename'];
    }

    /**
     * @test
     * @dataProvider providePersonSkill
     * @param PersonSkill $personSkill
     * @expectedException \DrdPlus\Person\Skills\Exceptions\SkillAlreadySet
     */
    public function I_can_not_replace_skill(PersonSkill $personSkill)
    {
        $sutClass = $this->getSutClass();
        /** @var PersonSameTypeSkills $sut */
        $sut = new $sutClass();
        $addSkill = $this->getSkillAdderName();
        $sut->$addSkill($personSkill);
        $sut->$addSkill($personSkill);
    }

    /**
     * @test
     */
    abstract public function I_can_not_add_unknown_skill();

    /**
     * @test
     */
    abstract public function I_can_get_unused_skill_points_from_first_level();

    /**
     * @test
     */
    abstract public function I_can_get_unused_skill_points_from_next_levels();
}