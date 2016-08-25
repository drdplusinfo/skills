<?php
namespace DrdPlus\Person\Skills;

use Doctrine\ORM\Mapping as ORM;
use Doctrineum\Entity\Entity;
use DrdPlus\Codes\CombinedSkillCode;
use DrdPlus\Codes\PhysicalSkillCode;
use DrdPlus\Codes\PsychicalSkillCode;
use DrdPlus\Codes\WeaponCode;
use DrdPlus\Person\Background\BackgroundParts\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use DrdPlus\Tables\Tables;
use Granam\Strict\Object\StrictObject;

/**
 * @ORM\Entity()
 */
class PersonSkills extends StrictObject implements \IteratorAggregate, \Countable, Entity
{
    const PHYSICAL = PersonPhysicalSkills::PHYSICAL;
    const PSYCHICAL = PersonPsychicalSkills::PSYCHICAL;
    const COMBINED = PersonCombinedSkills::COMBINED;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var PersonPhysicalSkills
     * @ORM\OneToOne(targetEntity="DrdPlus\Person\Skills\Physical\PersonPhysicalSkills", cascade={"persist"})
     */
    private $personPhysicalSkills;

    /**
     * @var PersonPsychicalSkills
     * @ORM\OneToOne(targetEntity="DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills", cascade={"persist"})
     */
    private $personPsychicalSkills;

    /**
     * @var PersonCombinedSkills
     * @ORM\OneToOne(targetEntity="DrdPlus\Person\Skills\Combined\PersonCombinedSkills", cascade={"persist"})
     */
    private $personCombinedSkills;

