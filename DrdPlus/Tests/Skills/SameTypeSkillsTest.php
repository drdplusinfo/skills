<?php
namespace DrdPlus\Tests\Skills;

use DrdPlus\Codes\SkillCode;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\Combined\CombinedSkill;
use DrdPlus\Skills\SameTypeSkills;
use DrdPlus\Skills\Skill;
use DrdPlus\Skills\SkillPoint;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\Psychical\PsychicalSkillPoint;
use Granam\Tests\Tools\TestWithMockery;

abstract class SameTypeSkillsTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $sutClass = $this->getSutClass();
        /** @var SameTypeSkills $sut */
        $sut = new $sutClass();
        self::assertSame(0, $sut->count());
        self::assertSame(0, $sut->getFirstLevelSkillRankSummary());
        self::assertSame(0, $sut->getNextLevelsSkillRankSummary());
        self::assertSame($this->getExpectedSkillsTypeName(), $sut->getSkillsGroupName());
        self::assertNull($sut->getId());
        self::assertEquals([], $sut->getIterator()->getArrayCopy());
    }

    /**
     * @return SameTypeSkills
     */
    protected function getSutClass()
    {
        return preg_replace('~[\\\]Tests([\\\].+)Test$~', '$1', static::class);
    }

    protected function getExpectedSkillsTypeName()
    {
        $sutClass = $this->getSutClass();
        self::assertSame(1, preg_match('~[\\\]?(?<groupName>\w+)Skills$~', $sutClass, $matches));

        return strtolower($matches['groupName']);
    }

    /**
     * @test
     * @dataProvider provideSkill
     * @param Skill $personSkill
     */
    public function I_can_add_new_skill(Skill $personSkill)
    {
        $sutClass = $this->getSutClass();
        /** @var SameTypeSkills $sut */
        $sut = new $sutClass();
        self::assertSame(0, $sut->getFirstLevelSkillRankSummary());
        self::assertSame(0, $sut->getNextLevelsSkillRankSummary());

        $addSkill = $this->getSkillAdderName();
        $sut->$addSkill($personSkill);
        self::assertSame(
            $this->getSameTypeSkillCodesExcept($personSkill->getName()),
            $sut->getCodesOfNotLearnedSameTypeSkills()
        );
        self::assertCount(1, $sut, 'Skill has not been included on count');
        $collected = [];
        foreach ($sut as $placedSkill) {
            $collected[] = $placedSkill;
        }
        self::assertSame([$personSkill], $collected, 'Skill has not been fetched by iteration');
        $skillGetter = $this->getSkillGetter($personSkill);
        self::assertSame($personSkill, $sut->$skillGetter());
        self::assertSame(
            1 + 2 /* first and second rank have been get on first level, see provider */,
            $sut->getFirstLevelSkillRankSummary(),
            'First level skill rank summary does not match with expected'
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
        $type = preg_replace('~.*[\\\](\w+)Skills$~', '$1', $this->getSutClass());
        $sameTypeGetter = "get{$type}SkillCodes";
        $skillCodeNamespace = (new \ReflectionClass(SkillCode::class))->getNamespaceName();
        $skillTypeCodeClass = "{$skillCodeNamespace}\\{$type}SkillCode";

        return $skillTypeCodeClass::$sameTypeGetter();
    }

    /**
     * @return array|Skill[]
     */
    public function provideSkill()
    {
        $skillClasses = $this->getSkillClasses();
        $personSkills = [];
        foreach ($skillClasses as $skillClass) {
            /** @var Skill|CombinedSkill $personSkill */
            $personSkill = new $skillClass($professionLevel = $this->createProfessionFirstLevel());
            $personSkill->addSkillRank($this->createSkillPoint($professionLevel));
            $personSkill->addSkillRank($this->createSkillPoint($professionLevel));
            $personSkill->addSkillRank($this->createSkillPoint($this->createProfessionNextLevel()));
            $personSkills[] = [$personSkill];
        }

        return $personSkills;
    }

    /**
     * @return array|Skill[]|string[]
     */
    protected function getSkillClasses()
    {
        $namespace = $this->getNamespace();
        $fileBaseNames = $this->getFileBaseNames($namespace);
        $sutClassNames = array_map(
            function ($fileBasename) use ($namespace) {
                $classBasename = preg_replace('~(\w+)\.\w+~', '$1', $fileBasename);
                $className = $namespace . '\\' . $classBasename;
                if (!is_a($className, Skill::class, true)
                    || (new \ReflectionClass($className))->isAbstract()
                ) {
                    return false;
                }

                return $className;
            },
            $fileBaseNames
        );

        return array_filter(
            $sutClassNames,
            function ($sutClassName) {
                return $sutClassName !== false;
            }
        );
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
     * @param ProfessionLevel $professionLevel
     * @return \Mockery\MockInterface|SkillPoint|CombinedSkillPoint|PhysicalSkillPoint|PsychicalSkillPoint
     */
    private function createSkillPoint(ProfessionLevel $professionLevel)
    {
        $skillPointClass = $this->mockery($this->getSkillPointClass());
        $skillPointClass->shouldReceive('getProfessionLevel')
            ->andReturn($professionLevel);

        return $skillPointClass;
    }

    /**
     * @return string
     */
    private function getSkillPointClass()
    {
        $baseClass = SkillPoint::class;
        $typeName = preg_quote(ucfirst($this->getExpectedSkillsTypeName()));
        $class = preg_replace(
            '~[\\\]SkillPoint$~',
            '\\' . $typeName . '\\' . $typeName . 'SkillPoint',
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
         * @see \DrdPlus\Skills\Combined\CombinedSkills::addCombinedSkill
         * @see \DrdPlus\Skills\Physical\PhysicalSkills::addPhysicalSkill
         * @see \DrdPlus\Skills\Psychical\PsychicalSkills::addPsychicalSkill
         */
        return 'add' . ucfirst($groupName) . 'Skill';
    }

    protected function getSkillGetter(Skill $personSkill)
    {
        $class = get_class($personSkill);
        self::assertSame(1, preg_match('~[\\\](?<basename>\w+)$~', $class, $matches));

        return 'get' . $matches['basename'];
    }

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    protected function createProfessionFirstLevel()
    {
        $professionFirstLevel = $this->mockery(ProfessionFirstLevel::class);
        $professionFirstLevel->shouldReceive('isFirstLevel')
            ->andReturn(true);
        $professionFirstLevel->shouldReceive('isNextLevel')
            ->andReturn(false);

        return $professionFirstLevel;
    }

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    protected function createProfessionNextLevel()
    {
        $professionFirstLevel = $this->mockery(ProfessionFirstLevel::class);
        $professionFirstLevel->shouldReceive('isFirstLevel')
            ->andReturn(false);
        $professionFirstLevel->shouldReceive('isNextLevel')
            ->andReturn(true);

        return $professionFirstLevel;
    }

    /**
     * @test
     * @dataProvider provideSkill
     * @param Skill $personSkill
     * @expectedException \DrdPlus\Skills\Exceptions\SkillAlreadySet
     */
    public function I_can_not_replace_skill(Skill $personSkill)
    {
        $sutClass = $this->getSutClass();
        /** @var SameTypeSkills $sut */
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

    /**
     * @test
     */
    public function I_can_iterate_through_all_skills()
    {
        $sutClass = $this->getSutClass();
        /** @var SameTypeSkills $skills */
        $skills = new $sutClass();
        self::assertCount(0, $skills);
        $collected = [];
        foreach ($skills as $skill) {
            $collected[] = $skill;
        }
        self::assertSame([], $collected);
    }
}