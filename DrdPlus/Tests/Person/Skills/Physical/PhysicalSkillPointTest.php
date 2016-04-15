<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Professions\Fighter;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Person\Skills\PersonSkillPointTest;

class PhysicalSkillPointTest extends PersonSkillPointTest
{
    protected function I_can_create_skill_point_by_first_level_background_skills()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $level = $this->createProfessionFirstLevel(Fighter::FIGHTER),
            $backgroundSkillPoints = $this->createBackgroundSkills(123, 'getPhysicalSkillPoints'),
            new Tables()
        );
        self::assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        self::assertSame(1, $physicalSkillPoint->getValue());
        self::assertSame('physical', $physicalSkillPoint->getTypeName());
        self::assertSame([Strength::STRENGTH, Agility::AGILITY], $physicalSkillPoint->getRelatedProperties());
        self::assertSame($backgroundSkillPoints, $physicalSkillPoint->getBackgroundSkillPoints());
        self::assertNull($physicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertNull($physicalSkillPoint->getSecondPaidOtherSkillPoint());

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
        $physicalSkillPoint = PhysicalSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createCombinedSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        self::assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        self::assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        self::assertSame($firstPaidSkillPoint, $physicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertSame($secondPaidSkillPoint, $physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_two_psychical_skill_points()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPsychicalSkillPoint(),
            new Tables()
        );
        self::assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        self::assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        self::assertSame($firstPaidSkillPoint, $physicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertSame($secondPaidSkillPoint, $physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_psychical_and_combined_skill_points()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        self::assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        self::assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        self::assertSame($firstPaidSkillPoint, $physicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertSame($secondPaidSkillPoint, $physicalSkillPoint->getSecondPaidOtherSkillPoint());

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
        $physicalSkillPoint = PhysicalSkillPoint::createFromNextLevelPropertyIncrease(
            $level = $this->createProfessionNextLevel(Agility::class, Strength::class),
            new Tables()
        );
        self::assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        self::assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        self::assertNull($physicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertNull($physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

    private function I_can_create_skill_point_by_level_by_agility_adjustment()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromNextLevelPropertyIncrease(
            $level = $this->createProfessionNextLevel(Strength::class, Agility::class),
            new Tables()
        );
        self::assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        self::assertNull($physicalSkillPoint->getBackgroundSkillPoints());
        self::assertNull($physicalSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertNull($physicalSkillPoint->getSecondPaidOtherSkillPoint());

        return [$physicalSkillPoint, $level];
    }

}
