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
        $sut = new $sutClass($personSkillRank = $this->createPersonSkillRank());
        $this->assertEquals([1 => $personSkillRank], $sut->getSkillRanks());

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
     * @param int $value
     * @return \Mockery\MockInterface|PersonSkillRank
     */
    protected function createPersonSkillRank($value = 1)
    {
        $personSkillRank = $this->mockery(PersonSkillRank::class);
        $personSkillRank->shouldReceive('getValue')
            ->andReturn($value);

        return $personSkillRank;
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
        $name = strtolower($underscored);

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
        $this->assertEquals($this->getExpectedRelatedPropertyCodes(), $personSkill->getRelatedPropertyCodes());
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
        $sut = new DeAbstractedPersonSkill($skillRank = $this->createPersonSkillRank($rankValue = 1));
        $this->assertSame([$rankValue => $skillRank], $sut->getSkillRanks());
        $sut->addSkillRank($nextRank = $this->createPersonSkillRank($nextRankValue = 2));
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
        $sut = new DeAbstractedPersonSkill($skillRank = $this->createPersonSkillRank());
        foreach ($sequence as $rankValue) {
            $sut->addSkillRank($this->createPersonSkillRank($rankValue));
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

/** inner */
class DeAbstractedPersonSkill extends PersonSkill
{
    public function getName()
    {
        return 'foo';
    }

    public function getRelatedPropertyCodes()
    {
        return [];
    }

    public function isPhysical()
    {
        return false;
    }

    public function isPsychical()
    {
        return false;
    }

    public function isCombined()
    {
        return false;
    }

}
