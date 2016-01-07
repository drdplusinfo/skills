<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Codes\PropertyCodes;
use DrdPlus\Person\Background\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\LevelRank;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Person\Skills\Combined\Cooking;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkill;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\Physical\Athletics;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkill;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Person\Skills\Physical\Swimming;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkill;
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
     * @dataProvider provideValidSkillsCombination
     * @param ProfessionLevels $professionLevels
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param PersonPhysicalSkills $physicalSkills
     * @param PersonPsychicalSkills $psychicalSkills
     * @param PersonCombinedSkills $combinedSkills
     */
    public function I_can_create_it(
        ProfessionLevels $professionLevels,
        BackgroundSkillPoints $backgroundSkillPoints,
        PersonPhysicalSkills $physicalSkills,
        PersonPsychicalSkills $psychicalSkills,
        PersonCombinedSkills $combinedSkills
    )
    {
        $personSkills = PersonSkills::getIt(
            $professionLevels,
            $backgroundSkillPoints,
            new Tables(),
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills
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

    public function provideValidSkillsCombination()
    {
        $professionLevels = $this->createProfessionLevels('foo');
        $backgroundSkillPoints = $this->createBackgroundSkillPoints($professionLevels->getFirstLevel()->getProfession());

        return [
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel()),
                $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel()),
                $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel())
            ],
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createPhysicalSkillsPaidByOtherSkillPoints($backgroundSkillPoints, $professionLevels->getFirstLevel()),
                $this->createPsychicalSkillsPaidByOtherSkillPoints($backgroundSkillPoints, $professionLevels->getFirstLevel()),
                $this->createCombinedSkillsPaidByOtherSkillPoints($backgroundSkillPoints, $professionLevels->getFirstLevel())
            ],
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createPhysicalSkillsByNextLevelPropertyIncrease($backgroundSkillPoints, current($professionLevels->getNextLevels())),
                $this->createPsychicalSkillsByNextLevelPropertyIncrease($backgroundSkillPoints, current($professionLevels->getNextLevels())),
                $this->createCombinedSkillsByNextLevelPropertyIncrease($backgroundSkillPoints, current($professionLevels->getNextLevels()))
            ],
        ];
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @return PersonPhysicalSkills
     * */
    private function createPhysicalSkillsPaidByFirstLevelBackground(
        BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel
    )
    {
        $physicalSkills = $this->createSkillsPaidByFirstLevelBackground(
            $backgroundSkillPoints, $firstLevel, Swimming::class
        );

        return $physicalSkills;
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @return PersonPhysicalSkills
     * */
    private function createPsychicalSkillsPaidByFirstLevelBackground(
        BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel
    )
    {
        $psychicalSkills = $this->createSkillsPaidByFirstLevelBackground(
            $backgroundSkillPoints, $firstLevel, ReadingAndWriting::class
        );

        return $psychicalSkills;
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @return PersonPhysicalSkills
     * */
    private function createCombinedSkillsPaidByFirstLevelBackground(
        BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel
    )
    {
        $combinedSkills = $this->createSkillsPaidByFirstLevelBackground(
            $backgroundSkillPoints, $firstLevel, Cooking::class
        );

        return $combinedSkills;
    }

    private function createSkillsPaidByFirstLevelBackground(
        BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel, $firstSkillClass
    )
    {
        $skillsClass = $this->determineSkillsClass($firstSkillClass);
        $skills = $this->mockery($skillsClass);
        $skills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(PersonSkill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($firstSkillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(PersonSkillRank::class)
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getPersonSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($firstSkillClass)));
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn(PhysicalSkillPoint::PHYSICAL);
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);

        return $skills;
    }

    private function determineSkillsClass($skillClass)
    {
        if (is_a($skillClass, PersonPhysicalSkill::class, true)) {
            return PersonPhysicalSkills::class;
        }
        if (is_a($skillClass, PersonPsychicalSkill::class, true)) {
            return PersonPsychicalSkills::class;
        }
        if (is_a($skillClass, PersonCombinedSkill::class, true)) {
            return PersonCombinedSkills::class;
        }
        throw new \LogicException;
    }

    /**
     * @param string $skillClass
     * @return string
     */
    private function parseSkillName($skillClass)
    {
        $this->assertEquals(1, preg_match('~[\\\](?<basename>\w+)$~', $skillClass, $matches));
        $sutBasename = $matches['basename'];
        $underscored = preg_replace('~([a-z])([A-Z])~', '$1_$2', $sutBasename);
        $underscoredSingleLetters = preg_replace('~([A-Z])([A-Z])~', '$1_$2', $underscored);
        $name = strtolower($underscoredSingleLetters);

        return $name;
    }

    private function determineSkillPointClass($skillClass)
    {
        if (is_a($skillClass, PersonPhysicalSkill::class, true)) {
            return PhysicalSkillPoint::class;
        }
        if (is_a($skillClass, PersonPsychicalSkill::class, true)) {
            return PsychicalSkillPoint::class;
        }
        if (is_a($skillClass, PersonCombinedSkill::class, true)) {
            return CombinedSkillPoint::class;
        }
        throw new \LogicException;
    }

    /**
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @return PersonPsychicalSkills
     */
    private function createPhysicalSkillsPaidByOtherSkillPoints(BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel)
    {
        $psychicalSkills = $this->createSkillsPaidByOtherSkillPoints(
            $backgroundSkillPoints, $firstLevel, Athletics::class, Cooking::class, ReadingAndWriting::class
        );

        return $psychicalSkills;
    }

    /**
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @return PersonPsychicalSkills
     */
    private function createPsychicalSkillsPaidByOtherSkillPoints(BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel)
    {
        $psychicalSkills = $this->createSkillsPaidByOtherSkillPoints(
            $backgroundSkillPoints, $firstLevel, ReadingAndWriting::class, Cooking::class, Athletics::class
        );

        return $psychicalSkills;
    }

    /**
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @return PersonPsychicalSkills
     */
    private function createCombinedSkillsPaidByOtherSkillPoints(BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel)
    {
        $psychicalSkills = $this->createSkillsPaidByOtherSkillPoints(
            $backgroundSkillPoints, $firstLevel, Cooking::class, ReadingAndWriting::class, Athletics::class
        );

        return $psychicalSkills;
    }

    private function createSkillsPaidByOtherSkillPoints(
        BackgroundSkillPoints $backgroundSkillPoints,
        ProfessionLevel $firstLevel,
        $firstSkillClass,
        $firstOtherSkillClass,
        $secondOtherSkillClass
    )
    {
        $skills = $this->mockery($this->determineSkillsClass($firstSkillClass));
        $skills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(PersonSkill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($firstSkillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(PersonSkillRank::class)
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getPersonSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($firstSkillClass)));
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getFirstPaidOtherSkillPoint')
            ->andReturn($firstPaidOtherSkillPoint = $this->mockery($this->determineSkillPointClass($firstOtherSkillClass)));
        $firstPaidOtherSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstOtherSkillClass));
        $firstSkillPoint->shouldReceive('getSecondPaidOtherSkillPoint')
            ->andReturn($secondPaidOtherSkillPoint = $this->mockery($this->determineSkillPointClass($secondOtherSkillClass)));
        $secondPaidOtherSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($secondOtherSkillClass));
        $firstPaidOtherSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $secondPaidOtherSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $secondPaidOtherSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);
        $firstPaidOtherSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);

        return $skills;
    }

    private function determineSkillTypeName($skillClass)
    {
        if (is_a($skillClass, PersonPhysicalSkill::class, true)) {
            return PhysicalSkillPoint::PHYSICAL;
        }
        if (is_a($skillClass, PersonPsychicalSkill::class, true)) {
            return PsychicalSkillPoint::PSYCHICAL;
        }
        if (is_a($skillClass, PersonCombinedSkill::class, true)) {
            return CombinedSkillPoint::COMBINED;
        }
        throw new \LogicException;
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @return PersonCombinedSkills
     */
    private function createPhysicalSkillsByNextLevelPropertyIncrease(
        BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $nextLevel
    )
    {
        $physicalSkillPoints = $this->createSkillsByNextLevelPropertyIncrease(
            $backgroundSkillPoints, $nextLevel, Athletics::class
        );

        return $physicalSkillPoints;
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @return PersonCombinedSkills
     */
    private function createPsychicalSkillsByNextLevelPropertyIncrease(
        BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $nextLevel
    )
    {
        $psychicalSkillPoints = $this->createSkillsByNextLevelPropertyIncrease(
            $backgroundSkillPoints, $nextLevel, ReadingAndWriting::class
        );

        return $psychicalSkillPoints;
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @return PersonCombinedSkills
     */
    private function createCombinedSkillsByNextLevelPropertyIncrease(
        BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $nextLevel
    )
    {
        $combinedSkills = $this->createSkillsByNextLevelPropertyIncrease(
            $backgroundSkillPoints, $nextLevel, Cooking::class
        );

        return $combinedSkills;
    }

    private function createSkillsByNextLevelPropertyIncrease(
        BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $nextLevel, $skillClass
    )
    {
        $kills = $this->mockery($this->determineSkillsClass($skillClass));
        $kills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(PersonSkill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($skillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $nextLevelSkillRank = $this->mockery(PersonSkillRank::class)
            ]);
        $nextLevelSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($nextLevel);
        $nextLevelSkillRank->shouldReceive('getPersonSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($skillClass)));
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($skillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByNextLevelPropertyIncrease')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);
        $firstSkillPoint->shouldReceive('getRelatedProperties')
            ->andReturn($this->determineRelatedProperties($skillClass));

        return $kills;
    }

    private function determineRelatedProperties($skillClass)
    {
        if (is_a($skillClass, PersonPhysicalSkill::class, true)) {
            return [PropertyCodes::STRENGTH, PropertyCodes::AGILITY];
        }
        if (is_a($skillClass, PersonPsychicalSkill::class, true)) {
            return [PropertyCodes::WILL, PropertyCodes::INTELLIGENCE];
        }
        if (is_a($skillClass, PersonCombinedSkill::class, true)) {
            return [PropertyCodes::KNACK, PropertyCodes::CHARISMA];
        }
        throw new \LogicException;
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
        $professionLevels->shouldReceive('getNextLevels')
            ->andReturn([$nextLevel = $this->mockery(ProfessionLevel::class)]);
        $nextLevel->shouldReceive('isFirstLevel')
            ->andReturn(false);
        $nextLevel->shouldReceive('isNextLevel')
            ->andReturn(true);
        $nextLevel->shouldReceive('getProfession')
            ->andReturn($profession = $this->mockery(Profession::class));
        $nextLevel->shouldReceive('getLevelRank')
            ->andReturn($nextLevelRank = $this->mockery(LevelRank::class));
        $nextLevelRank->shouldReceive('getValue')
            ->andReturn(2);
        $professionLevels->shouldReceive('getNextLevelsStrengthModifier')
            ->andReturn(1);
        $professionLevels->shouldReceive('getNextLevelsAgilityModifier')
            ->andReturn(0);
        $professionLevels->shouldReceive('getNextLevelsKnackModifier')
            ->andReturn(1);
        $professionLevels->shouldReceive('getNextLevelsWillModifier')
            ->andReturn(1);
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
                ->andReturn(3);
            $backgroundSkillPoints->shouldReceive('getCombinedSkillPoints')
                ->with($profession, \Mockery::type(Tables::class))
                ->andReturn(3);
        }
        $backgroundSkillPoints->shouldReceive('getBackgroundPointsValue')
            ->andReturn('foo bar');

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
