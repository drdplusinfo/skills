<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Person\Background\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\PersonProperties\NextLevelsProperties;
use DrdPlus\Tables\Tables;
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
        $physicalSkills->shouldReceive('getIterator')
            ->andReturn(new \ArrayObject($asArray));

        return $physicalSkills;
    }

    /**
     * @param array $asArray
     * @return PersonPsychicalSkills
     */
    private function createPsychicalSkills(array $asArray = [])
    {
        $psychicalSkills = $this->mockery(PersonPsychicalSkills::class);
        $psychicalSkills->shouldReceive('getIterator')
            ->andReturn(new \ArrayObject($asArray));

        return $psychicalSkills;
    }

    /**
     * @param array $asArray
     * @return PersonCombinedSkills
     */
    private function createCombinedSkills(array $asArray = [])
    {
        $combinedSkills = $this->mockery(PersonCombinedSkills::class);
        $combinedSkills->shouldReceive('getIterator')
            ->andReturn(new \ArrayObject($asArray));

        return $combinedSkills;
    }

    /**
     * TODO rework check
     * @dataProvider providePointsToBeChecked
     * @param ProfessionLevels $professionLevels
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param NextLevelsProperties $nextLevelsProperties
     * @param \Exception $expectedException = null
     * @throws \Exception
     */
    public function I_can_let_check_validity_of_skill_points(
        ProfessionLevels $professionLevels,
        BackgroundSkillPoints $backgroundSkillPoints,
        NextLevelsProperties $nextLevelsProperties,
        \Exception $expectedException = null
    )
    {
        $personSkills = new PersonSkills(
            $physicalSkills = $this->createPhysicalSkills(),
            $psychicalSkills = $this->createPsychicalSkills(),
            $combinedSkills = $this->createCombinedSkills()
        );

        try {
            $personSkills->checkSkillPoints(
                $professionLevels,
                $backgroundSkillPoints,
                $nextLevelsProperties,
                new Tables()
            );
        } catch (\Exception $exception) {
            if ($expectedException) {
                $this->assertInstanceOf(get_class($expectedException), $exception);
            } else {
                throw $exception;
            }
        }
    }

    public function providePointsToBeChecked()
    {
        return [
            [
                $this->createProfessionLevels(),
                $this->createBackgroundSkillPoints(),
                $this->createNextLevelsProperties(),
                null // TODO specific exception
            ],
        ];
    }

    private function createProfessionLevels()
    {
        $professionLevels = $this->mockery(ProfessionLevels::class);

        return $professionLevels;
    }

    private function createBackgroundSkillPoints()
    {
        $backgroundSkillPoints = $this->mockery(BackgroundSkillPoints::class);

        return $backgroundSkillPoints;
    }

    private function createNextLevelsProperties()
    {
        $nextLevelsProperties = $this->mockery(NextLevelsProperties::class);

        return $nextLevelsProperties;
    }

}
