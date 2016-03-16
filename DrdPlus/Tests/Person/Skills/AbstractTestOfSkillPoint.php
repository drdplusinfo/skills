<?php
namespace DrdPlus\Tests\Person\Skills;

use DrdPlus\Person\Background\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\LevelRank;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Person\Skills\PersonSkillPoint;
use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Professions\Profession;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Tables;
use Granam\Tests\Tools\TestWithMockery;

abstract class AbstractTestOfSkillPoint extends TestWithMockery
{
    protected $paidByFirstLevelBackgroundSkills;
    protected $isPaidByNextLevelPropertyIncrease;
    protected $isPaidByOtherSkillPoints;

    /**
     * @test
     */
    public function I_can_use_skill_point_by_first_level_background_skills()
    {
        $skillPointAndLevel = $this->I_can_create_skill_point_by_first_level_background_skills();

        $this->I_got_null_as_ID_of_non_persisted_skill_point($skillPointAndLevel[0]);
        $this->I_got_always_number_one_on_to_string_conversion($skillPointAndLevel[0]);
        $this->I_can_get_profession_level($skillPointAndLevel[0], $skillPointAndLevel[1]);
        $this->I_can_detect_way_of_payment($skillPointAndLevel[0]);
    }

    /**
     * @return array [PersonSkillPoint, PersonLevel]
     */
    abstract protected function I_can_create_skill_point_by_first_level_background_skills();

    protected function I_got_null_as_ID_of_non_persisted_skill_point(PersonSkillPoint $skillPoint)
    {
        self::assertNull($skillPoint->getId());
    }

    protected function I_got_always_number_one_on_to_string_conversion(PersonSkillPoint $skillPoint)
    {
        self::assertSame('1', (string)$skillPoint);
    }

    protected function I_can_get_profession_level(PersonSkillPoint $skillPoint, ProfessionLevel $expectedLevel)
    {
        self::assertSame($expectedLevel, $skillPoint->getProfessionLevel());
    }

    protected function I_can_detect_way_of_payment(PersonSkillPoint $skillPoint)
    {
        self::assertSame(
            $skillPoint->getBackgroundSkillPoints() !== null,
            $skillPoint->isPaidByFirstLevelBackgroundSkillPoints()
        );
        self::assertSame(
            $skillPoint->getFirstPaidOtherSkillPoint() !== null && $skillPoint->getSecondPaidOtherSkillPoint() !== null,
            $skillPoint->isPaidByOtherSkillPoints()
        );
        self::assertSame(
            !$skillPoint->isPaidByFirstLevelBackgroundSkillPoints()
            && !$skillPoint->isPaidByOtherSkillPoints()
            && $skillPoint->getProfessionLevel()->isNextLevel(),
            $skillPoint->isPaidByNextLevelPropertyIncrease()
        );
        self::assertSame(
            1,
            $skillPoint->isPaidByFirstLevelBackgroundSkillPoints()
            + $skillPoint->isPaidByNextLevelPropertyIncrease()
            + $skillPoint->isPaidByOtherSkillPoints()
        );
    }

    /**
     * @test
     */
    public function I_can_use_skill_point_by_cross_type_skill_points()
    {
        $skillPointsAndLevels = $this->I_can_create_skill_point_by_cross_type_skill_points();

        foreach ($skillPointsAndLevels as $skillPointAndLevel) {
            $this->I_got_null_as_ID_of_non_persisted_skill_point($skillPointAndLevel[0]);
            $this->I_got_always_number_one_on_to_string_conversion($skillPointAndLevel[0]);
            $this->I_can_get_profession_level($skillPointAndLevel[0], $skillPointAndLevel[1]);
            $this->I_can_detect_way_of_payment($skillPointAndLevel[0]);
        }
    }

    /**
     * @return array [PersonSkillPoint, PersonLevel][]
     */
    abstract protected function I_can_create_skill_point_by_cross_type_skill_points();

    /**
     * @test
     */
    public function I_can_use_skill_point_by_related_property_increase()
    {
        $skillPointsAndLevels = $this->I_can_create_skill_point_by_related_property_increase();

        foreach ($skillPointsAndLevels as $skillPointsAndLevel) {
            $this->I_got_null_as_ID_of_non_persisted_skill_point($skillPointsAndLevel[0]);
            $this->I_got_always_number_one_on_to_string_conversion($skillPointsAndLevel[0]);
            $this->I_can_get_profession_level($skillPointsAndLevel[0], $skillPointsAndLevel[1]);
            $this->I_can_detect_way_of_payment($skillPointsAndLevel[0]);
        }
    }

