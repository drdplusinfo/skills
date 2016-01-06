<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Person\Background\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Person\Skills\Combined\FirstAid;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Person\Skills\Physical\Swimming;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Person\Skills\Psychical\ReadingAndWriting;
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
            $professionLevels = $this->createProfessionLevels('foo'),
            $backgroundSkillPoints = $this->createBackgroundSkillPoints(
                $profession = $professionLevels->getFirstLevel()->getProfession()
            ),
            new Tables(),
            $physicalSkills = $this->createPhysicalSkills($backgroundSkillPoints, $professionLevels->getFirstLevel()),
            $psychicalSkills = $this->createPsychicalSkills($backgroundSkillPoints, $professionLevels->getFirstLevel()),
            $combinedSkills = $this->createCombinedSkills($backgroundSkillPoints, $professionLevels->getFirstLevel())
        );

        $this->assertNull($personSkills->getId());
        $this->assertSame($physicalSkills, $personSkills->getPhysicalSkills());
        $this->assertSame($psychicalSkills, $personSkills->getPsychicalSkills());
        $this->assertSame($combinedSkills, $personSkills->getCombinedSkills());
        $this->assertEquals(
            $this->getSortedExpectedSkills(
                $physicalSkills->getIterator()->getArrayCopy(),
                $psychicalSkills->getIterator()->getArrayCopy(),
                $combinedSkills->getIterator()->getArrayCopy()
            ),
            $this->getSortedGivenSkills($personSkills)
        );
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @return PersonPhysicalSkills
     * */
    private function createPhysicalSkills(BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel)
    {
        $physicalSkills = $this->mockery(PersonPhysicalSkills::class);
        $physicalSkills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(PersonSkill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn(Swimming::SWIMMING);
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(PersonSkillRank::class)
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getPersonSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery(PhysicalSkillPoint::class));
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn(PhysicalSkillPoint::PHYSICAL);
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);

        return $physicalSkills;
    }

    /**
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @return PersonPsychicalSkills
     */
    private function createPsychicalSkills(BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel)
    {
        $psychicalSkills = $this->mockery(PersonPsychicalSkills::class);
        $psychicalSkills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(PersonSkill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn(ReadingAndWriting::READING_AND_WRITING);
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(PersonSkillRank::class)
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getPersonSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery(PsychicalSkillPoint::class));
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn(PsychicalSkillPoint::PSYCHICAL);
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getFirstPaidOtherSkillPoint')
            ->andReturn($firstPaidOtherSkillPoint = $this->mockery(PsychicalSkillPoint::class));
        $firstPaidOtherSkillPoint->shouldReceive('getTypeName')
            ->andReturn(PhysicalSkillPoint::PHYSICAL);
        $firstSkillPoint->shouldReceive('getSecondPaidOtherSkillPoint')
            ->andReturn($secondPaidOtherSkillPoint = $this->mockery(PsychicalSkillPoint::class));
        $secondPaidOtherSkillPoint->shouldReceive('getTypeName')
            ->andReturn(PhysicalSkillPoint::PHYSICAL);
        $firstPaidOtherSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $secondPaidOtherSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $secondPaidOtherSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);
        $firstPaidOtherSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);

        return $psychicalSkills;
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @return PersonCombinedSkills
     */
    private function createCombinedSkills(BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel)
    {
        $combinedSkills = $this->mockery(PersonCombinedSkills::class);
        $combinedSkills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(PersonSkill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn(FirstAid::FIRST_AID);
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(PersonSkillRank::class)
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getPersonSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery(CombinedSkillPoint::class));
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn(CombinedSkillPoint::COMBINED);
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);

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
        $firstLevel->shouldReceive('isFirstLevel')
            ->andReturn(true);
        $firstLevel->shouldReceive('isNextLevel')
            ->andReturn(false);
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
     * @param Profession $profession
     * @return \Mockery\MockInterface|BackgroundSkillPoints
     */
    private function createBackgroundSkillPoints(Profession $profession = null)
    {
        $backgroundSkillPoints = $this->mockery(BackgroundSkillPoints::class);
        if ($profession) {
            $backgroundSkillPoints->shouldReceive('getPhysicalSkillPoints')
                ->with($profession, \Mockery::type(Tables::class))
                ->andReturn(3);
            $backgroundSkillPoints->shouldReceive('getPsychicalSkillPoints')
                ->with($profession, \Mockery::type(Tables::class))
                ->andReturn(1);
            $backgroundSkillPoints->shouldReceive('getCombinedSkillPoints')
                ->with($profession, \Mockery::type(Tables::class))
                ->andReturn(1);
        }
        $backgroundSkillPoints->shouldReceive('getBackgroundPointsValue')
            ->andReturn(8);

        return $backgroundSkillPoints;
    }

    private function getSortedExpectedSkills(array $physical, array $psychical, array $combined)
    {
        $expectedSkills = array_merge($physical, $psychical, $combined);
        usort($expectedSkills, function (PersonSkill $firstSkill, PersonSkill $secondSkill) {
            return strcmp($firstSkill->getName(), $secondSkill->getName());
        });

        return $expectedSkills;
    }

    private function getSortedGivenSkills(PersonSkills $personSkills)
    {
        $givenSkills = $personSkills->getSkills();
        usort($givenSkills, function (PersonSkill $firstSkill, PersonSkill $secondSkill) {
            return strcmp($firstSkill->getName(), $secondSkill->getName());
        });

        return $givenSkills;
    }

}
