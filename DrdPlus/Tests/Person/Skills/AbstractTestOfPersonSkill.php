<?php
namespace DrdPlus\Tests\Person\Skills;

use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Person\Skills\PersonSkillRank;
use DrdPlus\Tests\Tools\TestWithMockery;

abstract class AbstractTestOfPersonSkill extends TestWithMockery
{
    /**
     * @test
     * @dataProvider provideSutClass
     * @param string $sutClass
     */
    public function I_can_use_it($sutClass)
    {
        /** @var PersonSkill $sut */
        $sut = new $sutClass($personSkillRank = $this->createPersonSkillRank($sutClass));
        $this->assertEquals([1 => $personSkillRank], $sut->getSkillRanks());
        $this->assertNull($sut->getId());

        $this->I_can_get_its_name($sut);

        $this->I_can_get_related_property_codes($sut);

        $this->I_can_ask_it_which_type_is_it($sut);
    }

    public function provideSutClass()
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

                return [$className];
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
     * @param string $sutClass
     * @param int $value
     * @return \Mockery\MockInterface|PersonSkillRank
     */
    protected function createPersonSkillRank($sutClass, $value = 1)
    {
        $personSkillRank = $this->mockery($this->getPersonSkillRankClass($sutClass));
        $personSkillRank->shouldReceive('getValue')
            ->andReturn($value);

        return $personSkillRank;
    }

    private function getPersonSkillRankClass($sutClass)
    {
        $baseClass = PersonSkillRank::class;
        $typeName = preg_quote(ucfirst($this->getTypeName($sutClass)));
        $class = preg_replace(
            '~[\\\]PersonSkillRank$~',
            '\\' . $typeName . '\\' . $typeName . 'SkillRank',
            $baseClass
        );

        return $class;
    }

    private function getTypeName($sutClass)
    {
        preg_match('~[\\\](?<baseNamespace>\w+)[\\\]\w+$~', $sutClass, $matches);
        $this->assertNotEmpty($matches['baseNamespace']);

        return $matches['baseNamespace'];
    }

    /**
     * @param string $sutClass
     * @return string
     */
    protected function getExpectedSkillName($sutClass)
    {
        preg_match('~[\\\](?<basename>\w+)$~', $sutClass, $matches);
        $sutBasename = $matches['basename'];
        $underscored = preg_replace('~([a-z])([A-Z])~', '$1_$2', $sutBasename);
        $underscoredSingleLetters = preg_replace('~([A-Z])([A-Z])~', '$1_$2', $underscored);
        $name = strtolower($underscoredSingleLetters);

        return $name;
    }

    protected function I_can_get_its_name(PersonSkill $personSkill)
    {
        $expectedSkillName = $this->getExpectedSkillName(get_class($personSkill));
        $this->assertSame($expectedSkillName, $personSkill->getName());
        $constantName = $this->getConstantName($expectedSkillName);
        $this->assertTrue(defined(get_class($personSkill) . '::' . $constantName));
        $reflection = new \ReflectionClass($personSkill);
        $this->assertSame($expectedSkillName, $reflection->getConstant($constantName));
    }

    protected function getConstantName($skillName)
    {
        return strtoupper($skillName);
    }

    protected function I_can_get_related_property_codes(PersonSkill $personSkill)
    {
        $this->assertEquals(
            $this->sort($this->getExpectedRelatedPropertyCodes()),
            $this->sort($personSkill->getRelatedPropertyCodes())
        );
    }

    private function sort(array $values)
    {
        sort($values);

        return $values;
    }

    /**
     * @return string[]|array
     */
    abstract protected function getExpectedRelatedPropertyCodes();

    protected function I_can_ask_it_which_type_is_it(PersonSkill $personSkill)
    {
        // should be only one type
        $this->assertSame(1, $this->isPhysical() + $this->isPsychical() + $this->isCombined());
        $this->assertSame($this->isPhysical(), $personSkill->isPhysical());
        $this->assertSame($this->isPsychical(), $personSkill->isPsychical());
        $this->assertSame($this->isCombined(), $personSkill->isCombined());
    }

    /**
     * @return bool
     */
    abstract protected function isCombined();

    /**
     * @return bool
     */
    abstract protected function isPhysical();

    /**
     * @return bool
     */
    abstract protected function isPsychical();

    /**
     * @test
     */
    public function I_can_add_more_ranks()
    {
        $sutClass = current($this->provideSutClass())[0]; // one is enough of this test
        /** @var PersonSkill $sut */
        $sut = new $sutClass($skillRank = $this->createPersonSkillRank($sutClass, $rankValue = 1));
        $this->assertSame([$rankValue => $skillRank], $sut->getSkillRanks());
        $addTypeSkillRank = 'add' . $this->getTypeName($sutClass) . 'SkillRank';
        $sut->$addTypeSkillRank($nextRank = $this->createPersonSkillRank($sutClass, $nextRankValue = 2));
        $this->assertSame([$rankValue => $skillRank, $nextRankValue => $nextRank], $sut->getSkillRanks());
    }

    /**
     * @test
     * @dataProvider provideInvalidSequence
     * @expectedException \DrdPlus\Person\Skills\Exceptions\UnexpectedRankValue
     * @param array|int[] $sequence
     */
    public function I_can_not_add_rank_with_invalid_sequence(array $sequence)
    {
        $sutClass = current($this->provideSutClass())[0]; // one is enough of this test
        $sut = new $sutClass($skillRank = $this->createPersonSkillRank($sutClass));
        $addTypeSkillRank = 'add' . $this->getTypeName($sutClass) . 'SkillRank';
        foreach ($sequence as $rankValue) {
            $sut->$addTypeSkillRank($this->createPersonSkillRank($sutClass, $rankValue));
        }
    }

    public function provideInvalidSequence()
    {
        return [
            [[1]], // same rank
            [[0]], // lower rank
            [[3]], // skipped second rank
        ];
    }
}
