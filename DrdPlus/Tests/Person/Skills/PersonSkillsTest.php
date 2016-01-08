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
        $professionLevels = $this->createProfessionLevels();
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
                $this->createPhysicalSkillsByNextLevelPropertyIncrease(current($professionLevels->getNextLevels())),
                $this->createPsychicalSkillsByNextLevelPropertyIncrease(current($professionLevels->getNextLevels())),
                $this->createCombinedSkillsByNextLevelPropertyIncrease(current($professionLevels->getNextLevels()))
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
     * @return PersonPsychicalSkills
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
     * @return PersonCombinedSkills
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
     * @return PersonPhysicalSkills
     */
    private function createPhysicalSkillsByNextLevelPropertyIncrease(ProfessionLevel $nextLevel)
    {
        $physicalSkillPoints = $this->createSkillsByNextLevelPropertyIncrease($nextLevel, Athletics::class);

        return $physicalSkillPoints;
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @return PersonPsychicalSkills
     */
    private function createPsychicalSkillsByNextLevelPropertyIncrease(ProfessionLevel $nextLevel)
    {
        $psychicalSkillPoints = $this->createSkillsByNextLevelPropertyIncrease($nextLevel, ReadingAndWriting::class);

        return $psychicalSkillPoints;
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @return PersonCombinedSkills
     */
    private function createCombinedSkillsByNextLevelPropertyIncrease(ProfessionLevel $nextLevel)
    {
        $combinedSkills = $this->createSkillsByNextLevelPropertyIncrease($nextLevel, Cooking::class);

        return $combinedSkills;
    }

    private function createSkillsByNextLevelPropertyIncrease(ProfessionLevel $nextLevel, $skillClass)
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
     * @param int $nextLevelsStrengthModifier = 1
     * @return \Mockery\MockInterface|ProfessionLevels
     */
    private function createProfessionLevels($professionCode = 'foo', $nextLevelsStrengthModifier = 1)
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
            ->andReturn($nextLevelsStrengthModifier);
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
     * @param Profession|null $profession
     * @param mixed $value = 'foo bar'
     * @param $physicalSkillPoints = 3
     * @param $psychicalSkillPoints = 3
     * @param $combinedSkillPoints = 3
     * @return \Mockery\MockInterface|BackgroundSkillPoints
     */
    private function createBackgroundSkillPoints(
        Profession $profession = null,
        $value = 'foo bar',
        $physicalSkillPoints = 3,
        $psychicalSkillPoints = 3,
        $combinedSkillPoints = 3
    )
    {
        $backgroundSkillPoints = $this->mockery(BackgroundSkillPoints::class);
        if ($profession) {
            $backgroundSkillPoints->shouldReceive('getPhysicalSkillPoints')
                ->with($profession, \Mockery::type(Tables::class))
                ->andReturn($physicalSkillPoints);
            $backgroundSkillPoints->shouldReceive('getPsychicalSkillPoints')
                ->with($profession, \Mockery::type(Tables::class))
                ->andReturn($psychicalSkillPoints);
            $backgroundSkillPoints->shouldReceive('getCombinedSkillPoints')
                ->with($profession, \Mockery::type(Tables::class))
                ->andReturn($combinedSkillPoints);
        }
        $backgroundSkillPoints->shouldReceive('getBackgroundPointsValue')
            ->andReturn($value);

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

    // NEGATIVE TESTS

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    public function I_can_not_use_unknown_payment()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createBackgroundSkillPoints($professionLevels->getFirstLevel()->getProfession());
        $physicalSkills = $this->createPhysicalSkillsWithUnknownPayment($professionLevels->getFirstLevel(), Swimming::class);
        $psychicalSkills = $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel());
        $combinedSkills = $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel());

        PersonSkills::getIt(
            $professionLevels,
            $backgroundSkillPoints,
            new Tables(),
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills
        );
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param $firstSkillClass
     * @return \Mockery\MockInterface|PersonPhysicalSkills
     */
    private function createPhysicalSkillsWithUnknownPayment(ProfessionLevel $firstLevel, $firstSkillClass)
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
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByNextLevelPropertyIncrease')
            ->andReturn(false);

        return $skills;
    }

    /**
     * TODO test all the cases where same background should be checked
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\BackgroundSkillPointsAreNotSame
     */
    public function I_can_not_use_different_background_skill_points()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createBackgroundSkillPoints($professionLevels->getFirstLevel()->getProfession());
        $physicalSkills = $this->createPhysicalSkillsWithDifferentBackground($professionLevels->getFirstLevel(), Swimming::class);
        $psychicalSkills = $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel());
        $combinedSkills = $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel());

        PersonSkills::getIt(
            $professionLevels,
            $backgroundSkillPoints,
            new Tables(),
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills
        );
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param $firstSkillClass
     * @return \Mockery\MockInterface|PersonPhysicalSkills
     */
    private function createPhysicalSkillsWithDifferentBackground(ProfessionLevel $firstLevel, $firstSkillClass)
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
            ->andReturn($this->createBackgroundSkillPoints(null, 'different points value'));

        return $skills;
    }

    /**
     * TODO test all three types
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\HigherSkillRanksFromFirstLevelThanPossible
     */
    public function I_can_not_spent_more_background_skill_points_than_available()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createBackgroundSkillPoints(
            $professionLevels->getFirstLevel()->getProfession(), 'foo bar', 3
        );
        $physicalSkills = $this->createPhysicalSkillsWithTooHighFirstLevelPayment(
            $backgroundSkillPoints,
            $professionLevels->getFirstLevel(),
            Swimming::class,
            Athletics::class
        );
        $psychicalSkills = $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel());
        $combinedSkills = $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel());

        PersonSkills::getIt(
            $professionLevels,
            $backgroundSkillPoints,
            new Tables(),
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills
        );
    }

    /**
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @param string $firstSkillClass
     * @param string $secondSkillClass
     * @return \Mockery\MockInterface|PersonPhysicalSkills
     */
    private function createPhysicalSkillsWithTooHighFirstLevelPayment(
        BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel, $firstSkillClass, $secondSkillClass
    )
    {
        $skillsClass = $this->determineSkillsClass($firstSkillClass);
        $skills = $this->mockery($skillsClass);
        $skills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator([
                $firstSkill = $this->mockery(PersonSkill::class),
                $secondSkill = $this->mockery(PersonSkill::class),
            ]));
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
        $secondSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($secondSkillClass));
        $secondSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $secondSkillRank = $this->mockery(PersonSkillRank::class)
            ]);
        $secondSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $secondSkillRank->shouldReceive('getPersonSkillPoint')
            ->andReturn($secondSkillPoint = $this->mockery($this->determineSkillPointClass($secondSkillClass)));
        $secondSkillPoint->shouldReceive('getTypeName')
            ->andReturn(PhysicalSkillPoint::PHYSICAL);
        $secondSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $secondSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);

        return $skills;
    }

    /**
     * TODO test other types as well
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\HigherSkillRanksFromNextLevelsThanPossible
     */
    public function I_can_not_increase_skills_by_next_levels_more_than_provides_property_increments()
    {
        $professionLevels = $this->createProfessionLevels('foo', 0);
        $physicalSkills = $this->createPhysicalSkillsByNextLevelPropertyIncrease(current($professionLevels->getNextLevels()));
        $psychicalSkills = $this->createPsychicalSkillsByNextLevelPropertyIncrease($professionLevels->getFirstLevel());
        $combinedSkills = $this->createCombinedSkillsByNextLevelPropertyIncrease($professionLevels->getFirstLevel());
        $backgroundSkillPoints = $this->createBackgroundSkillPoints($professionLevels->getFirstLevel()->getProfession());

        PersonSkills::getIt(
            $professionLevels,
            $backgroundSkillPoints,
            new Tables(),
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Skills\Exceptions\TooHighSingleSkillIncrementPerNextLevel
     */
    public function I_can_not_increase_same_skill_more_than_once_per_next_level()
    {
        $professionLevels = $this->createProfessionLevels('foo', 2);
        $physicalSkills = $this->createPhysicalSkillsWithTooHighSkillIncrementPerNextLevel(current($professionLevels->getNextLevels()), Swimming::class);
        $psychicalSkills = $this->createPsychicalSkillsByNextLevelPropertyIncrease($professionLevels->getFirstLevel());
        $combinedSkills = $this->createCombinedSkillsByNextLevelPropertyIncrease($professionLevels->getFirstLevel());
        $backgroundSkillPoints = $this->createBackgroundSkillPoints($professionLevels->getFirstLevel()->getProfession());

        PersonSkills::getIt(
            $professionLevels,
            $backgroundSkillPoints,
            new Tables(),
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills
        );
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @param string $skillClass
     * @return PersonPhysicalSkills
     */
    private function createPhysicalSkillsWithTooHighSkillIncrementPerNextLevel(ProfessionLevel $nextLevel, $skillClass)
    {

        $kills = $this->mockery($this->determineSkillsClass($skillClass));
        $kills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator([
                $firstSkill = $this->mockery(PersonSkill::class),
            ]));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($skillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $skillFirstRank = $this->mockery(PersonSkillRank::class),
                $skillSecondRank = $this->mockery(PersonSkillRank::class)
            ]);
        $skillFirstRank->shouldReceive('getProfessionLevel')
            ->andReturn($nextLevel);
        $skillFirstRank->shouldReceive('getValue')
            ->andReturn(1);
        $skillFirstRank->shouldReceive('getPersonSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($skillClass)));
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($skillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByNextLevelPropertyIncrease')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getRelatedProperties')
            ->andReturn($this->determineRelatedProperties($skillClass));
        $skillSecondRank->shouldReceive('getProfessionLevel')
            ->andReturn($nextLevel);
        $skillSecondRank->shouldReceive('getValue')
            ->andReturn(2);
        $skillSecondRank->shouldReceive('getPersonSkillPoint')
            ->andReturn($secondSkillPoint = $this->mockery($this->determineSkillPointClass($skillClass)));
        $secondSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($skillClass));
        $secondSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(false);
        $secondSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(false);
        $secondSkillPoint->shouldReceive('isPaidByNextLevelPropertyIncrease')
            ->andReturn(true);
        $secondSkillPoint->shouldReceive('getRelatedProperties')
            ->andReturn($this->determineRelatedProperties($skillClass));

        return $kills;
    }
}
