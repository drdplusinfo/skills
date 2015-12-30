<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkill;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Tests\Person\Skills\AbstractTestOfPersonSameTypeSkills;

class PersonPhysicalSkillsTest extends AbstractTestOfPersonSameTypeSkills
{

    /**
     * @test
     * @dataProvider providePersonSkill
     * @param PersonSkill $personSkill
     * @expectedException \DrdPlus\Person\Skills\Physical\Exceptions\PhysicalSkillAlreadySet
     */
    public function I_can_not_replace_skill(PersonSkill $personSkill)
    {
        parent::I_can_not_replace_skill($personSkill);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Physical\Exceptions\UnknownPhysicalSkill
     */
    public function I_can_not_add_unknown_skill()
    {
        $skills = new PersonPhysicalSkills();
        /** @var PersonPhysicalSkill $strangePhysicalSkill */
        $strangePhysicalSkill = $this->mockery(PersonPhysicalSkill::class);
        $skills->addPhysicalSkill($strangePhysicalSkill);
    }

    public function I_can_get_unused_skill_points_from_first_level()
    {
        // TODO: Implement I_can_get_unused_skill_points_from_first_level() method.
    }

    public function I_can_get_unused_skill_points_from_next_levels()
    {
        // TODO: Implement I_can_get_unused_skill_points_from_next_levels() method.
    }

}
