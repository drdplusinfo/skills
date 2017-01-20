<?php
namespace DrdPlus\Skills;

use Doctrine\Common\Collections\ArrayCollection;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponCode;
use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Codes\PropertyCode;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Background\BackgroundParts\SkillsFromBackground;
use DrdPlus\Background\BackgroundParts\Ancestry;
use DrdPlus\Person\ProfessionLevels\LevelRank;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\ProfessionLevels\ProfessionNextLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionZeroLevel;
use DrdPlus\Professions\Commoner;
use DrdPlus\Professions\Fighter;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Skills\Combined\BigHandwork;
use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\Combined\Cooking;
use DrdPlus\Skills\Combined\CombinedSkill;
use DrdPlus\Skills\Combined\CombinedSkills;
use DrdPlus\Skills\Physical\Athletics;
use DrdPlus\Skills\Physical\PhysicalSkill;
use DrdPlus\Skills\Physical\PhysicalSkills;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\Physical\Swimming;
use DrdPlus\Skills\Psychical\Astronomy;
use DrdPlus\Skills\Psychical\PsychicalSkill;
use DrdPlus\Skills\Psychical\PsychicalSkills;
use DrdPlus\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Skills\Psychical\ReadingAndWriting;
use DrdPlus\Professions\Profession;
use DrdPlus\Tables\Armaments\Armourer;
use DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable;
use DrdPlus\Tables\Armaments\Weapons\WeaponSkillTable;
use DrdPlus\Tables\History\SkillsByBackgroundPointsTable;
use DrdPlus\Tables\Tables;
use Granam\Integer\PositiveIntegerObject;
use Granam\Tests\Tools\TestWithMockery;

class SkillsTest extends TestWithMockery
{

    /**
     * @test
     * @dataProvider provideValidSkillsCombination
     * @param ProfessionLevels $professionLevels
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param PhysicalSkills $physicalSkills
     * @param PsychicalSkills $psychicalSkills
     * @param CombinedSkills $combinedSkills
     */
    public function I_can_create_it(
        ProfessionLevels $professionLevels,
        SkillsFromBackground $backgroundSkillPoints,
        PhysicalSkills $physicalSkills,
        PsychicalSkills $psychicalSkills,
        CombinedSkills $combinedSkills
    )
    {
        $skills = Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills,
            Tables::getIt()
        );