    /**
     * @param ProfessionLevels $professionLevels
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param Tables $tables
     * @param PersonPhysicalSkills $personPhysicalSkills
     * @param PersonPsychicalSkills $personPsychicalSkills
     * @param PersonCombinedSkills $personCombinedSkills
     * @return PersonSkills
     */
    public static function createPersonSkills(
        ProfessionLevels $professionLevels,
        BackgroundSkillPoints $backgroundSkillPoints,
        Tables $tables,
        PersonPhysicalSkills $personPhysicalSkills,
        PersonPsychicalSkills $personPsychicalSkills,
        PersonCombinedSkills $personCombinedSkills
    )
    {
        self::checkPaymentForSkillPoints(
            $professionLevels, $backgroundSkillPoints, $tables, $personPhysicalSkills, $personPsychicalSkills, $personCombinedSkills
        );
        self::checkNextLevelsSkillRanks($personPhysicalSkills, $personPsychicalSkills, $personCombinedSkills);

        return new self($personPhysicalSkills, $personPsychicalSkills, $personCombinedSkills);
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param Tables $tables
     * @param PersonPhysicalSkills $physicalSkills ,
     * @param PersonPsychicalSkills $psychicalSkills ,
     * @param PersonCombinedSkills $combinedSkills
     */
    private static function checkPaymentForSkillPoints(
        ProfessionLevels $professionLevels,
        BackgroundSkillPoints $backgroundSkillPoints,
        Tables $tables,
        PersonPhysicalSkills $physicalSkills,
        PersonPsychicalSkills $psychicalSkills,
        PersonCombinedSkills $combinedSkills
    )
    {
        $paymentsFoSkills = self::extractPropertyPayments($physicalSkills, $psychicalSkills, $combinedSkills);
        self::checkFirstLevelPayment(
            $paymentsFoSkills['firstLevel'],
            $professionLevels->getFirstLevel(),
            $backgroundSkillPoints,
            $tables
        );
        self::checkNextLevelsPayment($paymentsFoSkills['nextLevels'], $professionLevels);
    }

    /**
     * @param PersonPhysicalSkills $physicalSkills ,
     * @param PersonPsychicalSkills $psychicalSkills ,
     * @param PersonCombinedSkills $combinedSkill
     * @return array
     */
    private static function extractPropertyPayments(
        PersonPhysicalSkills $physicalSkills,
        PersonPsychicalSkills $psychicalSkills,
        PersonCombinedSkills $combinedSkill
    )
    {
        $propertyPayments = self::getPaymentsSkeleton();
        foreach ([$physicalSkills, $psychicalSkills, $combinedSkill] as $sameTypeSkills) {
            foreach ($sameTypeSkills as $skill) {
                /** @var PersonSkill $skill */
                foreach ($skill->getSkillRanks() as $skillRank) {
                    $paymentDetails = self::extractPaymentDetails($skillRank->getPersonSkillPoint());
                    $propertyPayments = self::sumPayments([$propertyPayments, $paymentDetails]);
                }
            }
        }

        return $propertyPayments;
    }

    private static function getPaymentsSkeleton()
    {
        return [
            'firstLevel' => [
                PhysicalSkillPoint::PHYSICAL => ['spentFirstLevelSkillPoints' => 0, 'backgroundSkillPoints' => null],
                PsychicalSkillPoint::PSYCHICAL => ['spentFirstLevelSkillPoints' => 0, 'backgroundSkillPoints' => null],
                CombinedSkillPoint::COMBINED => ['spentFirstLevelSkillPoints' => 0, 'backgroundSkillPoints' => null]
            ],
            'nextLevels' => [
                PhysicalSkillPoint::PHYSICAL => ['spentNextLevelsSkillPoints' => 0, 'relatedProperties' => []],
                PsychicalSkillPoint::PSYCHICAL => ['spentNextLevelsSkillPoints' => 0, 'relatedProperties' => []],
                CombinedSkillPoint::COMBINED => ['spentNextLevelsSkillPoints' => 0, 'relatedProperties' => []]
            ]
        ];
    }

    private static function extractPaymentDetails(PersonSkillPoint $skillPoint)
    {
        $propertyPayment = self::getPaymentsSkeleton();

        $type = $skillPoint->getTypeName();
        if ($skillPoint->isPaidByFirstLevelBackgroundSkillPoints()) {
            /**
             * There are limited first level background skill points,
             * @see \DrdPlus\Person\Background\BackgroundSkillPoints
             * and @see \DrdPlus\Person\Background\Heritage
             * check their sum
             */
            $propertyPayment['firstLevel'][$type]['spentFirstLevelSkillPoints'] += $skillPoint->getValue();
            $propertyPayment['firstLevel'][$type]['backgroundSkillPoints'] = $skillPoint->getBackgroundSkillPoints();

            return $propertyPayment;
        } else if ($skillPoint->isPaidByOtherSkillPoints()) {
            $firstPaidOtherSkillPoint = self::extractPaymentDetails($skillPoint->getFirstPaidOtherSkillPoint());
            $secondPaidOtherSkillPoint = self::extractPaymentDetails($skillPoint->getSecondPaidOtherSkillPoint());

            // the other skill points have to be extracted to first level background skills, see upper
            return self::sumPayments([$firstPaidOtherSkillPoint, $secondPaidOtherSkillPoint]);
        } else if ($skillPoint->isPaidByNextLevelPropertyIncrease()) {
            // for every skill point of this type has to exists level property increase
            $propertyPayment['nextLevels'][$type]['spentNextLevelsSkillPoints'] += $skillPoint->getValue();
            $propertyPayment['nextLevels'][$type]['relatedProperties'] = $skillPoint->getRelatedProperties();

            return $propertyPayment;
        } else {
            throw new Exceptions\UnknownPaymentForSkillPoint(
                'Unknown payment for skill point ' . get_class($skillPoint)
            );
        }
    }

    /**
     * @param array $paymentOfSkillPoints
     *
     * @return array
     */
    private static function sumPayments(array $paymentOfSkillPoints)
    {
        $paymentSum = self::getPaymentsSkeleton();
        foreach ($paymentOfSkillPoints as $paymentOfSkillPoint) {
            foreach ([PhysicalSkillPoint::PHYSICAL, PsychicalSkillPoint::PSYCHICAL, CombinedSkillPoint::COMBINED] as $type) {
                $paymentSum['firstLevel'][$type] = self::sumFirstLevelPaymentOfType(
                    $paymentSum['firstLevel'][$type], $paymentOfSkillPoint['firstLevel'][$type]
                );
                $paymentSum['nextLevels'][$type] = self::sumNextLevelsPaymentOfType(
                    $paymentSum['nextLevels'][$type], $paymentOfSkillPoint['nextLevels'][$type]
                );
            }
        }

        return $paymentSum;
    }

    /**
     * @param array $firstLevelSumPaymentOfType
     * @param array $firstLevelSkillPointPaymentOfType
     *
     * @return array
     */
    private static function sumFirstLevelPaymentOfType(array $firstLevelSumPaymentOfType, array $firstLevelSkillPointPaymentOfType)
    {
        if ($firstLevelSkillPointPaymentOfType['spentFirstLevelSkillPoints'] > 0) {
            if ($firstLevelSumPaymentOfType['backgroundSkillPoints']) {
                self::checkIfBackgroundSkillPointsAreTheSame(
                    $firstLevelSkillPointPaymentOfType['backgroundSkillPoints'], $firstLevelSumPaymentOfType['backgroundSkillPoints']
                );
            } else {
                $firstLevelSumPaymentOfType['backgroundSkillPoints'] = $firstLevelSkillPointPaymentOfType['backgroundSkillPoints'];
            }
            $firstLevelSumPaymentOfType['spentFirstLevelSkillPoints'] += $firstLevelSkillPointPaymentOfType['spentFirstLevelSkillPoints'];
        }

        return $firstLevelSumPaymentOfType;
    }

    private static function checkIfBackgroundSkillPointsAreTheSame(
        BackgroundSkillPoints $firstBackgroundSkillPoints,
        BackgroundSkillPoints $secondBackgroundSkillPoints
    )
    {
        if ($firstBackgroundSkillPoints->getSpentBackgroundPoints() !== $secondBackgroundSkillPoints->getSpentBackgroundPoints()) {
            throw new Exceptions\BackgroundSkillPointsAreNotSame(
                'All skill points, originated in person background, have to use same background skill points.'
                . " Got different background skill points with values {$firstBackgroundSkillPoints->getSpentBackgroundPoints()}"
                . " and {$secondBackgroundSkillPoints->getSpentBackgroundPoints()}"
            );
        }
    }

    private static function sumNextLevelsPaymentOfType(array $nextLevelsSumPaymentOfType, array $NextLevelsSkillPointPaymentOfType)
    {
        if ($NextLevelsSkillPointPaymentOfType['spentNextLevelsSkillPoints'] > 0) {
            $nextLevelsSumPaymentOfType['spentNextLevelsSkillPoints'] += $NextLevelsSkillPointPaymentOfType['spentNextLevelsSkillPoints'];
            $nextLevelsSumPaymentOfType['relatedProperties'] = $NextLevelsSkillPointPaymentOfType['relatedProperties'];
        }

        return $nextLevelsSumPaymentOfType;
    }

    private static function checkFirstLevelPayment(
        array $firstLevelPayments, ProfessionLevel $firstLevel, BackgroundSkillPoints $backgroundSkillPoints, Tables $tables
    )
    {
        foreach ($firstLevelPayments as $skillType => $payment) {
            if (!$payment['spentFirstLevelSkillPoints']) {
                continue; // no skills have been "bought" at all
            }
            $paymentBackgroundSkills = $payment['backgroundSkillPoints'];
            self::checkIfBackgroundSkillPointsAreTheSame($paymentBackgroundSkills, $backgroundSkillPoints);
            $availableSkillPoints = 0;
            switch ($skillType) {
                case self::PHYSICAL :
                    $availableSkillPoints = $backgroundSkillPoints->getPhysicalSkillPoints(
                        $firstLevel->getProfession(), $tables
                    );
                    break;
                case self::PSYCHICAL :
                    $availableSkillPoints = $backgroundSkillPoints->getPsychicalSkillPoints(
                        $firstLevel->getProfession(), $tables
                    );
                    break;
                case self::COMBINED :
                    $availableSkillPoints = $backgroundSkillPoints->getCombinedSkillPoints(
                        $firstLevel->getProfession(), $tables
                    );
                    break;
            }
            if ($availableSkillPoints < $payment['spentFirstLevelSkillPoints']) {
                throw new Exceptions\HigherSkillRanksFromFirstLevelThanPossible(
                    "First level skills of type '$skillType' have higher ranks then possible."
                    . " Expected spent $availableSkillPoints skill points at most, got " . $payment['spentFirstLevelSkillPoints']
                );
            }
        }
    }

    private static function checkNextLevelsPayment(array $nextLevelsPayment, ProfessionLevels $professionLevels)
    {
        foreach ($nextLevelsPayment as $skillsType => $nextLevelPayment) {
            $increasedPropertySum = 0;
            foreach ($nextLevelPayment['relatedProperties'] as $relatedProperty) {
                switch ($relatedProperty) {
                    case Strength::STRENGTH :
                        $increasedPropertySum += $professionLevels->getNextLevelsStrengthModifier();
                        break;
                    case Agility::AGILITY :
                        $increasedPropertySum += $professionLevels->getNextLevelsAgilityModifier();
                        break;
                    case Knack::KNACK :
                        $increasedPropertySum += $professionLevels->getNextLevelsKnackModifier();
                        break;
                    case Will::WILL :
                        $increasedPropertySum += $professionLevels->getNextLevelsWillModifier();
                        break;
                    case Intelligence::INTELLIGENCE :
                        $increasedPropertySum += $professionLevels->getNextLevelsIntelligenceModifier();
                        break;
                    case Charisma::CHARISMA :
                        $increasedPropertySum += $professionLevels->getNextLevelsCharismaModifier();
                        break;
                }
            }
            $maxSkillPoint = self::getSkillPointByPropertyIncrease($increasedPropertySum);
            if ($nextLevelPayment['spentNextLevelsSkillPoints'] > $maxSkillPoint) {
                throw new Exceptions\HigherSkillRanksFromNextLevelsThanPossible(
                    "Skills from next levels of type '$skillsType' have higher ranks than possible."
                    . " Max increase by next levels can be $maxSkillPoint by $increasedPropertySum increase"
                    . ' of related properties (' . implode(', ', $nextLevelPayment['relatedProperties']) . ')'
                    . ', got ' . $nextLevelPayment['spentNextLevelsSkillPoints']
                );
            }
        }
    }

    const PROPERTY_TO_SKILL_POINT_MULTIPLIER = 1; // each point of property gives one skill point

    /**
     * @param int $propertyIncrease
     * @return int
     */
    private static function getSkillPointByPropertyIncrease($propertyIncrease)
    {
        return self::PROPERTY_TO_SKILL_POINT_MULTIPLIER * $propertyIncrease;
    }

    const MAX_SKILL_RANK_INCREASE_PER_NEXT_LEVEL = 1;

    private static function checkNextLevelsSkillRanks(
        PersonPhysicalSkills $physicalSkills,
        PersonPsychicalSkills $psychicalSkills,
        PersonCombinedSkills $combinedSkills
    )
    {
        $nextLevelSkills = [];
        foreach ([$physicalSkills, $psychicalSkills, $combinedSkills] as $sameTypeSkills) {
            /** @var PersonSkill[] $sameTypeSkills */
            foreach ($sameTypeSkills as $skill) {
                $nextLevelSkills[$skill->getName()] = [];
                foreach ($skill->getSkillRanks() as $skillRank) {
                    if ($skillRank->getProfessionLevel()->isNextLevel()) {
                        $levelValue = $skillRank->getProfessionLevel()->getLevelRank()->getValue();
                        if (!array_key_exists($levelValue, $nextLevelSkills[$skill->getName()])) {
                            $nextLevelSkills[$skill->getName()][$levelValue] = [];
                        }
                        $nextLevelSkills[$skill->getName()][$levelValue][] = $skillRank;
                    }
                }
            }
        }
        $tooHighRankAdjustments = [];
        /**
         * @var string $skillName
         * @var PersonSkillRank[][] $ranksPerLevel
         */
        foreach ($nextLevelSkills as $skillName => $ranksPerLevel) {
            /**
             * @var int $levelValue
             * @var PersonSkillRank[] $skillRanks
             */
            foreach ($ranksPerLevel as $levelValue => $skillRanks) {
                if (!isset($tooHighRankAdjustments[$skillName][$levelValue])
                    && count($skillRanks) > self::MAX_SKILL_RANK_INCREASE_PER_NEXT_LEVEL
                ) {
                    $tooHighRankAdjustments[$skillName][$levelValue] = $skillRanks;
                }
            }
        }
        if ($tooHighRankAdjustments) {
            throw new Exceptions\TooHighSingleSkillIncrementPerNextLevel(
                'Only on first level can be skill ranks increased more then '
                . (self::MAX_SKILL_RANK_INCREASE_PER_NEXT_LEVEL === 1 ? 'once' : self::MAX_SKILL_RANK_INCREASE_PER_NEXT_LEVEL) . '.'
                . ' Got ' . count($tooHighRankAdjustments) . ' skill(s) with too high rank-per-level adjustment'
                . ' (' . self::getTooHighRankAdjustmentsDescription($tooHighRankAdjustments) . ')'
            );
        }
    }

    private static function getTooHighRankAdjustmentsDescription(array $tooHighRankAdjustments)
    {
        $descriptionParts = [];
        /** @var PersonSkillRank[][] $ranksPerLevel */
        foreach ($tooHighRankAdjustments as $skillName => $ranksPerLevel) {
            $skillDescription = "skill '$skillName' over-increased on";
            $levelsDescriptions = [];
            foreach ($ranksPerLevel as $levelValue => $skillRanks) {
                $levelDescription = "level $levelValue to ranks "
                    . implode(
                        ' and ',
                        array_map(function (PersonSkillRank $rank) {
                            return $rank->getValue();
                        }, $skillRanks)
                    );
                $levelsDescriptions[] = $levelDescription;
            }
            $skillDescription .= ' ' . implode(', ', $levelsDescriptions);
            $descriptionParts[] = $skillDescription;
        }

        return implode(';', $descriptionParts);
    }

    /**
     * Looking for a way how to create it?
     * Try @see PersonSkills::createPersonSkills
     *
     * @param PersonPhysicalSkills $personPhysicalSkills
     * @param PersonPsychicalSkills $personPsychicalSkills
     * @param PersonCombinedSkills $personCombinedSkills
     */
    private function __construct(
        PersonPhysicalSkills $personPhysicalSkills,
        PersonPsychicalSkills $personPsychicalSkills,
        PersonCombinedSkills $personCombinedSkills
    )
    {
        $this->personPhysicalSkills = $personPhysicalSkills;
        $this->personPsychicalSkills = $personPsychicalSkills;
        $this->personCombinedSkills = $personCombinedSkills;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PersonPhysicalSkills
     */
    public function getPersonPhysicalSkills()
    {
        return $this->personPhysicalSkills;
    }

    /**
     * @return PersonPsychicalSkills
     */
    public function getPersonPsychicalSkills()
    {
        return $this->personPsychicalSkills;
    }

    /**
     * @return PersonCombinedSkills
     */
    public function getPersonCombinedSkills()
    {
        return $this->personCombinedSkills;
    }

    /**
     * @return array|PersonSkill[]
     */
    public function getSkills()
    {
        return array_merge(
            $this->getPersonPhysicalSkills()->getIterator()->getArrayCopy(),
            $this->getPersonPsychicalSkills()->getIterator()->getArrayCopy(),
            $this->getPersonCombinedSkills()->getIterator()->getArrayCopy()
        );
    }

    /**
     * @return array|string[]
     */
    public function getCodesOfAllSkills()
    {
        return array_merge(
            PhysicalSkillCode::getPhysicalSkillCodes(),
            PsychicalSkillCode::getPsychicalSkillCodes(),
            CombinedSkillCode::getCombinedSkillCodes()
        );
    }

    /**
     * @return array|string[]
     */
    public function getCodesOfLearnedSkills()
    {
        $codesOfKnownSkills = [];
        foreach ($this->getSkills() as $skill) {
            $codesOfKnownSkills[] = $skill->getName();
        }

        return $codesOfKnownSkills;
    }

    /**
     * @return array|string[]
     */
    public function getCodesOfNotLearnedSkills()
    {
        $namesOfKnownSkills = [];
        foreach ($this->getSkills() as $skill) {
            $namesOfKnownSkills[] = $skill->getName();
        }

        return array_diff($this->getCodesOfAllSkills(), $namesOfKnownSkills);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getSkills());
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getSkills());
    }

