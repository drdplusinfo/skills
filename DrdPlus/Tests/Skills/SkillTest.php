<?php
namespace DrdPlus\Tests\Skills;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\Combined\CombinedSkillRank;
use DrdPlus\Skills\Combined\CombinedSkill;
use DrdPlus\Skills\Combined\Cooking;
use DrdPlus\Skills\Skill;
use DrdPlus\Skills\SkillPoint;
use DrdPlus\Skills\SkillRank;
use DrdPlus\Skills\Physical\PhysicalSkill;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\Physical\PhysicalSkillRank;
use DrdPlus\Skills\Psychical\PsychicalSkill;
use DrdPlus\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Skills\Psychical\PsychicalSkillRank;
use Granam\Integer\PositiveIntegerObject;
use Granam\Tests\Tools\TestWithMockery;

abstract class SkillTest extends TestWithMockery
{
    /**
     * @test
     * @dataProvider provideSkillClasses
     * @param string $sutClass
     */
    public function I_can_use_it($sutClass)
    {
        /** @var Skill|PhysicalSkill|PsychicalSkill|CombinedSkill $sut */
        $sut = new $sutClass($professionLevel = $this->createProfessionFirstLevel());
        self::assertCount(1, $sut->getSkillRanks());
        $implicitSkillRanks = $sut->getSkillRanks()->toArray();
        self::assertSame([0], array_keys($implicitSkillRanks));
        self::assertInstanceOf($this->getSkillRankClass($sutClass), $implicitSkillRanks[0]);

        $sut->increaseSkillRank($skillPoint = $this->createSkillPoint());
        self::assertCount(2, $sut->getSkillRanks());
        self::assertSame([0, 1], $sut->getSkillRanks()->getKeys());
        $expectedSkillRank = 0;
        $lastSkillRank = null;
        foreach ($sut->getSkillRanks() as $skillRankValue => $lastSkillRank) {
            self::assertSame($skillRankValue, $lastSkillRank->getValue());
            self::assertSame($expectedSkillRank, $lastSkillRank->getValue());
            $expectedSkillRank++;
            self::assertSame($sut, $lastSkillRank->getSkill());
            if ($skillRankValue === 1) {
                self::assertSame($skillPoint, $lastSkillRank->getSkillPoint());
            }
        }
        self::assertSame($lastSkillRank, $sut->getCurrentSkillRank());
        self::assertNull($sut->getId());

        $this->I_can_get_its_name($sut);

        $this->I_can_get_related_property_codes($sut);

        $this->I_can_ask_it_which_type_is_it($sut);
    }

