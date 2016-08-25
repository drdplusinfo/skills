<?php
namespace DrdPlus\Tests\Person\Skills;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\Skills\Combined\CombinedSkillRank;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkill;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Person\Skills\PersonSkillRank;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkill;
use DrdPlus\Person\Skills\Physical\PhysicalSkillRank;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkill;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillRank;
use DrdPlus\Tests\Person\Skills\Physical\PersonPhysicalSkillsTest;
use DrdPlus\Tests\Person\Skills\Psychical\PersonPsychicalSkillsTest;
use Granam\Tests\Tools\TestWithMockery;

abstract class PersonSkillTest extends TestWithMockery
{
    /**
     * @test
     * @dataProvider provideSutClass
     * @param string $sutClass
     */
    public function I_can_use_it($sutClass)
    {
        /** @var PersonSkill|PersonPhysicalSkill|PersonPsychicalSkill|PersonCombinedSkill $sut */
        $sut = new $sutClass($this->createProfessionFirstLevel());
        self::assertCount(1, $sut->getSkillRanks());
        $veryFirstSkillRanks = $sut->getSkillRanks()->toArray();
        self::assertSame([0], array_keys($veryFirstSkillRanks));
        self::assertInstanceOf($this->getPersonSkillRankClass($sutClass), $veryFirstSkillRanks[0]);
        $sut->addSkillRank($personSkillRank = $this->createPersonSkillRank($sut, $sutClass));
        self::assertCount(2, $sut->getSkillRanks());
        self::assertSame([0, 1], $sut->getSkillRanks()->getKeys());
        self::assertSame([0 => $veryFirstSkillRanks[0], 1 => $personSkillRank], $sut->getSkillRanks()->toArray());
        self::assertSame($personSkillRank, $sut->getCurrentSkillRank());
        self::assertNull($sut->getId());

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

        return array_values(array_filter($sutClassNames));
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
     * @param PersonSkill $personSkill
     * @param string $personSkillClass
     * @param int $value
     * @return \Mockery\MockInterface|PersonSkillRank|PsychicalSkillRank|PhysicalSkillRank|CombinedSkillRank
     */
    protected function createPersonSkillRank(PersonSkill $personSkill, $personSkillClass, $value = 1)
    {
        $personSkillRank = $this->mockery($this->getPersonSkillRankClass($personSkillClass));
        $personSkillRank->shouldReceive('getPersonSkill')
            ->andReturn($personSkill);
        $personSkillRank->shouldReceive('getValue')
            ->andReturn($value);

        return $personSkillRank;
    }

    /**
     * @param $sutClass
     * @return string|PersonSkillRank
     */
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
        self::assertNotEmpty($matches['baseNamespace']);

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

        return strtolower($underscoredSingleLetters);
    }

    protected function I_can_get_its_name(PersonSkill $personSkill)
    {
        $expectedSkillName = $this->getExpectedSkillName(get_class($personSkill));
        self::assertSame($expectedSkillName, $personSkill->getName());
        $constantName = $this->getConstantName($expectedSkillName);
        self::assertTrue(defined(get_class($personSkill) . '::' . $constantName));
        $reflection = new \ReflectionClass($personSkill);
        self::assertSame($expectedSkillName, $reflection->getConstant($constantName));
    }

    protected function getConstantName($skillName)
    {
        return strtoupper($skillName);
    }

