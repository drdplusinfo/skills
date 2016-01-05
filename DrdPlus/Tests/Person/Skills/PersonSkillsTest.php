<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Person\Background\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\Professions\Profession;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tools\TestWithMockery;

class PersonSkillsTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_create_it()
    {
        $personSkills = PersonSkills::getIt(
            $this->createProfessionLevels('foo'),
            $this->createBackgroundSkillPoints(),
            new Tables(),
            $physicalSkills = $this->createPhysicalSkills(),
            $psychicalSkills = $this->createPsychicalSkills(),
            $combinedSkills = $this->createCombinedSkills()
        );

        $this->assertNull($personSkills->getId());
        $this->assertSame($physicalSkills, $personSkills->getPhysicalSkills());
        $this->assertSame($psychicalSkills, $personSkills->getPsychicalSkills());
        $this->assertSame($combinedSkills, $personSkills->getCombinedSkills());
        $this->assertEquals([], $personSkills->getSkills());
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
     * @param string $professionCode
     * @return \Mockery\MockInterface|ProfessionLevels
     */
    private function createProfessionLevels($professionCode)
    {
        $professionLevels = $this->mockery(ProfessionLevels::class);
        $professionLevels->shouldReceive('getFirstLevel')
            ->andReturn($firstLevel = $this->mockery(ProfessionLevel::class));
        $firstLevel->shouldReceive('getProfession')
            ->andReturn($profession = $this->mockery(Profession::class));
        $profession->shouldReceive('getValue')
            ->andReturn($professionCode);
        $professionLevels->shouldReceive('getNextLevelsStrengthModifier')
            ->andReturn(0);
        $professionLevels->shouldReceive('getNextLevelsAgilityModifier')
            ->andReturn(0);
        $professionLevels->shouldReceive('getNextLevelsKnackModifier')
            ->andReturn(0);
        $professionLevels->shouldReceive('getNextLevelsWillModifier')
            ->andReturn(0);
        $professionLevels->shouldReceive('getNextLevelsIntelligenceModifier')
            ->andReturn(0);
        $professionLevels->shouldReceive('getNextLevelsCharismaModifier')
            ->andReturn(0);

        return $professionLevels;
    }

    /**
     * @param string $expectedProfessionCode
     * @return \Mockery\MockInterface|BackgroundSkillPoints
     */
    private function createBackgroundSkillPoints($expectedProfessionCode = null)
    {
        $backgroundSkillPoints = $this->mockery(BackgroundSkillPoints::class);
        if (!is_null($expectedProfessionCode)) {
            $backgroundSkillPoints->shouldReceive('getPhysicalSkillPoints')
                ->with($expectedProfessionCode, \Mockery::type(Tables::class))
                ->atLeast()->once();
            $backgroundSkillPoints->shouldReceive('getPsychicalSkillPoints')
                ->with($expectedProfessionCode, \Mockery::type(Tables::class))
                ->atLeast()->once();
            $backgroundSkillPoints->shouldReceive('getCombinedSkillPoints')
                ->with($expectedProfessionCode, \Mockery::type(Tables::class))
                ->atLeast()->once();
        }

        return $backgroundSkillPoints;
    }

    // TODO test returned skills

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

}
