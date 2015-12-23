<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Professions\Fighter;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Person\Skills\AbstractTestOfSkillPoint;

class CombinedSkillPointTest extends AbstractTestOfSkillPoint
{
    /**
     * @test
     */
    public function I_can_create_skill_point_by_first_level_background_skills()
    {
        $combinedSkillPoint = CombinedSkillPoint::createByFirstLevelBackgroundSkills(
            $this->createProfessionFirstLevel(Fighter::FIGHTER),
            $backgroundSkillPoints = $this->createBackgroundSkills(123, 'getCombinedSkillPoints'),
            new Tables()
        );
        $this->assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        $this->assertSame(1, $combinedSkillPoint->getValue());
        $this->assertSame('combined', $combinedSkillPoint->getTypeName());
        $this->assertSame([Knack::KNACK, Charisma::CHARISMA], $combinedSkillPoint->getRelatedProperties());
        $this->assertSame($backgroundSkillPoints, $combinedSkillPoint->getBackgroundSkills());
        $this->assertNull($combinedSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($combinedSkillPoint->getSecondPaidOtherSkillPoint());
    }

    /**
     * @test
     */
    public function I_can_create_skill_point_by_cross_type_skill_points()
    {
        $this->I_can_create_skill_point_from_two_physical_skill_points();
        $this->I_can_create_skill_point_from_two_psychical_skill_points();
        $this->I_can_create_skill_point_from_psychical_and_physical_skill_points();
    }

    private function I_can_create_skill_point_from_two_physical_skill_points()
    {
        $combinedSkillPoint = CombinedSkillPoint::createFromCrossTypeSkillPoints(
            $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPhysicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPhysicalSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        $this->assertNull($combinedSkillPoint->getBackgroundSkills());
        $this->assertSame($firstPaidSkillPoint, $combinedSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $combinedSkillPoint->getSecondPaidOtherSkillPoint());
    }

    private function I_can_create_skill_point_from_two_psychical_skill_points()
    {
        $combinedSkillPoint = CombinedSkillPoint::createFromCrossTypeSkillPoints(
            $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPsychicalSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        $this->assertNull($combinedSkillPoint->getBackgroundSkills());
        $this->assertSame($firstPaidSkillPoint, $combinedSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $combinedSkillPoint->getSecondPaidOtherSkillPoint());
    }

    private function I_can_create_skill_point_from_psychical_and_physical_skill_points()
    {
        $combinedSkillPoint = CombinedSkillPoint::createFromCrossTypeSkillPoints(
            $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPhysicalSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        $this->assertNull($combinedSkillPoint->getBackgroundSkills());
        $this->assertSame($firstPaidSkillPoint, $combinedSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $combinedSkillPoint->getSecondPaidOtherSkillPoint());
    }

    /**
     * @test
     */
    public function I_can_create_skill_point_by_related_property_increase()
    {
        $this->I_can_create_skill_point_by_level_by_knack_adjustment();
        $this->I_can_create_skill_point_by_level_by_charisma_adjustment();
    }

    private function I_can_create_skill_point_by_level_by_knack_adjustment()
    {
        $combinedSkillPoint = CombinedSkillPoint::createByRelatedPropertyIncrease(
            $this->createProfessionNextLevel(Knack::class),
            new Tables()
        );
        $this->assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        $this->assertNull($combinedSkillPoint->getBackgroundSkills());
        $this->assertNull($combinedSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($combinedSkillPoint->getSecondPaidOtherSkillPoint());
    }

    private function I_can_create_skill_point_by_level_by_charisma_adjustment()
    {
        $combinedSkillPoint = CombinedSkillPoint::createByRelatedPropertyIncrease(
            $this->createProfessionNextLevel(Knack::class, Charisma::class),
            new Tables()
        );
        $this->assertInstanceOf(CombinedSkillPoint::class, $combinedSkillPoint);
        $this->assertNull($combinedSkillPoint->getBackgroundSkills());
        $this->assertNull($combinedSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($combinedSkillPoint->getSecondPaidOtherSkillPoint());
    }

}