        self::assertNull($skills->getId());
        self::assertSame($physicalSkills, $skills->getPhysicalSkills());
        self::assertSame($psychicalSkills, $skills->getPsychicalSkills());
        self::assertSame($combinedSkills, $skills->getCombinedSkills());
        self::assertEquals(
            $sortedExpectedSkills = $this->getSortedExpectedSkills(
                $physicalSkills->getIterator()->getArrayCopy(),
                $psychicalSkills->getIterator()->getArrayCopy(),
                $combinedSkills->getIterator()->getArrayCopy()
            ),
            $this->getSortedGivenSkills($skills)
        );
        self::assertSame(
            array_merge(
                PhysicalSkillCode::getPossibleValues(),
                PsychicalSkillCode::getPossibleValues(),
                CombinedSkillCode::getPossibleValues()
            ),
            $skills->getCodesOfAllSkills()
        );
        $learnedSkills = $skills->getCodesOfLearnedSkills();
        sort($learnedSkills);
        self::assertEquals(
            $expectedCodesOfLearnedSkills = array_map(
                function (Skill $skill) {
                    return $skill->getName();
                },
                $sortedExpectedSkills
            ),
            $learnedSkills
        );
        self::assertNotEmpty($expectedCodesOfLearnedSkills);
        self::assertEquals(
            array_diff($this->getAllSkillCodes(), $expectedCodesOfLearnedSkills),
            $skills->getCodesOfNotLearnedSkills()
        );
        self::assertEquals(
            $skills->getIterator()->getArrayCopy(),
            array_merge(
                $physicalSkills->getIterator()->getArrayCopy(),
                $psychicalSkills->getIterator()->getArrayCopy(),
                $combinedSkills->getIterator()->getArrayCopy()
            )
        );
        self::assertCount(count($sortedExpectedSkills), $skills);
    }

    /**
     * @return array|string[]
     */
    private function getAllSkillCodes()
    {
        return array_merge(
            PhysicalSkillCode::getPossibleValues(),
            PsychicalSkillCode::getPossibleValues(),
            CombinedSkillCode::getPossibleValues()
        );
    }

    public function provideValidSkillsCombination()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground(
            $professionLevels->getFirstLevel()->getProfession()
        );
        $firstLevel = $professionLevels->getFirstLevel();
        $nextLevel = $professionLevels->getProfessionNextLevels()->last();

        return [
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
                $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
                $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            ],
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createPhysicalSkillsPaidByOtherSkillPoints($backgroundSkillPoints, $firstLevel),
                $this->createPsychicalSkillsPaidByOtherSkillPoints($backgroundSkillPoints, $firstLevel),
                $this->createCombinedSkillsPaidByOtherSkillPoints($backgroundSkillPoints, $firstLevel),
            ],
            [
                $professionLevels,
                $backgroundSkillPoints,
                $this->createPhysicalSkillsByNextLevelPropertyIncrease($nextLevel),
                $this->createPsychicalSkillsByNextLevelPropertyIncrease($nextLevel),
                $this->createCombinedSkillsByNextLevelPropertyIncrease($nextLevel),
            ],
        ];
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param SkillsFromBackground $backgroundSkillPoints
     * @return \Mockery\MockInterface|PhysicalSkills
     * */
    private function createPhysicalSkillsPaidByFirstLevelBackground(
        SkillsFromBackground $backgroundSkillPoints,
        ProfessionLevel $firstLevel
    )
    {
        $physicalSkills = $this->createSkillsPaidByFirstLevelBackground(
            $backgroundSkillPoints, $firstLevel, Swimming::class
        );

        return $physicalSkills;
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param SkillsFromBackground $backgroundSkillPoints
     * @return PsychicalSkills|\Mockery\MockInterface
     * */
    private function createPsychicalSkillsPaidByFirstLevelBackground(
        SkillsFromBackground $backgroundSkillPoints,
        ProfessionLevel $firstLevel
    )
    {
        $psychicalSkills = $this->createSkillsPaidByFirstLevelBackground(
            $backgroundSkillPoints, $firstLevel, ReadingAndWriting::class
        );

        return $psychicalSkills;
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param SkillsFromBackground $backgroundSkillPoints
     * @return CombinedSkills|\Mockery\MockInterface
     * */
    private function createCombinedSkillsPaidByFirstLevelBackground(
        SkillsFromBackground $backgroundSkillPoints,
        ProfessionLevel $firstLevel
    )
    {
        $combinedSkills = $this->createSkillsPaidByFirstLevelBackground(
            $backgroundSkillPoints,
            $firstLevel,
            Cooking::class
        );

        return $combinedSkills;
    }

    /**
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @param string $firstSkillClass
     * @return \Mockery\MockInterface|CombinedSkills
     */
    private function createSkillsPaidByFirstLevelBackground(
        SkillsFromBackground $backgroundSkillPoints,
        ProfessionLevel $firstLevel,
        $firstSkillClass
    )
    {
        $skillsClass = $this->determineSkillsClass($firstSkillClass);
        $skills = $this->mockery($skillsClass);
        $skills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(Skill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($firstSkillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(SkillRank::class),
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($firstSkillClass)));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getSkillsFromBackground')
            ->andReturn($backgroundSkillPoints);

        return $skills;
    }

    /**
     * @param string $skillClass
     * @return string
     * @throws \LogicException
     */
    private function determineSkillsClass($skillClass)
    {
        if (is_a($skillClass, PhysicalSkill::class, true)) {
            return PhysicalSkills::class;
        }
        if (is_a($skillClass, PsychicalSkill::class, true)) {
            return PsychicalSkills::class;
        }
        if (is_a($skillClass, CombinedSkill::class, true)) {
            return CombinedSkills::class;
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
        if (is_a($skillClass, PhysicalSkill::class, true)) {
            return PhysicalSkillPoint::class;
        }
        if (is_a($skillClass, PsychicalSkill::class, true)) {
            return PsychicalSkillPoint::class;
        }
        if (is_a($skillClass, CombinedSkill::class, true)) {
            return CombinedSkillPoint::class;
        }
        throw new \LogicException;
    }

    /**
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @return PsychicalSkills
     */
    private function createPhysicalSkillsPaidByOtherSkillPoints(SkillsFromBackground $backgroundSkillPoints, ProfessionLevel $firstLevel)
    {
        $psychicalSkills = $this->createSkillsPaidByOtherSkillPoints(
            $backgroundSkillPoints, $firstLevel, Athletics::class, Cooking::class, ReadingAndWriting::class
        );

        return $psychicalSkills;
    }

    /**
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @return PsychicalSkills
     */
    private function createPsychicalSkillsPaidByOtherSkillPoints(SkillsFromBackground $backgroundSkillPoints, ProfessionLevel $firstLevel)
    {
        $psychicalSkills = $this->createSkillsPaidByOtherSkillPoints(
            $backgroundSkillPoints, $firstLevel, ReadingAndWriting::class, Cooking::class, Athletics::class
        );

        return $psychicalSkills;
    }

    /**
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @return PsychicalSkills
     */
    private function createCombinedSkillsPaidByOtherSkillPoints(SkillsFromBackground $backgroundSkillPoints, ProfessionLevel $firstLevel)
    {
        $psychicalSkills = $this->createSkillsPaidByOtherSkillPoints(
            $backgroundSkillPoints, $firstLevel, Cooking::class, ReadingAndWriting::class, Athletics::class
        );

        return $psychicalSkills;
    }

    private function createSkillsPaidByOtherSkillPoints(
        SkillsFromBackground $backgroundSkillPoints,
        ProfessionLevel $firstLevel,
        $firstSkillClass,
        $firstOtherSkillClass,
        $secondOtherSkillClass
    )
    {
        $skills = $this->mockery($this->determineSkillsClass($firstSkillClass));
        $skills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(Skill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($firstSkillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(SkillRank::class),
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($firstSkillClass)));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
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
        $firstPaidOtherSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
            ->andReturn(true);
        $secondPaidOtherSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
            ->andReturn(true);
        $secondPaidOtherSkillPoint->shouldReceive('getSkillsFromBackground')
            ->andReturn($backgroundSkillPoints);
        $firstPaidOtherSkillPoint->shouldReceive('getSkillsFromBackground')
            ->andReturn($backgroundSkillPoints);

        return $skills;
    }

    private function determineSkillTypeName($skillClass)
    {
        if (is_a($skillClass, PhysicalSkill::class, true)) {
            return PhysicalSkillPoint::PHYSICAL;
        }
        if (is_a($skillClass, PsychicalSkill::class, true)) {
            return PsychicalSkillPoint::PSYCHICAL;
        }
        if (is_a($skillClass, CombinedSkill::class, true)) {
            return CombinedSkillPoint::COMBINED;
        }
        throw new \LogicException;
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @return PhysicalSkills
     */
    private function createPhysicalSkillsByNextLevelPropertyIncrease(ProfessionLevel $nextLevel)
    {
        $physicalSkillPoints = $this->createSkillsByNextLevelPropertyIncrease($nextLevel, Athletics::class);

        return $physicalSkillPoints;
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @return PsychicalSkills
     */
    private function createPsychicalSkillsByNextLevelPropertyIncrease(ProfessionLevel $nextLevel)
    {
        $psychicalSkillPoints = $this->createSkillsByNextLevelPropertyIncrease($nextLevel, ReadingAndWriting::class);

        return $psychicalSkillPoints;
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @return CombinedSkills
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
                [$firstSkill = $this->mockery(Skill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($skillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $nextLevelSkillRank = $this->mockery(SkillRank::class),
            ]);
        $nextLevelSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($nextLevel);
        $nextLevelSkillRank->shouldReceive('getSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($skillClass)));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($skillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
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
        if (is_a($skillClass, PhysicalSkill::class, true)) {
            return [PropertyCode::STRENGTH, PropertyCode::AGILITY];
        }
        if (is_a($skillClass, PsychicalSkill::class, true)) {
            return [PropertyCode::WILL, PropertyCode::INTELLIGENCE];
        }
        if (is_a($skillClass, CombinedSkill::class, true)) {
            return [PropertyCode::KNACK, PropertyCode::CHARISMA];
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
        $professionLevels->shouldReceive('getProfessionNextLevels')
            ->andReturn(new ArrayCollection([$nextLevel = $this->mockery(ProfessionLevel::class)]));
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
     * @return \Mockery\MockInterface|SkillsFromBackground
     */
    private function createSkillsFromBackground(
        Profession $profession = null,
        $value = 'foo bar',
        $physicalSkillPoints = 3,
        $psychicalSkillPoints = 3,
        $combinedSkillPoints = 3
    )
    {
        $backgroundSkillPoints = $this->mockery(SkillsFromBackground::class);
        if ($profession) {
            $backgroundSkillPoints->shouldReceive('getPhysicalSkillPoints')
                ->with($profession, Tables::getIt())
                ->andReturn($physicalSkillPoints);
            $backgroundSkillPoints->shouldReceive('getPsychicalSkillPoints')
                ->with($profession, Tables::getIt())
                ->andReturn($psychicalSkillPoints);
            $backgroundSkillPoints->shouldReceive('getCombinedSkillPoints')
                ->with($profession, Tables::getIt())
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
     * @return array|Skill[]
     */
    private function getSortedExpectedSkills(array $physical, array $psychical, array $combined)
    {
        $expectedSkills = array_merge($physical, $psychical, $combined);
        usort($expectedSkills, function (Skill $firstSkill, Skill $secondSkill) {
            return strcmp($firstSkill->getName(), $secondSkill->getName());
        });

        return $expectedSkills;
    }

    private function getSortedGivenSkills(Skills $skills)
    {
        $givenSkills = $skills->getSkills();
        usort($givenSkills, function (Skill $firstSkill, Skill $secondSkill) {
            return strcmp($firstSkill->getName(), $secondSkill->getName());
        });

        return $givenSkills;
    }

    // NEGATIVE TESTS

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    public function I_can_not_use_unknown_payment()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground($professionLevels->getFirstLevel()->getProfession());
        $physicalSkills = $this->createPhysicalSkillsWithUnknownPayment($professionLevels->getFirstLevel(), Swimming::class);
        $psychicalSkills = $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel());
        $combinedSkills = $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $professionLevels->getFirstLevel());

        Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills,
            Tables::getIt()
        );
    }

    /**
     * @param ProfessionLevel $firstLevel
     * @param $firstSkillClass
     * @return \Mockery\MockInterface|PhysicalSkills
     */
    private function createPhysicalSkillsWithUnknownPayment(ProfessionLevel $firstLevel, $firstSkillClass)
    {
        $skillsClass = $this->determineSkillsClass($firstSkillClass);
        $skills = $this->mockery($skillsClass);
        $skills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(Skill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($firstSkillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(SkillRank::class),
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($firstSkillClass)));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(false);
        $firstSkillPoint->shouldReceive('isPaidByNextLevelPropertyIncrease')
            ->andReturn(false);

        return $skills;
    }

    /**
     * @test
     * @dataProvider provideDifferentSkillsFromBackground
     * @expectedException \DrdPlus\Skills\Exceptions\SkillsFromBackgroundAreNotSame
     * @param ProfessionLevels $professionLevels
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param SkillsFromBackground $fromFirstSkillsSkillsFromBackground
     * @param SkillsFromBackground $fromOtherSkillsSkillsFromBackground
     */
    public function I_can_not_use_different_background_skill_points(
        ProfessionLevels $professionLevels,
        SkillsFromBackground $backgroundSkillPoints,
        SkillsFromBackground $fromFirstSkillsSkillsFromBackground,
        SkillsFromBackground $fromOtherSkillsSkillsFromBackground
    )
    {
        $physicalSkills = $this->createPhysicalSkillsWithDifferentBackground($fromFirstSkillsSkillsFromBackground, $professionLevels->getFirstLevel(), Swimming::class);
        $psychicalSkills = $this->createPsychicalSkillsPaidByFirstLevelBackground($fromOtherSkillsSkillsFromBackground, $professionLevels->getFirstLevel());
        $combinedSkills = $this->createCombinedSkillsPaidByFirstLevelBackground($fromOtherSkillsSkillsFromBackground, $professionLevels->getFirstLevel());

        Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills,
            Tables::getIt()
        );
    }

    /**
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @param $firstSkillClass
     * @return \Mockery\MockInterface|PhysicalSkills
     */
    private function createPhysicalSkillsWithDifferentBackground(SkillsFromBackground $backgroundSkillPoints, ProfessionLevel $firstLevel, $firstSkillClass)
    {
        $skillsClass = $this->determineSkillsClass($firstSkillClass);
        $skills = $this->mockery($skillsClass);
        $skills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator(
                [$firstSkill = $this->mockery(Skill::class)]
            ));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($firstSkillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(SkillRank::class),
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($firstSkillClass)));
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getSkillsFromBackground')
            ->andReturn($backgroundSkillPoints /*$this->createSkillsFromBackground(null, 'different points value')*/);

        return $skills;
    }

    public function provideDifferentSkillsFromBackground()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground($professionLevels->getFirstLevel()->getProfession());
        $differentSkillsFromBackground = $this->createSkillsFromBackground(null, 'different points value');

        return [
            [$professionLevels, $backgroundSkillPoints, $differentSkillsFromBackground, $backgroundSkillPoints],
            [$professionLevels, $backgroundSkillPoints, $differentSkillsFromBackground, $differentSkillsFromBackground],
        ];
    }

    /**
     * @test
     * @dataProvider provideSkillsWithTooHighFirstLevelPayment
     * @expectedException \DrdPlus\Skills\Exceptions\HigherSkillRanksFromFirstLevelThanPossible
     * @param ProfessionLevels $professionLevels
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param PhysicalSkills $physicalSkills
     * @param PsychicalSkills $psychicalSkills
     * @param CombinedSkills $combinedSkills
     */
    public function I_can_not_spent_more_background_skill_points_than_available(
        ProfessionLevels $professionLevels,
        SkillsFromBackground $backgroundSkillPoints,
        PhysicalSkills $physicalSkills,
        PsychicalSkills $psychicalSkills,
        CombinedSkills $combinedSkills
    )
    {
        Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills,
            Tables::getIt()
        );
    }

    public function provideSkillsWithTooHighFirstLevelPayment()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground(
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
                $combinedSkills,
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
                $combinedSkills,
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
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param ProfessionLevel $firstLevel
     * @param string $firstSkillClass
     * @param string $secondSkillClass
     * @return \Mockery\MockInterface|PhysicalSkills
     */
    private function createSkillsWithTooHighFirstLevelPayment(
        SkillsFromBackground $backgroundSkillPoints, ProfessionLevel $firstLevel, $firstSkillClass, $secondSkillClass
    )
    {
        $skillsClass = $this->determineSkillsClass($firstSkillClass);
        $skills = $this->mockery($skillsClass);
        $skills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator([
                $firstSkill = $this->mockery(Skill::class),
                $secondSkill = $this->mockery(Skill::class),
            ]));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($firstSkillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $firstSkillRank = $this->mockery(SkillRank::class),
            ]);
        $firstSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $firstSkillRank->shouldReceive('getSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($firstSkillClass)));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
            ->andReturn(true);
        $firstSkillPoint->shouldReceive('getSkillsFromBackground')
            ->andReturn($backgroundSkillPoints);
        $secondSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($secondSkillClass));
        $secondSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $secondSkillRank = $this->mockery(SkillRank::class),
            ]);
        $secondSkillRank->shouldReceive('getProfessionLevel')
            ->andReturn($firstLevel);
        $secondSkillRank->shouldReceive('getSkillPoint')
            ->andReturn($secondSkillPoint = $this->mockery($this->determineSkillPointClass($secondSkillClass)));
        $secondSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $secondSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($firstSkillClass));
        $secondSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
            ->andReturn(true);
        $secondSkillPoint->shouldReceive('getSkillsFromBackground')
            ->andReturn($backgroundSkillPoints);

        return $skills;
    }

    /**
     * @test
     * @dataProvider provideProfessionLevelsWithTooLowPropertyIncrease
     * @expectedException \DrdPlus\Skills\Exceptions\HigherSkillRanksFromNextLevelsThanPossible
     * @param ProfessionLevels $professionLevels
     */
    public function I_can_not_increase_skills_by_next_levels_more_than_provides_property_increments(
        ProfessionLevels $professionLevels
    )
    {
        $nextLevel = $professionLevels->getProfessionNextLevels()->last();
        $physicalSkills = $this->createPhysicalSkillsByNextLevelPropertyIncrease($nextLevel);
        $psychicalSkills = $this->createPsychicalSkillsByNextLevelPropertyIncrease($nextLevel);
        $combinedSkills = $this->createCombinedSkillsByNextLevelPropertyIncrease($nextLevel);
        $backgroundSkillPoints = $this->createSkillsFromBackground($professionLevels->getFirstLevel()->getProfession());

        Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills,
            Tables::getIt()
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
     * @expectedException \DrdPlus\Skills\Exceptions\TooHighSingleSkillIncrementPerNextLevel
     */
    public function I_can_not_increase_same_skill_more_than_once_per_next_level()
    {
        $professionLevels = $this->createProfessionLevels('foo', 2);
        $physicalSkills = $this->createPhysicalSkillsWithTooHighSkillIncrementPerNextLevel(
            $professionLevels->getProfessionNextLevels()->last(),
            Swimming::class
        );
        $psychicalSkills = $this->createPsychicalSkillsByNextLevelPropertyIncrease($professionLevels->getFirstLevel());
        $combinedSkills = $this->createCombinedSkillsByNextLevelPropertyIncrease($professionLevels->getFirstLevel());
        $backgroundSkillPoints = $this->createSkillsFromBackground($professionLevels->getFirstLevel()->getProfession());

        Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills,
            Tables::getIt()
        );
    }

    /**
     * @param ProfessionLevel $nextLevel
     * @param string $skillClass
     * @return PhysicalSkills|\Mockery\MockInterface
     */
    private function createPhysicalSkillsWithTooHighSkillIncrementPerNextLevel(ProfessionLevel $nextLevel, $skillClass)
    {
        $kills = $this->mockery($this->determineSkillsClass($skillClass));
        $kills->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator([
                $firstSkill = $this->mockery(Skill::class),
            ]));
        $firstSkill->shouldReceive('getName')
            ->andReturn($this->parseSkillName($skillClass));
        $firstSkill->shouldReceive('getSkillRanks')
            ->andReturn([
                $skillFirstRank = $this->mockery(SkillRank::class),
                $skillSecondRank = $this->mockery(SkillRank::class),
            ]);
        $skillFirstRank->shouldReceive('getProfessionLevel')
            ->andReturn($nextLevel);
        $skillFirstRank->shouldReceive('getValue')
            ->andReturn(1);
        $skillFirstRank->shouldReceive('getSkillPoint')
            ->andReturn($firstSkillPoint = $this->mockery($this->determineSkillPointClass($skillClass)));
        $firstSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $firstSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($skillClass));
        $firstSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
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
        $skillSecondRank->shouldReceive('getSkillPoint')
            ->andReturn($secondSkillPoint = $this->mockery($this->determineSkillPointClass($skillClass)));
        $secondSkillPoint->shouldReceive('getValue')
            ->andReturn(1);
        $secondSkillPoint->shouldReceive('getTypeName')
            ->andReturn($this->determineSkillTypeName($skillClass));
        $secondSkillPoint->shouldReceive('isPaidByFirstLevelSkillsFromBackground')
            ->andReturn(false);
        $secondSkillPoint->shouldReceive('isPaidByOtherSkillPoints')
            ->andReturn(false);
        $secondSkillPoint->shouldReceive('isPaidByNextLevelPropertyIncrease')
            ->andReturn(true);
        $secondSkillPoint->shouldReceive('getRelatedProperties')
            ->andReturn($this->determineRelatedProperties($skillClass));

        return $kills;
    }

    /**
     * @test
     * @dataProvider provideBattleParameterNames
     * @param string $malusTo
     */
    public function I_can_get_melee_weapon_malus($malusTo)
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground(
            $professionLevels->getFirstLevel()->getProfession()
        );
        $firstLevel = $professionLevels->getFirstLevel();
        $skills = Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills = $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            Tables::getIt()
        );

        if ($malusTo === 'cover') {
            $meleeWeaponCode = $this->createWeaponCode(true /* is melee */);
        } else {
            $meleeWeaponCode = $this->createWeaponlikeCode(true /* is melee */);
        }
        $missingWeaponSkillsTable = $this->createMissingWeaponSkillsTable();
        /**
         * @see \DrdPlus\Skills\Skills::getMalusToFightNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToAttackNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToBaseOfWoundsWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToCoverWithWeapon
         */
        $getMalusToParameter = 'getMalusTo' . ucfirst($malusTo) . 'WithWeapon' . ($malusTo === 'cover' ? '' : 'like');

        $physicalSkills->shouldReceive($getMalusToParameter)
            ->with($meleeWeaponCode, $missingWeaponSkillsTable, false)
            ->andReturn($singleMeleeWeaponMalus = 'foo');
        self::assertSame(
            $singleMeleeWeaponMalus,
            $skills->$getMalusToParameter(
                $meleeWeaponCode,
                $missingWeaponSkillsTable,
                false // single weapon used
            )
        );

        $physicalSkills->shouldReceive($getMalusToParameter)
            ->with($meleeWeaponCode, $missingWeaponSkillsTable, true)
            ->andReturn($twoMeleeWeaponsMalus = 'bar');
        self::assertSame(
            $twoMeleeWeaponsMalus,
            $skills->$getMalusToParameter(
                $meleeWeaponCode,
                $missingWeaponSkillsTable,
                true // two weapons used
            )
        );
    }

    /**
     * @param bool $isMelee
     * @param bool $isThrowing
     * @param bool $isShooting
     * @param bool $isProjectile
     * @return \Mockery\MockInterface|WeaponlikeCode
     */
    private function createWeaponlikeCode($isMelee = false, $isThrowing = false, $isShooting = false, $isProjectile = false)
    {
        return $this->createCode(false /* not weapon only (wants weaponlike) */, $isMelee, $isThrowing, $isShooting, $isProjectile);
    }

    /**
     * @param $weaponOnly
     * @param bool $isMelee
     * @param bool $isThrowing
     * @param bool $isShooting
     * @param bool $isProjectile
     * @return \Mockery\MockInterface|WeaponCode|WeaponlikeCode
     */
    private function createCode(
        $weaponOnly,
        $isMelee = false,
        $isThrowing = false,
        $isShooting = false,
        $isProjectile = false
    )
    {
        $weaponlikeCode = $this->mockery($weaponOnly ? WeaponCode::class : WeaponlikeCode::class);
        $weaponlikeCode->shouldReceive('isMelee')
            ->andReturn($isMelee);
        $weaponlikeCode->shouldReceive('isThrowingWeapon')
            ->andReturn($isThrowing);
        $weaponlikeCode->shouldReceive('isShootingWeapon')
            ->andReturn($isShooting);
        $weaponlikeCode->shouldReceive('isProjectile')
            ->andReturn($isProjectile);

        return $weaponlikeCode;
    }

    /**
     * @return \Mockery\MockInterface|WeaponSkillTable
     */
    private function createMissingWeaponSkillsTable()
    {
        return $this->mockery(WeaponSkillTable::class);
    }

    /**
     * @test
     * @dataProvider provideBattleParameterNames
     * @param string $malusTo
     */
    public function I_can_get_malus_for_throwing_weapon($malusTo)
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground(
            $professionLevels->getFirstLevel()->getProfession()
        );
        $firstLevel = $professionLevels->getFirstLevel();
        $skills = Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills = $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            Tables::getIt()
        );

        if ($malusTo === 'cover') {
            $throwingWeaponCode = $this->createWeaponCode(
                false /* not melee */,
                true /* is throwing */
            );
        } else {
            $throwingWeaponCode = $this->createWeaponlikeCode(
                false /* not melee */,
                true /* is throwing */
            );
        }
        $missingWeaponSkillsTable = $this->createMissingWeaponSkillsTable();
        /**
         * @see \DrdPlus\Skills\Skills::getMalusToFightNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToAttackNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToBaseOfWoundsWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToCoverWithWeapon
         */
        $getMalusToParameter = 'getMalusTo' . ucfirst($malusTo) . 'WithWeapon' . ($malusTo === 'cover' ? '' : 'like');

        $physicalSkills->shouldReceive($getMalusToParameter)
            ->with($throwingWeaponCode, $missingWeaponSkillsTable, false)
            ->andReturn($singleThrowingWeaponMalus = 'foo');
        self::assertSame(
            $singleThrowingWeaponMalus,
            $skills->$getMalusToParameter(
                $throwingWeaponCode,
                $missingWeaponSkillsTable,
                false // single weapon used
            )
        );

        $physicalSkills->shouldReceive($getMalusToParameter)
            ->with($throwingWeaponCode, $missingWeaponSkillsTable, true)
            ->andReturn($twoThrowingWeaponsMalus = 'bar');
        self::assertSame(
            $twoThrowingWeaponsMalus,
            $skills->$getMalusToParameter(
                $throwingWeaponCode,
                $missingWeaponSkillsTable,
                true // two weapons used
            )
        );
    }

    /**
     * @test
     * @dataProvider provideBattleParameterNames
     * @param string $malusTo
     */
    public function I_can_get_malus_for_shooting_weapon($malusTo)
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground(
            $professionLevels->getFirstLevel()->getProfession()
        );
        $firstLevel = $professionLevels->getFirstLevel();
        $skills = Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $combinedSkills = $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            Tables::getIt()
        );

        if ($malusTo === 'cover') {
            $shootingWeaponCode = $this->createWeaponCode(
                false /* not melee */,
                false /* not throwing */,
                true /* is shooting */
            );
        } else {
            $shootingWeaponCode = $this->createWeaponlikeCode(
                false /* not melee */,
                false /* not throwing */,
                true /* is shooting */
            );
        }
        $shootingWeaponCode->shouldReceive('convertToRangedWeaponCodeEquivalent')
            ->andReturn($rangeWeaponCode = $this->createRangeWeaponCode());
        $missingWeaponSkillsTable = $this->createMissingWeaponSkillsTable();
        $combinedSkills->shouldReceive('getMalusTo' . ucfirst($malusTo) . 'WithShootingWeapon')
            ->with($rangeWeaponCode, $missingWeaponSkillsTable)
            ->andReturn($shootingWeaponMalus = 'foo');
        /**
         * @see \DrdPlus\Skills\Skills::getMalusToFightNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToAttackNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToBaseOfWoundsWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToCoverWithWeapon
         */
        $malusToParameter = 'getMalusTo' . ucfirst($malusTo) . 'WithWeapon' . ($malusTo === 'cover' ? '' : 'like');
        self::assertSame(
            $shootingWeaponMalus,
            $skills->$malusToParameter(
                $shootingWeaponCode,
                $missingWeaponSkillsTable,
                false /* fight with two weapons should be irrelevant for shooting weapons */
            )
        );
        self::assertSame(
            $shootingWeaponMalus,
            $skills->$malusToParameter(
                $shootingWeaponCode,
                $missingWeaponSkillsTable,
                true /* fight with two weapons should be irrelevant for shooting weapons */
            )
        );
    }

    /**
     * @return \Mockery\MockInterface|RangedWeaponCode
     */
    private function createRangeWeaponCode()
    {
        return $this->mockery(RangedWeaponCode::class);
    }

    /**
     * @test
     * @dataProvider provideBattleParameterNames
     * @param string $malusTo
     */
    public function I_get_zero_malus_for_projectiles($malusTo)
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground(
            $professionLevels->getFirstLevel()->getProfession()
        );
        $firstLevel = $professionLevels->getFirstLevel();
        $skills = Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $combinedSkills = $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            Tables::getIt()
        );

        if ($malusTo === 'cover') {
            $projectile = $this->createWeaponCode(
                false /* not melee */,
                false /* not throwing */,
                false /* not shooting */,
                true /* projectile */
            );
        } else {
            $projectile = $this->createWeaponlikeCode(
                false /* not melee */,
                false /* not throwing */,
                false /* not shooting */,
                true /* projectile */
            );
        }
        $projectile->shouldReceive('convertToRangedWeaponCodeEquivalent')
            ->andReturn($rangeWeaponCode = $this->createRangeWeaponCode());
        $missingWeaponSkillsTable = $this->createMissingWeaponSkillsTable();
        /**
         * @see \DrdPlus\Skills\Skills::getMalusToFightNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToAttackNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToBaseOfWoundsWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToCoverWithWeapon
         */
        $malusToParameter = 'getMalusTo' . ucfirst($malusTo) . 'WithWeapon' . ($malusTo === 'cover' ? '' : 'like');
        self::assertSame(
            0,
            $skills->$malusToParameter(
                $projectile,
                $missingWeaponSkillsTable,
                false /* fight with two weapons should be irrelevant for projectile */
            )
        );
        self::assertSame(
            0,
            $skills->$malusToParameter(
                $projectile,
                $missingWeaponSkillsTable,
                true /* fight with two weapons should be irrelevant for projectile */
            )
        );
    }

    public function provideBattleParameterNames()
    {
        return [
            ['fightNumber'],
            ['attackNumber'],
            ['cover'],
            ['baseOfWounds'],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_fight_number_with_protective()
    {
        $professionLevels = $this->createProfessionLevels();
        $firstLevel = $professionLevels->getFirstLevel();
        $backgroundSkillPoints = $this->createSkillsFromBackground($firstLevel->getProfession());
        $skills = Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills = $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            Tables::getIt()
        );
        /** @var Armourer $armourer */
        $armourer = $this->mockery(Armourer::class);
        /** @var ShieldCode $shield */
        $shield = $this->mockery(ShieldCode::class);
        $physicalSkills->shouldReceive('getMalusToFightNumberWithProtective')
            ->with($shield, $armourer)
            ->andReturn('foo');
        self::assertSame(
            'foo',
            $skills->getMalusToFightNumberWithProtective($shield, $armourer)
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Exceptions\UnknownTypeOfWeapon
     * @dataProvider provideBattleParameterNames
     * @param string $malusTo
     */
    public function I_can_not_get_battle_number_malus_for_unknown_weapon($malusTo)
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground($professionLevels->getFirstLevel()->getProfession());
        $firstLevel = $professionLevels->getFirstLevel();
        $skills = Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            Tables::getIt()
        );
        /**
         * @see \DrdPlus\Skills\Skills::getMalusToFightNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToAttackNumberWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToBaseOfWoundsWithWeaponlike
         * @see \DrdPlus\Skills\Skills::getMalusToCoverWithWeapon
         */
        $malusToParameter = 'getMalusTo' . ucfirst($malusTo) . 'WithWeapon' . ($malusTo === 'cover' ? '' : 'like');
        $skills->$malusToParameter(
            ($malusTo === 'cover' ? $this->createWeaponCode() : $this->createWeaponlikeCode()),
            $this->createMissingWeaponSkillsTable(),
            false
        );
    }

    /**
     * @param bool $isMelee
     * @param bool $isThrowing
     * @param bool $isShooting
     * @param bool $isProjectile
     * @return \Mockery\MockInterface|WeaponCode
     */
    private function createWeaponCode(
        $isMelee = false,
        $isThrowing = false,
        $isShooting = false,
        $isProjectile = false
    )
    {
        return $this->createCode(true /* weapon only */, $isMelee, $isThrowing, $isShooting, $isProjectile);
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_cover_with_shield()
    {
        $professionLevels = $this->createProfessionLevels();
        $backgroundSkillPoints = $this->createSkillsFromBackground($professionLevels->getFirstLevel()->getProfession());
        $firstLevel = $professionLevels->getFirstLevel();
        $skills = Skills::createSkills(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills = $this->createPhysicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createPsychicalSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            $this->createCombinedSkillsPaidByFirstLevelBackground($backgroundSkillPoints, $firstLevel),
            Tables::getIt()
        );
        $missingShieldSkillTable = $this->createShieldUsageSkillTable();
        $physicalSkills->shouldReceive('getMalusToCoverWithShield')
            ->with($missingShieldSkillTable)
            ->andReturn('foo');
        self::assertSame('foo', $skills->getMalusToCoverWithShield($missingShieldSkillTable));
    }

    /**
     * @return \Mockery\MockInterface|ShieldUsageSkillTable
     */
    private function createShieldUsageSkillTable()
    {
        return $this->mockery(ShieldUsageSkillTable::class);
    }

    /**
     * @test
     */
    public function I_can_create_it_with_zero_profession_levels()
    {
        $skills = Skills::createSkills(
            ProfessionLevels::createIt(
                $professionZeroLevel = ProfessionZeroLevel::createZeroLevel(Commoner::getIt()),
                ProfessionFirstLevel::createFirstLevel($profession = Fighter::getIt()),
                [ProfessionNextLevel::createNextLevel(
                    $profession,
                    LevelRank::getIt(2),
                    Strength::getIt(0),
                    Agility::getIt(1),
                    Knack::getIt(0),
                    Will::getIt(0),
                    Intelligence::getIt(1),
                    Charisma::getIt(0)
                )]
            ),
            SkillsFromBackground::getIt(
                new PositiveIntegerObject(2),
                Ancestry::getIt(new PositiveIntegerObject(7), Tables::getIt()),
                Tables::getIt()
            ),
            new PhysicalSkills($professionZeroLevel),
            new PsychicalSkills($professionZeroLevel),
            new CombinedSkills($professionZeroLevel),
            Tables::getIt()
        );
        self::assertSame(
            $professionZeroLevel,
            $skills->getCombinedSkills()->getBigHandwork()->getCurrentSkillRank()->getProfessionLevel()
        );
    }

}