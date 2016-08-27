<?php
namespace DrdPlus\Tests\Skills;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\Combined\CombinedSkillRank;
use DrdPlus\Skills\Combined\CombinedSkill;
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
     * @dataProvider provideSutClass
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
        $sut->addSkillRank($skillPoint = $this->createSkillPoint());
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

    public function provideSutClass()
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
        $projectRootDir = preg_replace('~' . preg_quote($namespaceAsRelativePath) . '.*~', '', __DIR__);

        return $projectRootDir;
    }

    /**
     * @param Skill $personSkill
     * @param string $personSkillClass
     * @param int $value
     * @return \Mockery\MockInterface|SkillRank|PsychicalSkillRank|PhysicalSkillRank|CombinedSkillRank
     */
    protected function createPersonSkillRank(Skill $personSkill, $personSkillClass, $value = 1)
    {
        $personSkillRank = $this->mockery($this->getSkillRankClass($personSkillClass));
        $personSkillRank->shouldReceive('getPersonSkill')
            ->andReturn($personSkill);
        $personSkillRank->shouldReceive('getValue')
            ->andReturn($value);

        return $personSkillRank;
    }

    /**
     * @param $sutClass
     * @return string|SkillRank
     */
    private function getSkillRankClass($sutClass)
    {
        $baseClass = SkillRank::class;
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
     * @return \Mockery\MockInterface|SkillPoint|CombinedSkillPoint|PhysicalSkillPoint|PsychicalSkillPoint
     */
    private function createSkillPoint()
    {
        return $this->mockery($this->getSkillPointClass());
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

    protected function I_can_get_its_name(Skill $personSkill)
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

    protected function I_can_get_related_property_codes(Skill $personSkill)
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

    protected function I_can_ask_it_which_type_is_it(Skill $personSkill)
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
        /** @var Skill|PhysicalSkill|PsychicalSkill|CombinedSkill $sut */
        $sut = new $sutClass($this->createProfessionFirstLevel());
        self::assertCount(1, $sut->getSkillRanks());
        self::assertInstanceOf($this->getSkillRankClass($sutClass), $zeroSkillRank = $sut->getCurrentSkillRank());

        $sut->addSkillRank($skillPoint = $this->createSkillPoint());
        $oneSkillRank = $sut->getCurrentSkillRank();
        self::assertSame($skillPoint, $oneSkillRank->getSkillPoint());
        self::assertSame([0 => $zeroSkillRank, 1 => $oneSkillRank], $sut->getSkillRanks()->toArray());

        $sut->addSkillRank($skillPoint = $this->createSkillPoint());
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
        $sut = new CheatingSkill($this->createProfessionFirstLevel());
        self::assertCount(1, $sut->getSkillRanks());
        $sut->addSkillRank($this->mockery(CombinedSkillPoint::class), 2);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @expectedExceptionMessageRegExp ~belongs to different skill class~
     */
    public function I_can_not_add_rank_linked_with_another_skill()
    {
        $sut = new CheatingSkill($this->createProfessionFirstLevel());
        self::assertCount(1, $sut->getSkillRanks());
        /** @var CombinedSkill $anotherSut */
        $anotherSut = $this->mockery(CombinedSkill::class);
        $sut->addSkillRank($this->mockery(CombinedSkillPoint::class), 1, $anotherSut);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @expectedExceptionMessageRegExp ~belongs to different instance~
     */
    public function I_can_not_add_rank_linked_with_same_skill_but_different_instance()
    {
        $sut = new CheatingSkill($this->createProfessionFirstLevel());
        self::assertCount(1, $sut->getSkillRanks());
        $anotherSut = new CheatingSkill($this->createProfessionFirstLevel());
        $sut->addSkillRank($this->mockery(CombinedSkillPoint::class), 1, $anotherSut);
    }
}

/** inner */
class CheatingSkill extends CombinedSkill
{
    public function getName()
    {
        return 'foo';
    }

    public function addSkillRank(
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