    protected function I_can_get_related_property_codes(PersonSkill $personSkill)
    {
        self::assertEquals(
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
        self::assertSame(1, $this->shouldBePhysical() + $this->shouldBePsychical() + $this->shouldBeCombined());
        self::assertSame($this->shouldBePhysical(), $personSkill->isPhysical());
        self::assertSame($this->shouldBePsychical(), $personSkill->isPsychical());
        self::assertSame($this->shouldBeCombined(), $personSkill->isCombined());
    }

    /**
     * @return bool
     */
    protected function shouldBeCombined()
    {
        return strpos(static::class, 'Combined') !== false;
    }

    /**
     * @return bool
     */
    protected function shouldBePhysical()
    {
        return strpos(static::class, 'Physical') !== false;
    }

    /**
     * @return bool
     */
    protected function shouldBePsychical()
    {
        return strpos(static::class, 'Psychical') !== false;
    }

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    protected function createProfessionFirstLevel()
    {
        return $this->mockery(ProfessionFirstLevel::class);
    }

    /**
     * @test
     */
    public function I_can_add_more_ranks()
    {
        $sutClass = current($this->provideSutClass())[0]; // one is enough of this test
        /** @var PersonSkill|PersonPhysicalSkill|PersonPsychicalSkill|PersonCombinedSkill $sut */
        $sut = new $sutClass($this->createProfessionFirstLevel());
        self::assertCount(1, $sut->getSkillRanks());
        self::assertInstanceOf($this->getPersonSkillRankClass($sutClass), $zeroSkillRank = $sut->getCurrentSkillRank());

        $sut->addSkillRank($firstSkillRank = $this->createPersonSkillRank($sut, $sutClass, 1));
        self::assertSame([0 => $zeroSkillRank, 1 => $firstSkillRank], $sut->getSkillRanks()->toArray());
        self::assertSame($firstSkillRank, $sut->getCurrentSkillRank());
        $sut->addSkillRank($nextSkillRank = $this->createPersonSkillRank($sut, $sutClass, 2));
        self::assertSame(
            [0 => $zeroSkillRank, 1 => $firstSkillRank, 2 => $nextSkillRank],
            $sut->getSkillRanks()->toArray()
        );
        self::assertSame($nextSkillRank, $sut->getCurrentSkillRank());
    }

    /**
     * @test
     * @dataProvider provideInvalidSequence
     * @expectedException \DrdPlus\Person\Skills\Exceptions\UnexpectedRankValue
     *
     * @param array|int[] $invalidRank
     */
    public function I_can_not_add_rank_with_invalid_sequence($invalidRank)
    {
        $sutClass = current($this->provideSutClass())[0]; // one is enough of this test
        /** @var PersonSkill|PersonCombinedSkill|PersonPhysicalSkillsTest|PersonPsychicalSkillsTest $sut */
        $sut = new $sutClass($this->createProfessionFirstLevel());
        self::assertCount(1, $sut->getSkillRanks());
        $zeroSkillRank = $sut->getCurrentSkillRank();
        self::assertInstanceOf($this->getPersonSkillRankClass($sutClass), $zeroSkillRank);
        $sut->addSkillRank($firstSkillRank = $this->createPersonSkillRank($sut, $sutClass));
        self::assertSame([0 => $zeroSkillRank, 1 => $firstSkillRank], $sut->getSkillRanks()->toArray());
        $sut->addSkillRank($this->createPersonSkillRank($sut, $sutClass, $invalidRank));
    }

    public function provideInvalidSequence()
    {
        return [
            [1], // same rank
            [0], // lower rank
            [3], // skipped second rank
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     * @expectedExceptionMessageRegExp ~belongs to different skill class~
     */
    public function I_can_not_add_rank_linked_with_another_skill()
    {
        $sutClass = current($this->provideSutClass())[0];
        /** @var PersonSkill|PersonPhysicalSkill|PersonPsychicalSkill|PersonCombinedSkill $sut */
        $sut = new $sutClass($this->createProfessionFirstLevel());
        $anotherSutClass = $this->provideSutClass()[1][0];
        /** @var PersonSkill|PersonPhysicalSkill|PersonPsychicalSkill|PersonCombinedSkill $anotherSut */
        $anotherSut = new $anotherSutClass($this->createProfessionFirstLevel());
        $sut->addSkillRank($firstSkillRank = $this->createPersonSkillRank($anotherSut, $sutClass, 1));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     * @expectedExceptionMessageRegExp ~belongs to different instance~
     */
    public function I_can_not_add_rank_linked_with_same_skill_but_different_instance()
    {
        $sutClass = current($this->provideSutClass())[0];
        /** @var PersonSkill|PersonPhysicalSkill|PersonPsychicalSkill|PersonCombinedSkill $sut */
        $sut = new $sutClass($this->createProfessionFirstLevel());
        /** @var PersonSkill|PersonPhysicalSkill|PersonPsychicalSkill|PersonCombinedSkill $anotherInstance */
        $anotherInstance = new $sutClass($this->createProfessionFirstLevel());
        $sut->addSkillRank($firstSkillRank = $this->createPersonSkillRank($anotherInstance, $sutClass, 1));
    }
}