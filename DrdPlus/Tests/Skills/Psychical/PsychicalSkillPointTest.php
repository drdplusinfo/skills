<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Professions\Fighter;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Skills\SkillPointTest;

class PsychicalSkillPointTest extends SkillPointTest
{
    protected function I_can_create_skill_point_by_first_level_background_skills()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $level = $this->createProfessionFirstLevel(Fighter::FIGHTER),
            $backgroundSkillPoints = $this->createBackgroundSkills(123, 'getPsychicalSkillPoints'),
            new Tables()
        );
        self::assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        self::assertSame(1, $psychicalSkillPoint->getValue());
        self::assertSame('psychical', $psychicalSkillPoint->getTypeName());
        self::assertSame([Will::WILL, Intelligence::INTELLIGENCE], $psychicalSkillPoint->getRelatedProperties());
        self::assertSame($backgroundSkillPoints, $psychicalSkillPoint->getBackgroundSkillPoints());
        self::assertNull($psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertNull($psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

    protected function I_can_create_skill_point_by_cross_type_skill_points()
    {
        $skillPointsAndLevels = [];
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_from_two_combined_skill_points();
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_from_two_physical_skill_points();
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_from_physical_and_combined_skill_points();

        return $skillPointsAndLevels;
    }

    private function I_can_create_skill_point_from_two_combined_skill_points()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createCombinedSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        self::assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        self::assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        self::assertSame($firstPaidSkillPoint, $psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertSame($secondPaidSkillPoint, $psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_two_physical_skill_points()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPhysicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPhysicalSkillPoint(),
            new Tables()
        );
        self::assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        self::assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        self::assertSame($firstPaidSkillPoint, $psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertSame($secondPaidSkillPoint, $psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_physical_and_combined_skill_points()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPhysicalSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        self::assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        self::assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        self::assertSame($firstPaidSkillPoint, $psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertSame($secondPaidSkillPoint, $psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

    protected function I_can_create_skill_point_by_related_property_increase()
    {
        $skillPointsAndLevels = [];
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_by_level_by_will_adjustment();
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_by_level_by_intelligence_adjustment();

        return $skillPointsAndLevels;
    }

    private function I_can_create_skill_point_by_level_by_will_adjustment()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromNextLevelPropertyIncrease(
            $level = $this->createProfessionNextLevel(Intelligence::class, Will::class),
            new Tables()
        );
        self::assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        self::assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        self::assertNull($psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertNull($psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_by_level_by_intelligence_adjustment()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromNextLevelPropertyIncrease(
            $level = $this->createProfessionNextLevel(Will::class, Intelligence::class),
            new Tables()
        );
        self::assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        self::assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        self::assertNull($psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertNull($psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

}
