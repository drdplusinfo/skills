<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Professions\Fighter;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Person\Skills\AbstractTestOfSkillPoint;

class PsychicalSkillPointTest extends AbstractTestOfSkillPoint
{
    protected function I_can_create_skill_point_by_first_level_background_skills()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $level = $this->createProfessionFirstLevel(Fighter::FIGHTER),
            $backgroundSkillPoints = $this->createBackgroundSkills(123, 'getPsychicalSkillPoints'),
            new Tables()
        );
        $this->assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        $this->assertSame(1, $psychicalSkillPoint->getValue());
        $this->assertSame('psychical', $psychicalSkillPoint->getTypeName());
        $this->assertSame([Will::WILL, Intelligence::INTELLIGENCE], $psychicalSkillPoint->getRelatedProperties());
        $this->assertSame($backgroundSkillPoints, $psychicalSkillPoint->getBackgroundSkillPoints());
        $this->assertNull($psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($psychicalSkillPoint->getSecondPaidOtherSkillPoint());

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
        $psychicalSkillPoint = PsychicalSkillPoint::createFromCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createCombinedSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        $this->assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        $this->assertSame($firstPaidSkillPoint, $psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_two_physical_skill_points()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPhysicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPhysicalSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        $this->assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        $this->assertSame($firstPaidSkillPoint, $psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_physical_and_combined_skill_points()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPhysicalSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        $this->assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        $this->assertSame($firstPaidSkillPoint, $psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $psychicalSkillPoint->getSecondPaidOtherSkillPoint());

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
        $psychicalSkillPoint = PsychicalSkillPoint::createFromRelatedPropertyIncrease(
            $level = $this->createProfessionNextLevel(Intelligence::class, Will::class),
            new Tables()
        );
        $this->assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        $this->assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        $this->assertNull($psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_by_level_by_intelligence_adjustment()
    {
        $psychicalSkillPoint = PsychicalSkillPoint::createFromRelatedPropertyIncrease(
            $level = $this->createProfessionNextLevel(Will::class, Intelligence::class),
            new Tables()
        );
        $this->assertInstanceOf(PsychicalSkillPoint::class, $psychicalSkillPoint);
        $this->assertNull($psychicalSkillPoint->getBackgroundSkillPoints());
        $this->assertNull($psychicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($psychicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$psychicalSkillPoint, $level];
    }

}
