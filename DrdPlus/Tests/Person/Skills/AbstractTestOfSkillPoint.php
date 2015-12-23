<?php
namespace DrdPlus\Tests\Person\Skills;

use DrdPlus\Person\Background\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Professions\Profession;
use DrdPlus\Tables\Tables;
use DrdPlus\Tools\Tests\TestWithMockery;

abstract class AbstractTestOfSkillPoint extends TestWithMockery
{
    abstract public function I_can_create_skill_point_by_first_level_background_skills();

    abstract public function I_can_create_skill_point_by_cross_type_skill_points();

    abstract public function I_can_create_skill_point_by_related_property_increase();

    /**
     * @param string $professionName
     *
     * @return \Mockery\MockInterface|ProfessionLevel
     */
    protected function createProfessionFirstLevel($professionName = '')
    {
        $professionLevel = $this->mockery(ProfessionLevel::class);
        $professionLevel->shouldReceive('isFirstLevel')
            ->atLeast()->once()
            ->andReturn(true);
        if ($professionName) {
            $professionLevel->shouldReceive('getProfession')
                ->atLeast()->once()
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
     *
     * @return \Mockery\MockInterface|PhysicalSkillPoint
     */
    protected function createPhysicalSkillPoint($paidByBackgroundPoints = true)
    {
        return $this->createSkillPointMock(PhysicalSkillPoint::class, 'foo physical', $paidByBackgroundPoints);
    }

    private function createSkillPointMock(
        $skillPointClass,
        $typeName,
        $paidByBackgroundPoints
    )
    {
        $skillPoint = $this->mockery($skillPointClass);
        $skillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkills')
            ->atLeast()->once()
            ->andReturn($paidByBackgroundPoints);
        $skillPoint->shouldReceive('getTypeName')
            ->atLeast()->once()
            ->andReturn($typeName);

        return $skillPoint;
    }

    /**
     * @param bool $paidByBackgroundPoints
     *
     * @return \Mockery\MockInterface|CombinedSkillPoint
     */
    protected function createCombinedSkillPoint($paidByBackgroundPoints = true)
    {
        return $this->createSkillPointMock(CombinedSkillPoint::class, 'foo combined', $paidByBackgroundPoints);
    }

    /**
     * @param bool $paidByBackgroundPoints
     *
     * @return \Mockery\MockInterface|PsychicalSkillPoint
     */
    protected function createPsychicalSkillPoint($paidByBackgroundPoints = true)
    {
        return $this->createSkillPointMock(PsychicalSkillPoint::class, 'foo psychical', $paidByBackgroundPoints);
    }

    /**
     * @param $firstPropertyClass
     * @param bool $secondPropertyClass
     * @return \Mockery\MockInterface|ProfessionLevel
     */
    protected function createProfessionNextLevel($firstPropertyClass, $secondPropertyClass = false)
    {
        $professionLevel = $this->mockery(ProfessionLevel::class);
        $professionLevel->shouldReceive('isFirstLevel')
            ->atLeast()->once()
            ->andReturn(false);
        $professionLevel->shouldReceive('isNextLevel')
            ->atLeast()->once()
            ->andReturn(true);
        $professionLevel->shouldReceive('get' . $this->parsePropertyName($firstPropertyClass) . 'Increment')
            ->atLeast()->once()
            ->andReturn($willIncrement = $this->mockery($firstPropertyClass));
        $willIncrement->shouldReceive('getValue')
            ->atLeast()->once()
            ->andReturn($secondPropertyClass ? 0 : 1);
        if ($secondPropertyClass) {
            $professionLevel->shouldReceive('get' . $this->parsePropertyName($secondPropertyClass) . 'Increment')
                ->atLeast()->once()
                ->andReturn($intelligenceIncrement = $this->mockery($secondPropertyClass));
            $intelligenceIncrement->shouldReceive('getValue')
                ->atLeast()->once()
                ->andReturn(1);
        }

        return $professionLevel;
    }

    private function parsePropertyName($propertyClass)
    {
        return basename(str_replace('\\', '/', $propertyClass));
    }

}
