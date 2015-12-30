<?php
namespace DrdPlus\Tests\Person\Skills\Psychical;

use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkill;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\Tests\Person\Skills\AbstractTestOfPersonSameTypeSkills;

class PersonPsychicalSkillsTest extends AbstractTestOfPersonSameTypeSkills
{
    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Psychical\Exceptions\UnknownPsychicalSkill
     */
    public function I_can_not_add_unknown_skill()
    {
        $skills = new PersonPsychicalSkills();
        /** @var PersonPsychicalSkill $strangePsychicalSkill */
        $strangePsychicalSkill = $this->mockery(PersonPsychicalSkill::class);
        $skills->addPsychicalSkill($strangePsychicalSkill);
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