    public function provideSkillClasses()
    {
        $namespace = $this->getNamespace();
        $fileBaseNames = $this->getFileBaseNames($namespace);
        $sutClassNames = array_map(
            function ($fileBasename) use ($namespace) {
                $classBasename = preg_replace('~(\w+)\.\w+~', '$1', $fileBasename);
                $className = $namespace . '\\' . $classBasename;
                if (!is_a($className, Skill::class, true)) {
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
        $projectRootDir = preg_replace('~' . preg_quote($namespaceAsRelativePath, '~') . '.*~', '', __DIR__);

        return $projectRootDir;
    }

    /**
     * @param Skill $skill
     * @param string $skillClass
     * @param int $value
     * @return \Mockery\MockInterface|SkillRank|PsychicalSkillRank|PhysicalSkillRank|CombinedSkillRank
     */
    protected function createSkillRank(Skill $skill, $skillClass, $value = 1)
    {
        $skillRank = $this->mockery($this->getSkillRankClass($skillClass));
        $skillRank->shouldReceive('getSkill')
            ->andReturn($skill);
        $skillRank->shouldReceive('getValue')
            ->andReturn($value);

        return $skillRank;
    }

    /**
     * @param $sutClass
     * @return string|SkillRank
     */
    private function getSkillRankClass($sutClass)
    {
        $baseClass = SkillRank::class;
        $typeName = preg_quote(ucfirst($this->getTypeName($sutClass)), '~');
        $class = preg_replace(
            '~[\\\]SkillRank$~',
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
     * @param int $value
     * @return \Mockery\MockInterface|SkillPoint|CombinedSkillPoint|PhysicalSkillPoint|PsychicalSkillPoint
     */
    protected function createSkillPoint($value = 1)
    {
        $skillPoint = $this->mockery($this->getSkillPointClass());
        $skillPoint->shouldReceive('getValue')
            ->andReturn($value);

        return $skillPoint;
    }

    /**
     * @return string
     */
    private function getSkillPointClass(): string
    {
        $baseClass = SkillPoint::class;
        $typeName = preg_quote(ucfirst($this->getExpectedSkillsTypeName()), '~');
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
    private function getExpectedSkillsTypeName()
    {
        self::assertTrue((bool)preg_match('~(?<typeName>\w+)$~', $this->getNamespace(), $matches));

        return $matches['typeName'];
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

    protected function I_can_get_its_name(Skill $skill)
    {
        $expectedSkillName = $this->getExpectedSkillName(get_class($skill));
        self::assertSame($expectedSkillName, $skill->getName());
        $constantName = $this->getConstantName($expectedSkillName);
        self::assertTrue(defined(get_class($skill) . '::' . $constantName));
        $reflection = new \ReflectionClass($skill);
        self::assertSame($expectedSkillName, $reflection->getConstant($constantName));
    }

    protected function getConstantName($skillName)
    {
        return strtoupper($skillName);
    }

    protected function I_can_get_related_property_codes(Skill $skill)
    {
        self::assertEquals(
            $this->sort($this->getExpectedRelatedPropertyCodes()),
            $this->sort($skill->getRelatedPropertyCodes())
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

    protected function I_can_ask_it_which_type_is_it(Skill $skill)
    {
        // should be only one type
        self::assertSame(1, $this->shouldBePhysical() + $this->shouldBePsychical() + $this->shouldBeCombined());
        self::assertSame($this->shouldBePhysical(), $skill->isPhysical());
        self::assertSame($this->shouldBePsychical(), $skill->isPsychical());
        self::assertSame($this->shouldBeCombined(), $skill->isCombined());
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
        $sutClass = current($this->provideSkillClasses())[0]; // one is enough of this test
        /** @var Skill|PhysicalSkill|PsychicalSkill|CombinedSkill $sut */
        $sut = new $sutClass($this->createProfessionFirstLevel());
        self::assertCount(1, $sut->getSkillRanks());
        self::assertInstanceOf($this->getSkillRankClass($sutClass), $zeroSkillRank = $sut->getCurrentSkillRank());

        $sut->increaseSkillRank($skillPoint = $this->createSkillPoint());
        $oneSkillRank = $sut->getCurrentSkillRank();
        self::assertSame($skillPoint, $oneSkillRank->getSkillPoint());
        self::assertSame([0 => $zeroSkillRank, 1 => $oneSkillRank], $sut->getSkillRanks()->toArray());

        $sut->increaseSkillRank($skillPoint = $this->createSkillPoint());
        $twoSkillRank = $sut->getCurrentSkillRank();
        self::assertSame(
            [0 => $zeroSkillRank, 1 => $oneSkillRank, 2 => $twoSkillRank],
            $sut->getSkillRanks()->toArray()
        );
        self::assertSame($twoSkillRank, $sut->getCurrentSkillRank());

        $expectedSkillRank = 0;
        $oneSkillRank = null;
        foreach ($sut->getSkillRanks() as $skillRankValue => $oneSkillRank) {
            self::assertSame($skillRankValue, $oneSkillRank->getValue());
            self::assertSame($expectedSkillRank, $oneSkillRank->getValue());
            $expectedSkillRank++;
            self::assertSame($sut, $oneSkillRank->getSkill());
        }
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Exceptions\UnexpectedRankValue
     */
    public function I_can_not_add_rank_with_invalid_sequence()
    {
        $cheatingSkill = new CheatingSkill($this->createProfessionFirstLevel());
        self::assertCount(1, $cheatingSkill->getSkillRanks());
        /** @var CombinedSkillPoint|\Mockery\MockInterface $skillPoint */
        $skillPoint = $this->mockery(CombinedSkillPoint::class);
        $skillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $cheatingSkill->increaseSkillRank($skillPoint, 2);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @expectedExceptionMessageRegExp ~Cooking~
     */
    public function I_can_not_add_skill_rank_from_another_skill()
    {
        $cheatingSkill = new CheatingSkill($this->createProfessionFirstLevel());
        /** @var CombinedSkillPoint|\Mockery\MockInterface $skillPoint */
        $skillPoint = $this->mockery(CombinedSkillPoint::class);
        $skillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $cheatingSkill->increaseSkillRank($skillPoint, 1, new Cooking($this->createProfessionFirstLevel()));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @expectedExceptionMessageRegExp ~instance~
     */
    public function I_can_not_add_skill_rank_from_same_skill_but_different_instance()
    {
        $cheatingSkill = new CheatingSkill($this->createProfessionFirstLevel());
        /** @var CombinedSkillPoint|\Mockery\MockInterface $skillPoint */
        $skillPoint = $this->mockery(CombinedSkillPoint::class);
        $skillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $cheatingSkill->increaseSkillRank($skillPoint, 1, new CheatingSkill($this->createProfessionFirstLevel()));
    }
}

/** inner */
class CheatingSkill extends CombinedSkill
{
    public function getName(): string
    {
        return 'foo';
    }

    public function increaseSkillRank(
        CombinedSkillPoint $combinedSkillPoint,
        $nextRankIncrement = 1,
        CombinedSkill $rankRelatedSkill = null
    )
    {
        parent::addTypeVerifiedSkillRank(
            new CombinedSkillRank(
                $rankRelatedSkill
                    ?: $this,
                $combinedSkillPoint,
                new PositiveIntegerObject($this->getCurrentSkillRank()->getValue() + $nextRankIncrement)
            )
        );
    }

}