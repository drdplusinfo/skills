<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\Tools\Tests\TestWithMockery;

class PersonSkillsTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_create_it()
    {
        $skills = new PersonSkills(
            $physicalSKills = $this->createPhysicalSkills(),
            $psychicalSkills = $this->createPsychicalSkills(),
            $combinedSkills = $this->createCombinedSkills()
        );

        $this->assertSame($physicalSKills, $skills->getPhysicalSkills());
        $this->assertSame($psychicalSkills, $skills->getPsychicalSkills());
        $this->assertSame($combinedSkills, $skills->getCombinedSkills());

        $this->assertNull($skills->getId());
    }

    /** @return PersonPhysicalSkills */
    private function createPhysicalSkills()
    {
        return $this->mockery(PersonPhysicalSkills::class);
    }

    /** @return PersonPsychicalSkills */
    private function createPsychicalSkills()
    {
        return $this->mockery(PersonPsychicalSkills::class);
    }
    /** @return PersonCombinedSkills */
    private function createCombinedSkills()
    {
        return $this->mockery(PersonCombinedSkills::class);
    }

}
