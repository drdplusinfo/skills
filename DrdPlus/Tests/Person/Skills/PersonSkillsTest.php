<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Codes\PropertyCodes;
use DrdPlus\Codes\SkillCodes;
use DrdPlus\Person\Background\BackgroundParts\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\LevelRank;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\Skills\Combined\BigHandwork;
use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Person\Skills\Combined\Cooking;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkill;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\Physical\Athletics;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkill;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Person\Skills\Physical\Swimming;
use DrdPlus\Person\Skills\Psychical\Astronomy;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkill;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Person\Skills\Psychical\ReadingAndWriting;
use DrdPlus\Professions\Profession;
use DrdPlus\Tables\Tables;
use Granam\Tests\Tools\TestWithMockery;

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
        $personSkills = PersonSkills::createIt(
            $professionLevels,
            $backgroundSkillPoints,
            new Tables(),
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills
        );

        self::assertNull($personSkills->getId());
        self::assertSame($physicalSkills, $personSkills->getPhysicalSkills());
        self::assertSame($psychicalSkills, $personSkills->getPsychicalSkills());
        self::assertSame($combinedSkills, $personSkills->getCombinedSkills());
        self::assertEquals(
            $sortedExpectedSkills = $this->getSortedExpectedSkills(
                $physicalSkills->getIterator()->getArrayCopy(),
                $psychicalSkills->getIterator()->getArrayCopy(),
                $combinedSkills->getIterator()->getArrayCopy()
            ),
            $this->getSortedGivenSkills($personSkills)
        );
        self::assertSame(SkillCodes::getSkillCodes(), $personSkills->getCodesOfAllSkills());
        $learnedSkills = $personSkills->getCodesOfLearnedSkills();
        sort($learnedSkills);
        self::assertEquals(
            $expectedCodesOfLearnedSkills = array_map(
                function (PersonSkill $personSkill) {
                    return $personSkill->getName();
                },
                $sortedExpectedSkills
            ),
            $learnedSkills
        );
        self::assertNotEmpty($expectedCodesOfLearnedSkills);
        self::assertEquals(
            array_diff(SkillCodes::getSkillCodes(), $expectedCodesOfLearnedSkills),
            $personSkills->getCodesOfNotLearnedSkills()
        );
        self::assertEquals(
            $personSkills->getIterator()->getArrayCopy(),
            array_merge(
                $physicalSkills->getIterator()->getArrayCopy(),
                $psychicalSkills->getIterator()->getArrayCopy(),
                $combinedSkills->getIterator()->getArrayCopy()
            )
        );
    }

    public function provideValidSkillsCombination()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createBackgroundSkillPoints($professionLevels->getFirstLevel()->getProfession());
        $firstLevel = $professionLevels->getFirstLevel();
        $nextLevel = current($professionLevels->getNextLevels());

        return [
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
                $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
                $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel)
            ],
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createPhysicalSkillsPaidByOtherSkillPoints($backgroundSkillPoints, $firstLevel),
                $this->createPsychicalSkillsPaidByOtherSkillPoints($backgroundSkillPoints, $firstLevel),
                $this->createCombinedSkillsPaidByOtherSkillPoints($backgroundSkillPoints, $firstLevel)
            ],
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createPhysicalSkillsByNextLevelPropertyIncrease($nextLevel),
                $this->createPsychicalSkillsByNextLevelPropertyIncrease($nextLevel),
                $this->createCombinedSkillsByNextLevelPropertyIncrease($nextLevel)
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
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
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
        self::assertEquals(1, preg_match('~[\\\](?<basename>\w+)$~', $skillClass, $matches));
        $sutBasename = $matches['basename'];
        $underscored = preg_replace('~([a-z])([A-Z])~', '$1_$2', $sutBasename);
        $underscoredSingleLetters = preg_replace('~([A-Z])([A-Z])~', '$1_$2', $underscored);

        return strtolower($underscoredSingleLetters);
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
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getFirstPaidOtherSkillPoint')
            ->andReturn($firstPaidOtherSkillPoint = $this->mockery($this->determineSkillPointClass($firstOtherSkillClass)));
        $firstPaidOtherSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstPaidOtherSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstOtherSkillClass));
        $firstSkillPoint->shouldReceive('getSecondPaidOtherSkillPoint')
            ->andReturn($secondPaidOtherSkillPoint = $this->mockery($this->determineSkillPointClass($secondOtherSkillClass)));
        $secondPaidOtherSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
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
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
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
     * @param int $nextLevelsAgilityModifier = 0
     * @param int $nextLevelsKnackModifier = 1
     * @param int $nextLevelsWillModifier = 1
     * @param int $nextLevelsIntelligenceModifier = 0
     * @param int $nextLevelsCharismaModifier = 0
     * @return \Mockery\MockInterface|ProfessionLevels
     */
    private function createProfessionLevels(
        $professionCode = 'foo',
        $nextLevelsStrengthModifier = 1,
        $nextLevelsAgilityModifier = 0,
        $nextLevelsKnackModifier = 1,
        $nextLevelsWillModifier = 1,
        $nextLevelsIntelligenceModifier = 0,
        $nextLevelsCharismaModifier = 0
    )
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
            ->andReturn($nextLevelsAgilityModifier);
        $professionLevels->shouldReceive('getNextLevelsKnackModifier')
            ->andReturn($nextLevelsKnackModifier);
        $professionLevels->shouldReceive('getNextLevelsWillModifier')
            ->andReturn($nextLevelsWillModifier);
        $professionLevels->shouldReceive('getNextLevelsIntelligenceModifier')
            ->andReturn($nextLevelsIntelligenceModifier);
        $professionLevels->shouldReceive('getNextLevelsCharismaModifier')
            ->andReturn($nextLevelsCharismaModifier);

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
        $backgroundSkillPoints->shouldReceive('getSpentBackgroundPoints')
            ->andReturn($value);

        return $backgroundSkillPoints;
    }

    /**
     * @param array $physical
     * @param array $psychical
     * @param array $combined
     * @return array|PersonSkill[]
     */
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

        PersonSkills::createIt(
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
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByNextLevelPropertyIncrease')
            ->andReturn(false);

        return $skills;
    }

    /**
     * @test
     * @dataProvider provideDifferentBackgroundSkillPoints
     * @expectedException \DrdPlus\Person\Skills\Exceptions\BackgroundSkillPointsAreNotSame
     * @param ProfessionLevels $professionLevels
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param BackgroundSkillPoints $fromFirstSkillsBackgroundSkillPoints
     * @param BackgroundSkillPoints $fromOtherSkillsBackgroundSkillPoints
     */
    public function I_can_not_use_different_background_skill_points(
        ProfessionLevels $professionLevels,
        BackgroundSkillPoints $backgroundSkillPoints,
        BackgroundSkillPoints $fromFirstSkillsBackgroundSkillPoints,
        BackgroundSkillPoints $fromOtherSkillsBackgroundSkillPoints
    )
    {
        $physicalSkills = $this->createPhysicalSkillsWithDifferentBackground($fromFirstSkillsBackgroundSkillPoints, $professionLevels->getFirstLevel(), Swimming::class);
        $psychicalSkills = $this->createPsychicalSkillsPaidByFirstLevelBackground($fromOtherSkillsBackgroundSkillPoints, $professionLevels->getFirstLevel());
        $combinedSkills = $this->createCombinedSkillsPaidByFirstLevelBackground($fromOtherSkillsBackgroundSkillPoints, $professionLevels->getFirstLevel());

        PersonSkills::createIt(
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
     * @param $firstSkillClass
     * @return \Mockery\MockInterface|PersonPhysicalSkills
     */
    private function createPhysicalSkillsWithDifferentBackground(BackgroundSkillPoints $backgroundSkillPoints, ProfessionLevel $firstLevel, $firstSkillClass)
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
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints /*$this->createBackgroundSkillPoints(null, 'different points value')*/);

        return $skills;
    }

    public function provideDifferentBackgroundSkillPoints()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createBackgroundSkillPoints($professionLevels->getFirstLevel()->getProfession());
        $differentBackgroundSkillPoints = $this->createBackgroundSkillPoints(null, 'different points value');

        return [
            [$professionLevels, $backgroundSkillPoints, $differentBackgroundSkillPoints, $backgroundSkillPoints],
            [$professionLevels, $backgroundSkillPoints, $differentBackgroundSkillPoints, $differentBackgroundSkillPoints],
        ];
    }

    /**
     * @test
     * @dataProvider provideSkillsWithTooHighFirstLevelPayment
     * @expectedException \DrdPlus\Person\Skills\Exceptions\HigherSkillRanksFromFirstLevelThanPossible
     * @param ProfessionLevels $professionLevels
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param PersonPhysicalSkills $physicalSkills
     * @param PersonPsychicalSkills $psychicalSkills
     * @param PersonCombinedSkills $combinedSkills
     */
    public function I_can_not_spent_more_background_skill_points_than_available(
        ProfessionLevels $professionLevels,
        BackgroundSkillPoints $backgroundSkillPoints,
        PersonPhysicalSkills $physicalSkills,
        PersonPsychicalSkills $psychicalSkills,
        PersonCombinedSkills $combinedSkills
    )
    {
        PersonSkills::createIt(
            $professionLevels,
            $backgroundSkillPoints,
            new Tables(),
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills
        );
    }

    public function provideSkillsWithTooHighFirstLevelPayment()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createBackgroundSkillPoints(
            $professionLevels->getFirstLevel()->getProfession(), 'foo bar', 1, 1, 1
        );
        $firstLevel = $professionLevels->getFirstLevel();
        $physicalSkills = $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel);
        $psychicalSkills = $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel);
        $combinedSkills = $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel);

        return [
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createSkillsWithTooHighFirstLevelPayment(
                    $backgroundSkillPoints,
                    $professionLevels->getFirstLevel(),
                    Swimming::class,
                    Athletics::class
                ),
                $psychicalSkills,
                $combinedSkills
            ],
            [
                $professionLevels,
                $backgroundSkillPoints,
                $physicalSkills,
                $this->createSkillsWithTooHighFirstLevelPayment(
                    $backgroundSkillPoints,
                    $professionLevels->getFirstLevel(),
                    ReadingAndWriting::class,
                    Astronomy::class
                ),
                $combinedSkills
            ],
            [
                $professionLevels,
                $backgroundSkillPoints,
                $physicalSkills,
                $psychicalSkills,
                $this->createSkillsWithTooHighFirstLevelPayment(
                    $backgroundSkillPoints,
                    $professionLevels->getFirstLevel(),
                    Cooking::class,
                    BigHandwork::class
                ),
            ],
        ];
    }

    /**
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @param string $firstSkillClass
     * @param string $secondSkillClass
     * @return \Mockery\MockInterface|PersonPhysicalSkills
     */
    private function createSkillsWithTooHighFirstLevelPayment(
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
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
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
        $secondSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $secondSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $secondSkillPoint->shouldReceive('isPaidByFirstLevelBackgroundSkillPoints')
            ->andReturn(true);
        $secondSkillPoint->shouldReceive('getBackgroundSkillPoints')
            ->andReturn($backgroundSkillPoints);

        return $skills;
    }

    /**
     * @test
     * @dataProvider provideProfessionLevelsWithTooLowPropertyIncrease
     * @expectedException \DrdPlus\Person\Skills\Exceptions\HigherSkillRanksFromNextLevelsThanPossible
     * @param ProfessionLevels $professionLevels
     */
    public function I_can_not_increase_skills_by_next_levels_more_than_provides_property_increments(
        ProfessionLevels $professionLevels
    )
    {
        $nextLevel = current($professionLevels->getNextLevels());
        $physicalSkills = $this->createPhysicalSkillsByNextLevelPropertyIncrease($nextLevel);
        $psychicalSkills = $this->createPsychicalSkillsByNextLevelPropertyIncrease($nextLevel);
        $combinedSkills = $this->createCombinedSkillsByNextLevelPropertyIncrease($nextLevel);
        $backgroundSkillPoints = $this->createBackgroundSkillPoints($professionLevels->getFirstLevel()->getProfession());

        PersonSkills::createIt(
            $professionLevels,
            $backgroundSkillPoints,
            new Tables(),
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills
        );
    }

    public function provideProfessionLevelsWithTooLowPropertyIncrease()
    {
        return [
            [$this->createProfessionLevels('foo', 0, 0)], // physical properties
            [$this->createProfessionLevels('foo', 1, 1, 1, 0, 0)], // psychical properties
            [$this->createProfessionLevels('foo', 1, 1, 0, 1, 1, 0)], // combined properties
        ];
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

        PersonSkills::createIt(
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
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
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
        $secondSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
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
