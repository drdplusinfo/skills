<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Professions\Fighter;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Person\Skills\AbstractTestOfSkillPoint;

class PhysicalSkillPointTest extends AbstractTestOfSkillPoint
{
    protected function I_can_create_skill_point_by_first_level_background_skills()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createByFirstLevelBackgroundSkills(
            $level = $this->createProfessionFirstLevel(Fighter::FIGHTER),
            $backgroundSkillPoints = $this->createBackgroundSkills(123, 'getPhysicalSkillPoints'),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertSame(1, $physicalSkillPoint->getValue());
        $this->assertSame('physical', $physicalSkillPoint->getTypeName());
        $this->assertSame([Strength::STRENGTH, Agility::AGILITY], $physicalSkillPoint->getRelatedProperties());
        $this->assertSame($backgroundSkillPoints, $physicalSkillPoint->getBackgroundSkillPoints());
        $this->assertNull($physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

    protected function I_can_create_skill_point_by_cross_type_skill_points()
    {
        $skillPointsAndLevels = [];
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_from_two_combined_skill_points();
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_from_two_psychical_skill_points();
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_from_psychical_and_combined_skill_points();

        return $skillPointsAndLevels;
    }

    private function I_can_create_skill_point_from_two_combined_skill_points()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createCombinedSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        $this->assertSame($firstPaidSkillPoint, $physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_two_psychical_skill_points()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPsychicalSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        $this->assertSame($firstPaidSkillPoint, $physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_psychical_and_combined_skill_points()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        $this->assertSame($firstPaidSkillPoint, $physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

    protected function I_can_create_skill_point_by_related_property_increase()
    {
        $skillPointsAndLevels = [];
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_by_level_by_strength_adjustment();
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_by_level_by_agility_adjustment();

        return $skillPointsAndLevels;
    }

    private function I_can_create_skill_point_by_level_by_strength_adjustment()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createByRelatedPropertyIncrease(
            $level = $this->createProfessionNextLevel(Strength::class),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        $this->assertNull($physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_by_level_by_agility_adjustment()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createByRelatedPropertyIncrease(
            $level = $this->createProfessionNextLevel(Strength::class, Agility::class),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        $this->assertNull($physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

}
