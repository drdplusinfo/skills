<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\Tests\Tools\TestWithMockery;

class PersonSkillsTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_create_it()
    {
        $personSkills = new PersonSkills(
            $physicalSkills = $this->createPhysicalSkills($physical = ['foo']),
            $psychicalSkills = $this->createPsychicalSkills($psychical = ['bar']),
            $combinedSkills = $this->createCombinedSkills($combined = ['baz'])
        );

        $this->assertNull($personSkills->getId());

        $this->assertSame($physicalSkills, $personSkills->getPhysicalSkills());
        $this->assertSame($psychicalSkills, $personSkills->getPsychicalSkills());
        $this->assertSame($combinedSkills, $personSkills->getCombinedSkills());
        $this->assertEquals(
            $this->getSortedExpectedSkills($physical, $psychical, $combined),
            $this->getSortedGivenSkills($personSkills)
        );
    }

    private function getSortedExpectedSkills(array $physical, array $psychical, array $combined)
    {
        $expectedSkills = array_merge($physical, $psychical, $combined);
        sort($expectedSkills);

        return $expectedSkills;
    }

    private function getSortedGivenSkills(PersonSkills $personSkills)
    {
        $givenSkills = $personSkills->getSkills();
        sort($givenSkills);

        return $givenSkills;
    }

    /**
     * @param array $asArray
     * @return PersonPhysicalSkills
     * */
    private function createPhysicalSkills(array $asArray = [])
    {
        $physicalSkills = $this->mockery(PersonPhysicalSkills::class);
        $physicalSkills->shouldReceive('toArray')
            ->andReturn($asArray);

        return $physicalSkills;
    }

    /**
     * @param array $asArray
     * @return PersonPsychicalSkills
     */
    private function createPsychicalSkills(array $asArray = [])
    {
        $psychicalSkills = $this->mockery(PersonPsychicalSkills::class);
        $psychicalSkills->shouldReceive('toArray')
            ->andReturn($asArray);

        return $psychicalSkills;
    }

    /**
     * @param array $asArray
     * @return PersonCombinedSkills
     */
    private function createCombinedSkills(array $asArray = [])
    {
        $combinedSkills = $this->mockery(PersonCombinedSkills::class);
        $combinedSkills->shouldReceive('toArray')
            ->andReturn($asArray);

        return $combinedSkills;
    }

}