    /**
     * @return PersonSkillPoint[]
     */
    abstract protected function I_can_create_skill_point_by_related_property_increase();

    /**
     * @param string $professionName
     *
     * @return \Mockery\MockInterface|ProfessionLevel
     */
    protected function createProfessionFirstLevel($professionName = '')
    {
        $professionLevel = $this->mockery(ProfessionLevel::class);
        $professionLevel->shouldReceive('isFirstLevel')
            ->andReturn(true);
        $professionLevel->shouldReceive('isNextLevel')
            ->andReturn(false);
        $professionLevel->shouldReceive('getLevelRank')
            ->andReturn($levelRank = $this->mockery(LevelRank::class));
        $levelRank->shouldReceive('getValue')
            ->andReturn(1);
        if ($professionName) {
            $professionLevel->shouldReceive('getProfession')
                ->andReturn($profession = $this->mockery(Profession::class));
            $profession->shouldReceive('getValue')
                ->andReturn($professionName);
        }

        return $professionLevel;
    }

    /**
     * @param int $skillPointsValue
     * @param string $getterName
     *
     * @return \Mockery\MockInterface|BackgroundSkillPoints
     */
    protected function createBackgroundSkills($skillPointsValue, $getterName)
    {
        $backgroundSKills = $this->mockery(BackgroundSkillPoints::class);
        $backgroundSKills->shouldReceive($getterName)
            ->with(\Mockery::type(Profession::class), \Mockery::type(Tables::class))
            ->atLeast()->once()
            ->andReturn($skillPointsValue);

        return $backgroundSKills;
    }

    /**
     * @param bool $paidByBackgroundPoints
     * @param bool $paidByNextLevelPropertyIncrease
     * @param bool $paidByOtherSkillPoints
     *
     * @return \Mockery\MockInterface|PhysicalSkillPoint
     */
    protected function createPhysicalSkillPoint(
        $paidByBackgroundPoints = true, $paidByNextLevelPropertyIncrease = false, $paidByOtherSkillPoints = false
    )
    {
        return $this->createSkillPoint(
            PhysicalSkillPoint::class, 'foo physical', $paidByBackgroundPoints, $paidByNextLevelPropertyIncrease, $paidByOtherSkillPoints
        );
    }

    /**
     * @param $skillPointClass
     * @param $typeName
     * @param $paidByBackgroundPoints
     * @param bool $isPaidByNextLevelPropertyIncrease
     * @param bool $isPaidByOtherSkillPoints
     * @return \Mockery\MockInterface|PersonSkillPoint
     */
    private function createSkillPoint(
        $skillPointClass,
        $typeName,
        $paidByBackgroundPoints,
        $isPaidByNextLevelPropertyIncrease = false,
        $isPaidByOtherSkillPoints = false
    )
    {
        $skillPoint = $this->mockery($skillPointClass);
        $skillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn($paidByBackgroundPoints);
        $skillPoint->shouldReceive('getTypeName')
            ->andReturn($typeName);
        $skillPoint->shouldReceive('isPaidByNextLevelPropertyIncrease')
            ->andReturn($isPaidByNextLevelPropertyIncrease);
        $skillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn($isPaidByOtherSkillPoints);

        return $skillPoint;
    }

    /**
     * @param bool $paidByBackgroundPoints
     * @param bool $paidByNextLevelPropertyIncrease
     * @param bool $paidByOtherSkillPoints
     *
     * @return \Mockery\MockInterface|CombinedSkillPoint
     */
    protected function createCombinedSkillPoint(
        $paidByBackgroundPoints = true, $paidByNextLevelPropertyIncrease = false, $paidByOtherSkillPoints = false
    )
    {
        return $this->createSkillPoint(
            CombinedSkillPoint::class, 'foo combined', $paidByBackgroundPoints, $paidByNextLevelPropertyIncrease, $paidByOtherSkillPoints
        );
    }

    /**
     * @param bool $paidByBackgroundPoints
     * @param bool $paidByNextLevelPropertyIncrease
     * @param bool $paidByOtherSkillPoints
     *
     * @return \Mockery\MockInterface|PsychicalSkillPoint
     */
    protected function createPsychicalSkillPoint(
        $paidByBackgroundPoints = true, $paidByNextLevelPropertyIncrease = false, $paidByOtherSkillPoints = false
    )
    {
        return $this->createSkillPoint(
            PsychicalSkillPoint::class, 'foo psychical', $paidByBackgroundPoints, $paidByNextLevelPropertyIncrease, $paidByOtherSkillPoints
        );
    }

