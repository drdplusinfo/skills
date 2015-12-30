<?php
namespace DrdPlus\Tests\Person\Skills;

use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\PersonSameTypeSkills;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Person\Skills\PersonSkillRank;
use DrdPlus\Tests\Tools\TestWithMockery;

abstract class AbstractTestOfPersonSameTypeSkills extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $sutClass = $this->getSutClass();
        /** @var PersonSameTypeSkills $sut */
        $sut = new $sutClass();
        $this->assertSame(0, $sut->count());
        $this->assertSame(0, $sut->getFirstLevelSkillRankSummary());
        $this->assertSame(0, $sut->getNextLevelsSkillRankSummary());
        $this->assertSame($this->getExpectedSkillsGroupName(), $sut->getSkillsGroupName());
        $this->assertNull($sut->getId());
        $this->assertEquals([], $sut->getIterator()->getArrayCopy());
        foreach ($sut as $skill) {
            $this->assertNull($skill);
        }
    }

    /**
     * @return PersonSameTypeSkills
     */
    protected function getSutClass()
    {
        return preg_replace('~[\\\]Tests([\\\].+)Test$~', '$1', static::class);
    }

    protected function getExpectedSkillsGroupName()
    {
        $sutClass = $this->getSutClass();
        $this->assertSame(1, preg_match('~[\\\]?Person(?<groupName>\w+)Skills$~', $sutClass, $matches));
        $groupName = strtolower($matches['groupName']);

        return $groupName;
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
        $addSkill = $this->getAdderName();
        $sut->$addSkill($personSkill);
        foreach ($sut as $index => $placedPersonSkill) {
            $this->assertSame($personSkill, $placedPersonSkill);
        }
        $getSkill = $this->getSkillGetter($personSkill);
        $this->assertSame($personSkill, $sut->$getSkill());
        $this->assertSame(1 + 2, $sut->getFirstLevelSkillRankSummary());
        $this->assertSame(3, $sut->getNextLevelsSkillRankSummary());
    }

    /**
     * @return array|PersonSkill[]
     */
    public function providePersonSkill()
    {
        $personSkillClasses = $this->getPersonSkillClasses();
        $personSkills = [];
        foreach ($personSkillClasses as $personSkillClass) {
            /** @var PersonSkill $personSkill */
            $personSkill = new $personSkillClass($this->createPersonSkillRank(1, true)); // first level
            $personSkill->addSkillRank($this->createPersonSkillRank(2, true)); // first level
            $personSkill->addSkillRank($this->createPersonSkillRank(3, false)); // next level
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
     * @return \Mockery\MockInterface|PersonSkillRank
     */
    private function createPersonSkillRank($skillRankValue, $isFirstLevel)
    {
        $personSkillRank = $this->mockery(PersonSkillRank::class);
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

    /**
     * @return string
     */
    protected function getAdderName()
    {
        $groupName = $this->getExpectedSkillsGroupName();

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
        $this->assertSame(1, preg_match('~[\\\](?<basename>\w+)$~', $class, $matches));
        $getterName = 'get' . $matches['basename'];

        return $getterName;
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
        $addSkill = $this->getAdderName();
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
