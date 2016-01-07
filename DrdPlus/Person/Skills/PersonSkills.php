<?php
namespace DrdPlus\Person\Skills;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Person\Background\BackgroundSkillPoints;
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
use DrdPlus\Tables\Tables;
use Granam\Strict\Object\StrictObject;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class PersonSkills extends StrictObject
{
    const PHYSICAL = PersonPhysicalSkills::PHYSICAL;
    const PSYCHICAL = PersonPsychicalSkills::PSYCHICAL;
    const COMBINED = PersonCombinedSkills::COMBINED;

    public static function getIt(
        ProfessionLevels $professionLevels,
        BackgroundSkillPoints $backgroundSkillPoints,
        Tables $tables,
        PersonPhysicalSkills $physicalSkills,
        PersonPsychicalSkills $psychicalSkills,
        PersonCombinedSkills $combinedSkills
    )
    {
        self::checkPaymentForSkillPoints(
            $professionLevels, $backgroundSkillPoints, $tables, $physicalSkills, $psychicalSkills, $combinedSkills
        );
        self::checkNextLevelsSkillRanks($physicalSkills, $psychicalSkills, $combinedSkills);

        return new self($physicalSkills, $psychicalSkills, $combinedSkills);
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
                PhysicalSkillPoint::PHYSICAL => ['paidSkillPoints' => 0, 'backgroundSkillPoints' => null],
                PsychicalSkillPoint::PSYCHICAL => ['paidSkillPoints' => 0, 'backgroundSkillPoints' => null],
                CombinedSkillPoint::COMBINED => ['paidSkillPoints' => 0, 'backgroundSkillPoints' => null]
            ],
            'nextLevels' => [
                PhysicalSkillPoint::PHYSICAL => ['paidSkillPoints' => 0, 'relatedProperties' => []],
                PsychicalSkillPoint::PSYCHICAL => ['paidSkillPoints' => 0, 'relatedProperties' => []],
                CombinedSkillPoint::COMBINED => ['paidSkillPoints' => 0, 'relatedProperties' => []]
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
            $propertyPayment['firstLevel'][$type]['paidSkillPoints']++;
            $propertyPayment['firstLevel'][$type]['backgroundSkillPoints'] = $skillPoint->getBackgroundSkillPoints();

            return $propertyPayment;
        } else if ($skillPoint->isPaidByOtherSkillPoints()) {
            $firstPaidOtherSkillPoint = self::extractPaymentDetails($skillPoint->getFirstPaidOtherSkillPoint());
            $secondPaidOtherSkillPoint = self::extractPaymentDetails($skillPoint->getSecondPaidOtherSkillPoint());

            // the other skill points have to be extracted to first level background skills, see upper
            return self::sumPayments([$firstPaidOtherSkillPoint, $secondPaidOtherSkillPoint]);
        } else if ($skillPoint->isPaidByNextLevelPropertyIncrease()) {
            // for every skill point of this type has to exists level property increase
            $propertyPayment['nextLevels'][$type]['paidSkillPoints']++;
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
        if ($firstLevelSkillPointPaymentOfType['paidSkillPoints'] > 0) {
            if ($firstLevelSumPaymentOfType['backgroundSkillPoints']) {
                self::checkIfBackgroundSkillPointsAreTheSame(
                    $firstLevelSkillPointPaymentOfType['backgroundSkillPoints'], $firstLevelSumPaymentOfType['backgroundSkillPoints']
                );
            } else {
                $firstLevelSumPaymentOfType['backgroundSkillPoints'] = $firstLevelSkillPointPaymentOfType['backgroundSkillPoints'];
            }
            $firstLevelSumPaymentOfType['paidSkillPoints'] += $firstLevelSkillPointPaymentOfType['paidSkillPoints'];
        }

        return $firstLevelSumPaymentOfType;
    }

    private static function checkIfBackgroundSkillPointsAreTheSame(
        BackgroundSkillPoints $firstBackgroundSkillPoints,
        BackgroundSkillPoints $secondBackgroundSkillPoints
    )
    {
        if ($firstBackgroundSkillPoints->getBackgroundPointsValue() !== $secondBackgroundSkillPoints->getBackgroundPointsValue()) {
            throw new Exceptions\BackgroundSkillPointsAreNotSame(
                'All skill points, originated in person background, have to use same background skill points.'
                . " Got different background skill points with value {$firstBackgroundSkillPoints->getBackgroundPointsValue()}"
                . " and {$secondBackgroundSkillPoints->getBackgroundPointsValue()}"
            );
        }
    }

    private static function sumNextLevelsPaymentOfType(array $nextLevelsSumPaymentOfType, array $NextLevelsSkillPointPaymentOfType)
    {
        if ($NextLevelsSkillPointPaymentOfType['paidSkillPoints'] > 0) {
            $nextLevelsSumPaymentOfType['paidSkillPoints'] += $NextLevelsSkillPointPaymentOfType['paidSkillPoints'];
            $nextLevelsSumPaymentOfType['relatedProperties'] = $NextLevelsSkillPointPaymentOfType['relatedProperties'];
        }

        return $nextLevelsSumPaymentOfType;
    }

    private static function checkFirstLevelPayment(
        array $firstLevelPayments, ProfessionLevel $firstLevel, BackgroundSkillPoints $backgroundSkillPoints, Tables $tables
    )
    {
        foreach ($firstLevelPayments as $skillType => $payment) {
            if (!$payment['paidSkillPoints']) {
                continue; // no skills have been "bought" at all
            }
            /** @var BackgroundSkillPoints $paymentBackgroundSkills */
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
            if ($availableSkillPoints < $payment['paidSkillPoints']) {
                throw new Exceptions\HigherSkillRanksFromFirstLevelThanPossible(
                    "First level skills of type '$skillType' have higher ranks then possible."
                    . " Expected spent $availableSkillPoints skill points at most, got " . $payment['paidSkillPoints']
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
            if ($nextLevelPayment['paidSkillPoints'] > $maxSkillPoint) {
                throw new Exceptions\HigherSkillRanksFromNextLevelsThanPossible(
                    "Skills from next levels of type '$skillsType' have higher ranks than possible."
                    . " Max increase by next levels can be $maxSkillPoint by $increasedPropertySum increase"
                    . ' of related properties (' . implode(', ', $nextLevelPayment['relatedProperties']) . ')'
                    . ', got ' . $nextLevelPayment['paidSkillPoints']
                );
            }
        }
    }

    const PROPERTY_TO_SKILL_POINT_MULTIPLIER = PersonSkillPoint::SKILL_POINT_VALUE; // each point of property gives one skill point

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
            foreach ($sameTypeSkills as $skill) {
                /** @var PersonSkill $skill */
                $nextLevelSkills[$skill->getName()] = [];
                foreach ($skill->getSkillRanks() as $skillRank) {
                    if ($skillRank->getProfessionLevel()->isNextLevel()) {
                        $levelValue = $skillRank->getProfessionLevel()->getLevelRank()->getValue();
                        if (!isset($nextLevelSkills[$skill->getName()][$levelValue])) {
                            $nextLevelSkills[$skill->getName()][$levelValue] = [];
                        }
                        $nextLevelSkills[$skill->getName()][$levelValue][] = $skillRank;
                    }
                }
            }
        }
        $tooHighRankAdjustments = [];
        foreach ($nextLevelSkills as $skillName => $ranksPerLevel) {
            foreach ($ranksPerLevel as $levelValue => $skillRanks) {
                if (count($skillRanks) > self::MAX_SKILL_RANK_INCREASE_PER_NEXT_LEVEL) {
                    if (!isset($tooHighRankAdjustments[$skillName][$levelValue])) {
                        $tooHighRankAdjustments[$skillName][$levelValue] = $skillRanks;
                    }
                }
            }
        }
        if ($tooHighRankAdjustments) {
            throw new \LogicException(
                'Only on first level can be skill ranks increased more then ' . self::MAX_SKILL_RANK_INCREASE_PER_NEXT_LEVEL . '.'
                . ' Got ' . count($tooHighRankAdjustments) . ' skills with too high rank-per-level adjustment'
                . ' (' . self::getTooHighRankAdjustmentsDescription($tooHighRankAdjustments) . ')'
            );
        }
    }

    private static function getTooHighRankAdjustmentsDescription(array $tooHighRankAdjustments)
    {
        $descriptionParts = [];
        foreach ($tooHighRankAdjustments as $skillName => $ranksPerLevel) {
            $skillDescription = "skill $skillName over-increased on";
            $levelsDescriptions = [];
            foreach ($ranksPerLevel as $levelValue => $skillRanks) {
                $levelDescription = " level $levelValue to rank"
                    . implode(
                        ',',
                        array_map(function (PersonSkillRank $rank) {
                            return $rank->getValue();
                        }, $skillRanks)
                    );
                $levelsDescriptions[] = $levelDescription;
            }
            $skillDescription .= ' ' . implode(',', $levelsDescriptions);
            $descriptionParts[] = $skillDescription;
        }

        return implode(';', $descriptionParts);
    }

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var PersonPhysicalSkills
     *
     * @ORM\OneToOne(targetEntity="DrdPlus\Person\Skills\Physical\PhysicalSkills")
     */
    private $physicalSkills;

    /**
     * @var PersonPsychicalSkills
     *
     * @ORM\OneToOne(targetEntity="DrdPlus\Person\Skills\Psychical\PsychicalSkills")
     */
    private $psychicalSkills;

    /**
     * @var PersonCombinedSkills
     *
     * @ORM\OneToOne(targetEntity="DrdPlus\Person\Skills\Combined\CombinedSkills")
     */
    private $combinedSkills;

    private function __construct(
        PersonPhysicalSkills $physicalSkills,
        PersonPsychicalSkills $psychicalSkills,
        PersonCombinedSkills $combinedSkills
    )
    {
        $this->physicalSkills = $physicalSkills;
        $this->psychicalSkills = $psychicalSkills;
        $this->combinedSkills = $combinedSkills;
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
    public function getPhysicalSkills()
    {
        return $this->physicalSkills;
    }

    /**
     * @return PersonPsychicalSkills
     */
    public function getPsychicalSkills()
    {
        return $this->psychicalSkills;
    }

    /**
     * @return PersonCombinedSkills
     */
    public function getCombinedSkills()
    {
        return $this->combinedSkills;
    }

    /**
     * @return array|PersonSkill[]
     */
    public function getSkills()
    {
        return array_merge(
            $this->getPhysicalSkills()->getIterator()->getArrayCopy(),
            $this->getPsychicalSkills()->getIterator()->getArrayCopy(),
            $this->getCombinedSkills()->getIterator()->getArrayCopy()
        );
    }

}
