<?php
namespace DrdPlus\Tests\Person\Skills\Combined;

use DrdPlus\Person\Skills\Combined\PersonCombinedSkill;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Tests\Person\Skills\AbstractTestOfPersonSameTypeSkills;

class PersonCombinedSkillsTest extends AbstractTestOfPersonSameTypeSkills
{
    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Combined\Exceptions\UnknownCombinedSkill
     */
    public function I_can_not_add_unknown_skill()
    {
        $skills = new PersonCombinedSkills();
        /** @var PersonCombinedSkill $strangeCombinedSkill */
        $strangeCombinedSkill = $this->mockery(PersonCombinedSkill::class);
        $skills->addCombinedSkill($strangeCombinedSkill);
    }

}
