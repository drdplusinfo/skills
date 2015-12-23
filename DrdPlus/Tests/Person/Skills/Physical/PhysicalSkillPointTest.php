<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Professions\Fighter;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Person\Skills\AbstractTestOfSkillPoint;

class PhysicalSkillPointTest extends AbstractTestOfSkillPoint
{
    /**
     * @test
     */
    public function I_can_create_skill_point_by_first_level_background_skills()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createByFirstLevelBackgroundSkills(
            $this->createProfessionFirstLevel(Fighter::FIGHTER),
            $backgroundSkillPoints = $this->createBackgroundSkills(123, 'getPhysicalSkillPoints'),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertSame(1, $physicalSkillPoint->getValue());
        $this->assertSame('physical', $physicalSkillPoint->getTypeName());
        $this->assertSame([Strength::STRENGTH, Agility::AGILITY], $physicalSkillPoint->getRelatedProperties());
        $this->assertSame($backgroundSkillPoints, $physicalSkillPoint->getBackgroundSkills());
        $this->assertNull($physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($physicalSkillPoint->getSecondPaidOtherSkillPoint());
    }

    /**
     * @test
     */
    public function I_can_create_skill_point_by_cross_type_skill_points()
    {
        $this->I_can_create_skill_point_from_two_combined_skill_points();
        $this->I_can_create_skill_point_from_two_psychical_skill_points();
        $this->I_can_create_skill_point_from_psychical_and_combined_skill_points();
    }

    private function I_can_create_skill_point_from_two_combined_skill_points()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromCrossTypeSkillPoints(
            $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createCombinedSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkills());
        $this->assertSame($firstPaidSkillPoint, $physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $physicalSkillPoint->getSecondPaidOtherSkillPoint());
    }

    private function I_can_create_skill_point_from_two_psychical_skill_points()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromCrossTypeSkillPoints(
            $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createPsychicalSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkills());
        $this->assertSame($firstPaidSkillPoint, $physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $physicalSkillPoint->getSecondPaidOtherSkillPoint());
    }

    private function I_can_create_skill_point_from_psychical_and_combined_skill_points()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createFromCrossTypeSkillPoints(
            $this->createProfessionFirstLevel(),
            $firstPaidSkillPoint = $this->createPsychicalSkillPoint(),
            $secondPaidSkillPoint = $this->createCombinedSkillPoint(),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkills());
        $this->assertSame($firstPaidSkillPoint, $physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertSame($secondPaidSkillPoint, $physicalSkillPoint->getSecondPaidOtherSkillPoint());
    }

    /**
     * @test
     */
    public function I_can_create_skill_point_by_related_property_increase()
    {
        $this->I_can_create_skill_point_by_level_by_strength_adjustment();
        $this->I_can_create_skill_point_by_level_by_agility_adjustment();
    }

    private function I_can_create_skill_point_by_level_by_strength_adjustment()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createByRelatedPropertyIncrease(
            $this->createProfessionNextLevel(Strength::class),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkills());
        $this->assertNull($physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($physicalSkillPoint->getSecondPaidOtherSkillPoint());
    }

    private function I_can_create_skill_point_by_level_by_agility_adjustment()
    {
        $physicalSkillPoint = PhysicalSkillPoint::createByRelatedPropertyIncrease(
            $this->createProfessionNextLevel(Strength::class, Agility::class),
            new Tables()
        );
        $this->assertInstanceOf(PhysicalSkillPoint::class, $physicalSkillPoint);
        $this->assertNull($physicalSkillPoint->getBackgroundSkills());
        $this->assertNull($physicalSkillPoint->getFirstPaidOtherSkillPoint());
        $this->assertNull($physicalSkillPoint->getSecondPaidOtherSkillPoint());
    }

}
