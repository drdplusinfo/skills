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
     * @throws \LogicException
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
        self::checkNextLevelsPayment(
            $paymentsFoSkills['nextLevels'],
            $professionLevels->getNextLevelsStrengthModifier(),
            $professionLevels->getNextLevelsAgilityModifier(),
            $professionLevels->getNextLevelsKnackModifier(),
            $professionLevels->getNextLevelsWillModifier(),
            $professionLevels->getNextLevelsIntelligenceModifier(),
            $professionLevels->getNextLevelsCharismaModifier()
        );
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
            throw new \LogicException("Unknown payment for skill point of ID {$skillPoint->getId()}");
        }
    }

    /**
     * @param array $skillPointPayments
     *
     * @return array
     */
    private static function sumPayments(array $skillPointPayments)
    {
        $sumPayment = self::getPaymentsSkeleton();

        foreach ($skillPointPayments as $skillPointPayment) {
            foreach ([PhysicalSkillPoint::PHYSICAL, PsychicalSkillPoint::PSYCHICAL, CombinedSkillPoint::COMBINED] as $type) {
                $sumPayment = self::sumFirstLevelPaymentForType($sumPayment, $skillPointPayment, $type);
                $sumPayment = self::sumNextLevelsPaymentForType($sumPayment, $skillPointPayment, $type);
            }
        }

        return $sumPayment;
    }

    /**
     * @param array $sumPayment
     * @param array $skillPointPayment
     * @param string $type
     *
     * @return array
     */
    private static function sumFirstLevelPaymentForType(array $sumPayment, array $skillPointPayment, $type)
    {
        $sumPaymentOfType = &$sumPayment['firstLevel'][$type];
        $skillPointPaymentOfType = $skillPointPayment['firstLevel'][$type];

        $sumPaymentOfType['paidSkillPoints'] += $skillPointPaymentOfType['paidSkillPoints'];
        if (!$sumPaymentOfType['backgroundSkillPoints']) {
            if ($skillPointPaymentOfType['backgroundSkillPoints']) {
                $sumPaymentOfType['backgroundSkillPoints'] = $skillPointPaymentOfType['backgroundSkillPoints'];
            }
        } else if ($skillPointPaymentOfType['backgroundSkillPoints']) {
            /** @var BackgroundSkillPoints $skillPointPaymentBackgroundSkills */
            $skillPointPaymentBackgroundSkills = $skillPointPaymentOfType['backgroundSkillPoints'];
            /** @var BackgroundSkillPoints $sumPaymentBackgroundSkills */
            $sumPaymentBackgroundSkills = $sumPaymentOfType['backgroundSkillPoints'];
            if ($skillPointPaymentBackgroundSkills->getBackgroundPointsValue() !== $sumPaymentBackgroundSkills->getBackgroundPointsValue()) {
                throw new \LogicException(
                    "All skill points, originated in background skills, have to use same background."
                    . " Got background skills with value {$skillPointPaymentBackgroundSkills->getBackgroundPointsValue()} and {$sumPaymentBackgroundSkills->getBackgroundPointsValue()}"
                );
            }
        }

        return $sumPayment;
    }

    private static function sumNextLevelsPaymentForType(array $sumPayment, array $skillPointPayment, $type)
    {
        $sumPaymentOfType = &$sumPayment['nextLevels'][$type];
        $skillPointPaymentOfType = $skillPointPayment['nextLevels'][$type];

        $sumPaymentOfType['paidSkillPoints'] += $skillPointPaymentOfType['paidSkillPoints'];
        if (!$sumPaymentOfType['relatedProperties']) {
            if ($skillPointPaymentOfType['relatedProperties']) {
                $sumPaymentOfType['relatedProperties'] = $skillPointPaymentOfType['relatedProperties'];
            }
        } else if ($skillPointPaymentOfType['relatedProperties']) {
            /** @var string[] $skillPointRelatedProperties */
            $skillPointRelatedProperties = $skillPointPaymentOfType['relatedProperties'];
            /** @var string[] $sumPaymentRelatedProperties */
            $sumPaymentRelatedProperties = $sumPaymentOfType['relatedProperties'];
            if ($skillPointRelatedProperties !== $sumPaymentRelatedProperties) {
                throw new \LogicException(
                    "All next level skill points of same type ($type) have to use same related properties."
                    . ' Got ' . implode(',', $skillPointRelatedProperties) . ' and ' . implode(',', $sumPaymentRelatedProperties)
                );
            }
        }

        return $sumPayment;
    }

    private static function checkFirstLevelPayment(
        array $firstLevelPayments, ProfessionLevel $firstLevel, BackgroundSkillPoints $backgroundSkillPoints, Tables $tables
    )
    {
        foreach ($firstLevelPayments as $skillType => $payment) {
            if (!$payment['paidSkillPoints']) {
                continue; // no skills have been "bought" at all
            }
            $paymentBackgroundSkills = $payment['backgroundSkillPoints'];
            /** @var BackgroundSkillPoints $paymentBackgroundSkills */
            if ($paymentBackgroundSkills->getBackgroundPointsValue() !== $backgroundSkillPoints->getBackgroundPointsValue()) {
                throw new \LogicException(
                    "Background skills of current skills with value {$paymentBackgroundSkills->getBackgroundPointsValue()}"
                    . " have to be same as person background skills with value {$backgroundSkillPoints->getBackgroundPointsValue()}"
                );
            }
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
                throw new Exceptions\HigherSkillRanksThanPossible(
                    "First level skills of type $skillType have higher ranks then possible."
                    . " Expected spent $availableSkillPoints skill points at most, got " . $payment['paidSkillPoints']
                );
            }
        }
    }

    private static function checkNextLevelsPayment(
        array $nextLevelsPayment,
        $nextLevelsStrengthModifier,
        $nextLevelsAgilityModifier,
        $nextLevelsKnackModifier,
        $nextLevelsWillModifier,
        $nextLevelsIntelligenceModifier,
        $nextLevelsCharismaModifier
    )
    {
        self::checkNextLevelsAvailableVsUsedSkillPoints(
            $nextLevelsPayment,
            $nextLevelsStrengthModifier,
            $nextLevelsAgilityModifier,
            $nextLevelsKnackModifier,
            $nextLevelsWillModifier,
            $nextLevelsIntelligenceModifier,
            $nextLevelsCharismaModifier
        );
    }

    private static function checkNextLevelsAvailableVsUsedSkillPoints(
        array $nextLevelsPayment,
        $nextLevelsStrengthModifier,
        $nextLevelsAgilityModifier,
        $nextLevelsKnackModifier,
        $nextLevelsWillModifier,
        $nextLevelsIntelligenceModifier,
        $nextLevelsCharismaModifier
    )
    {
        foreach ($nextLevelsPayment as $skillsType => $payment) {
            $increasedPropertySum = 0;
            foreach ($payment['relatedProperties'] as $relatedProperty) {
                switch ($relatedProperty) {
                    case Strength::STRENGTH :
                        $increasedPropertySum += $nextLevelsStrengthModifier;
                        break;
                    case Agility::AGILITY :
                        $increasedPropertySum += $nextLevelsAgilityModifier;
                        break;
                    case Knack::KNACK :
                        $increasedPropertySum += $nextLevelsKnackModifier;
                        break;
                    case Will::WILL :
                        $increasedPropertySum += $nextLevelsWillModifier;
                        break;
                    case Intelligence::INTELLIGENCE :
                        $increasedPropertySum += $nextLevelsIntelligenceModifier;
                        break;
                    case Charisma::CHARISMA :
                        $increasedPropertySum += $nextLevelsCharismaModifier;
                        break;
                }
            }
            $maxSkillPoint = self::getSkillPointByPropertyIncrease($increasedPropertySum);
            if ($payment['paidSkillPoints'] > $maxSkillPoint) {
                throw new \LogicException(
                    "Skills from next levels of type $skillsType have higher ranks then possible."
                    . " Max increase by next levels can be $maxSkillPoint, got " . $payment['paidSkillPoints']
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