    /**
     * @param WeaponCode $weaponCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(WeaponCode $weaponCode, MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        if ($weaponCode->isMeleeArmament() || $weaponCode->isThrowingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPersonPhysicalSkills()->getMalusToFightNumber($weaponCode, $missingWeaponSkillsTable);
        }
        if ($weaponCode->isShootingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPersonCombinedSkills()->getMalusToFightNumber(
                $weaponCode->convertToRangeWeaponCodeEquivalent(),
                $missingWeaponSkillsTable
            );
        }

        return 0;
    }

    /**
     * @param WeaponCode $weaponCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(WeaponCode $weaponCode, MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        if ($weaponCode->isMeleeArmament() || $weaponCode->isThrowingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPersonPhysicalSkills()->getMalusToAttackNumber($weaponCode, $missingWeaponSkillsTable);
        }
        if ($weaponCode->isShootingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPersonCombinedSkills()->getMalusToAttackNumber(
                $weaponCode->convertToRangeWeaponCodeEquivalent(),
                $missingWeaponSkillsTable
            );
        }

        return 0;
    }

    /**
     * @param WeaponCode $weaponCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(WeaponCode $weaponCode, MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        if ($weaponCode->isMeleeArmament() || $weaponCode->isThrowingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPersonPhysicalSkills()->getMalusToCover($weaponCode, $missingWeaponSkillsTable);
        }
        if ($weaponCode->isShootingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPersonCombinedSkills()->getMalusToCover(
                $weaponCode->convertToRangeWeaponCodeEquivalent(),
                $missingWeaponSkillsTable
            );
        }

        return 0;
    }

    /**
     * @param WeaponCode $weaponCode
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(WeaponCode $weaponCode, MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        if ($weaponCode->isMeleeArmament() || $weaponCode->isThrowingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPersonPhysicalSkills()->getMalusToBaseOfWounds($weaponCode, $missingWeaponSkillsTable);
        }
        if ($weaponCode->isShootingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPersonCombinedSkills()->getMalusToBaseOfWounds(
                $weaponCode->convertToRangeWeaponCodeEquivalent(),
                $missingWeaponSkillsTable
            );
        }

        return 0;
    }

}