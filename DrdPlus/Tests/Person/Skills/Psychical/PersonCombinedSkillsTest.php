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

}