    /**
     * @param $firstPropertyClass
     * @param bool $secondPropertyClass
     * @param bool $withPropertyIncrement
     * @return \Mockery\MockInterface|ProfessionLevel
     */
    protected function createProfessionNextLevel(
        $firstPropertyClass, $secondPropertyClass, $withPropertyIncrement = true
    )
    {
        $professionLevel = $this->mockery(ProfessionLevel::class);
        $professionLevel->shouldReceive('isFirstLevel')
            ->atLeast()->once()
            ->andReturn(false);
        $professionLevel->shouldReceive('isNextLevel')
            ->atLeast()->once()
            ->andReturn(true);
        $professionLevel->shouldReceive('get' . $this->parsePropertyName($firstPropertyClass) . 'Increment')
            ->andReturn($willIncrement = $this->mockery($firstPropertyClass));
        $willIncrement->shouldReceive('getValue')
            ->andReturn($withPropertyIncrement ? 1 : 0);
        $professionLevel->shouldReceive('get' . $this->parsePropertyName($secondPropertyClass) . 'Increment')
            ->andReturn($intelligenceIncrement = $this->mockery($secondPropertyClass));
        $intelligenceIncrement->shouldReceive('getValue')
            ->andReturn($withPropertyIncrement ? 1 : 0);
        $professionLevel->shouldReceive('getProfession')
            ->andReturn($profession = $this->mockery(Profession::class));
        $profession->shouldReceive('getValue')
            ->andReturn('foo');
        $professionLevel->shouldReceive('getLevelRank')
            ->andReturn($levelRank = $this->mockery(LevelRank::class));
        $levelRank->shouldReceive('getValue')
            ->andReturn(2);

        return $professionLevel;
    }

    private function parsePropertyName($propertyClass)
    {
        return basename(str_replace('\\', '/', $propertyClass));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    public function I_can_not_create_skill_point_by_invalid_payment()
    {
        DeAbstractedPersonSkillPoint::createFromNextLevelsPropertyIncrease(
            $this->createProfessionFirstLevel('foo'),
            new Tables()
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\EmptyFirstLevelBackgroundSkillPoints
     */
    public function I_can_not_create_skill_point_by_poor_first_level_background()
    {
        DeAbstractedPersonSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $this->createProfessionFirstLevel('foo'),
            $this->createBackgroundSkills(0, 'getPhysicalSkillPoints'),
            new Tables()
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\ProhibitedOriginOfPaidBySkillPoint
     * @dataProvider provideInvalidPayment
     * @param $firstPaidByBackgroundPoints
     * @param $secondPaidByBackgroundPoints
     */
    public function I_can_not_create_skill_point_by_non_first_level_other_skill_point(
        $firstPaidByBackgroundPoints, $secondPaidByBackgroundPoints
    )
    {
        DeAbstractedPersonSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $this->createProfessionFirstLevel('foo'),
            $this->createCombinedSkillPoint($firstPaidByBackgroundPoints, true, true),
            $this->createCombinedSkillPoint($secondPaidByBackgroundPoints, true, true),
            new Tables()
        );
    }

    public function provideInvalidPayment()
    {
        return [
            [true, false],
            [true, false],
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\NonSensePaymentBySameType
     */
    public function I_can_not_pay_for_skill_point_by_same_type_skill_point()
    {
        $sameTypeSkillPoint = $this->createSkillPoint(
            DeAbstractedPersonSkillPoint::class,
            DeAbstractedPersonSkillPoint::TYPE_NAME,
            true
        );
        $sameTypeSkillPoint->shouldReceive('getProfessionLevel')
            ->andReturn($this->createProfessionFirstLevel('baz'));
        DeAbstractedPersonSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $this->createProfessionFirstLevel('bar'),
            $sameTypeSkillPoint,
            $this->createPhysicalSkillPoint(),
            new Tables()
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\MissingPropertyAdjustmentForPayment
     */
    public function I_can_not_pay_for_skill_point_by_next_level_without_property_increment()
    {
        DeAbstractedPersonSkillPoint::createFromNextLevelsPropertyIncrease(
            $this->createProfessionNextLevel(Strength::class, Agility::class, false),
            new Tables()
        );
    }

}

/** inner */
class DeAbstractedPersonSkillPoint extends PersonSkillPoint
{
    const TYPE_NAME = 'foo';

    public function getTypeName()
    {
        return self::TYPE_NAME;
    }

    public function getRelatedProperties()
    {
        return [Strength::STRENGTH, Agility::AGILITY];
    }

}
