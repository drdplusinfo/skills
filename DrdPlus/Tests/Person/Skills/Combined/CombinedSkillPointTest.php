<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Professions\Fighter;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Person\Skills\PersonSkillPointTest;

class CombinedSkillPointTest extends PersonSkillPointTest
{
    protected function I_can_create_skill_point_by_first_level_background_skills()
    {
        $combinedSkillPoint = CombinedSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $level = $this->createProfessionFirstLevel(Fighter::FIGHTER),
            $backgroundSkillPoints = $this->createBackgroundSkills(123, 'getCombinedSkillPoints'),
            new Tables()
        );
        self::assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        self::assertSame(1, $combinedSkillPoint->getValue());
        self::assertSame('combined', $combinedSkillPoint->getTypeName());
        self::assertSame([Knack::KNACK, Charisma::CHARISMA], $combinedSkillPoint->getRelatedProperties());
        self::assertSame($backgroundSkillPoints, $combinedSkillPoint->getBackgroundSkillPoints());
        self::assertNull($combinedSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertNull($combinedSkillPoint->getSecondPaidOtherSkillPoint());

        return [$combinedSkillPoint, $level];
    }

    protected function I_can_create_skill_point_by_cross_type_skill_points()
    {
        $skillPointsAndLevels = [];
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_from_two_physical_skill_points();
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_from_two_psychical_skill_points();
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_from_psychical_and_physical_skill_points();

        return $skillPointsAndLevels;
    }

    private function I_can_create_skill_point_from_two_physical_skill_points()
    {
        $combinedSkillPoint = CombinedSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPhysicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPhysicalSkillPoint(),
            new Tables()
        );
        self::assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        self::assertNull($combinedSkillPoint->getBackgroundSkillPoints());
        self::assertSame($firstPaidSkillPoint, $combinedSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertSame($secondPaidSkillPoint, $combinedSkillPoint->getSecondPaidOtherSkillPoint());

        return [$combinedSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_two_psychical_skill_points()
    {
        $combinedSkillPoint = CombinedSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPsychicalSkillPoint(),
            new Tables()
        );
        self::assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        self::assertNull($combinedSkillPoint->getBackgroundSkillPoints());
        self::assertSame($firstPaidSkillPoint, $combinedSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertSame($secondPaidSkillPoint, $combinedSkillPoint->getSecondPaidOtherSkillPoint());

        return [$combinedSkillPoint, $level];
    }

    private function I_can_create_skill_point_from_psychical_and_physical_skill_points()
    {
        $combinedSkillPoint = CombinedSkillPoint::createFromFirstLevelCrossTypeSkillPoints(
            $level = $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPhysicalSkillPoint(),
            new Tables()
        );
        self::assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        self::assertNull($combinedSkillPoint->getBackgroundSkillPoints());
        self::assertSame($firstPaidSkillPoint, $combinedSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertSame($secondPaidSkillPoint, $combinedSkillPoint->getSecondPaidOtherSkillPoint());

        return [$combinedSkillPoint, $level];
    }

    protected function I_can_create_skill_point_by_related_property_increase()
    {
        $skillPointsAndLevels = [];
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_by_level_knack_adjustment();
        $skillPointsAndLevels[] = $this->I_can_create_skill_point_by_level_charisma_adjustment();

        return $skillPointsAndLevels;
    }

    private function I_can_create_skill_point_by_level_knack_adjustment()
    {
        $combinedSkillPoint = CombinedSkillPoint::createFromNextLevelPropertyIncrease(
            $level = $this->createProfessionNextLevel(Knack::class, Charisma::class),
            new Tables()
        );
        self::assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        self::assertNull($combinedSkillPoint->getBackgroundSkillPoints());
        self::assertNull($combinedSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertNull($combinedSkillPoint->getSecondPaidOtherSkillPoint());

        return [$combinedSkillPoint, $level];
    }

    private function I_can_create_skill_point_by_level_charisma_adjustment()
    {
        $combinedSkillPoint = CombinedSkillPoint::createFromNextLevelPropertyIncrease(
            $level = $this->createProfessionNextLevel(Charisma::class, Knack::class),
            new Tables()
        );
        self::assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        self::assertNull($combinedSkillPoint->getBackgroundSkillPoints());
        self::assertNull($combinedSkillPoint->getFirstPaidOtherSkillPoint());
        self::assertNull($combinedSkillPoint->getSecondPaidOtherSkillPoint());

        return [$combinedSkillPoint, $level];
    }

}
