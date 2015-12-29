<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Person\Skills\Physical\PersonPhysicalSkill;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Tests\Person\Skills\AbstractTestOfPersonSameTypeSkills;

class PersonPhysicalSkillsTest extends AbstractTestOfPersonSameTypeSkills
{
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

}
