<?php
namespace DrdPlus\Person\Skills;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Person\Background\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\PersonProperties\NextLevelsProperties;
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

    const PROPERTY_TO_SKILL_POINT_MULTIPLIER = 1; // each point of property gives one skill point
    const MAX_SKILL_RANK_INCREASE_PER_NEXT_LEVEL = 1;

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

    public function __construct(
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
            $this->getPhysicalSkills()->toArray(),
            $this->getPsychicalSkills()->toArray(),
            $this->getCombinedSkills()->toArray()
        );
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getFreeFirstLevelPhysicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        $firstLevelPhysicalPropertiesSum = $this->getFirstLevelPhysicalPropertiesSum($professionLevels);

        return $this->getFreeFirstLevelSkillPointsValue($firstLevelPhysicalPropertiesSum, $this->getPhysicalSkills());
    }

    private function getFirstLevelPhysicalPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getAgilityModifierForFirstProfession() + $professionLevels->getStrengthModifierForFirstProfession();
    }

    /**
     * @param int $firstLevelPropertiesSum as a potential of skill points
     * @param PersonSameTypeSkills $sameTypeSkills
     * @return int
     */
    private function getFreeFirstLevelSkillPointsValue(
        $firstLevelPropertiesSum,
        PersonSameTypeSkills $sameTypeSkills
    )
    {
        return $firstLevelPropertiesSum - $sameTypeSkills->getFirstLevelSkillRankSummary();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getFreeNextLevelsPhysicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        $nextLevelsPhysicalPropertiesSum = $this->getNextLevelsPhysicalPropertiesSum($professionLevels);

        return $this->getFreeNextLevelsSkillPointsValue($nextLevelsPhysicalPropertiesSum, $this->getPhysicalSkills());
    }

    private function getNextLevelsPhysicalPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getNextLevelsAgilityModifier() + $professionLevels->getNextLevelsStrengthModifier();
    }

    /**
     * @param int $nextLevelsPropertiesSum as a potential of skill points
     * @param PersonSameTypeSkills $sameTypeSkills
     * @return int
     */
    private function getFreeNextLevelsSkillPointsValue(
        $nextLevelsPropertiesSum,
        PersonSameTypeSkills $sameTypeSkills
    )
    {
        return $nextLevelsPropertiesSum - $sameTypeSkills->getNextLevelsSkillRankSummary();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getFreeFirstLevelPsychicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        $firstLevelPhysicalPropertiesSum = $this->getFirstLevelPsychicalPropertiesSum($professionLevels);

        return $this->getFreeFirstLevelSkillPointsValue($firstLevelPhysicalPropertiesSum, $this->getPsychicalSkills());
    }

    private function getFirstLevelPsychicalPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getWillModifierForFirstProfession() + $professionLevels->getIntelligenceModifierForFirstProfession();
    }

    /**
     * @param ProfessionLevels $professionLevels
     *
     * @return int
     */
    public function getFreeNextLevelsPsychicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        $nextLevelsPsychicalPropertiesSum = $this->getNextLevelsPsychicalPropertiesSum($professionLevels);

        return $this->getFreeNextLevelsSkillPointsValue($nextLevelsPsychicalPropertiesSum, $this->getPsychicalSkills());
    }

    private function getNextLevelsPsychicalPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getNextLevelsIntelligenceModifier() + $professionLevels->getNextLevelsWillModifier();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getFreeFirstLevelCombinedSkillPointsValue(ProfessionLevels $professionLevels)
    {
        $firstLevelCombinedPropertiesSum = $this->getFirstLevelCombinedPropertiesSum($professionLevels);

        return $this->getFreeFirstLevelSkillPointsValue($firstLevelCombinedPropertiesSum, $this->getCombinedSkills());
    }

    private function getFirstLevelCombinedPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getKnackModifierForFirstProfession() + $professionLevels->getCharismaModifierForFirstProfession();
    }

    /**
     * @param ProfessionLevels $professionLevels
     *
     * @return int
     */
    public function getFreeNextLevelsCombinedSkillPointsValue(ProfessionLevels $professionLevels)
    {
        $nextLevelsCombinedPropertiesSum = $this->getNextLevelsCombinedPropertiesSum($professionLevels);

        return $this->getFreeNextLevelsSkillPointsValue($nextLevelsCombinedPropertiesSum, $this->getCombinedSkills());
    }

    private function getNextLevelsCombinedPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getNextLevelsKnackModifier() + $professionLevels->getNextLevelsCharismaModifier();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param NextLevelsProperties $nextLevelsProperties
     * @param Tables $tables
     * @throws \LogicException
     */
    public function checkSkillPoints(
        ProfessionLevels $professionLevels,
        BackgroundSkillPoints $backgroundSkillPoints,
        NextLevelsProperties $nextLevelsProperties,
        Tables $tables
    )
    {
        $this->checkSkillRanks();
        $this->checkPaymentOfSkillPoints(
            $professionLevels->getFirstLevel(), $backgroundSkillPoints, $nextLevelsProperties, $tables
        );
    }

    private function checkSkillRanks()
    {
        $nextLevelSkills = [];
        foreach ($this->getSkills() as $skill) {
            foreach ($skill->getSkillRanks() as $skillRank) {
                $nextLevelSkills[$skill->getName()] = [];
                if ($skillRank->getProfessionLevel()->isNextLevel()) {
                    $levelValue = $skillRank->getProfessionLevel()->getLevelRank()->getValue();
                    if (!$nextLevelSkills[$skill->getName()][$levelValue]) {
                        $nextLevelSkills[$skill->getName()][$levelValue] = [];
                    }
                    $nextLevelSkills[$skill->getName()][$levelValue][] = $skillRank;
                }
            }
        }
        $tooHighRankAdjustments = [];
        foreach ($nextLevelSkills as $skillName => $ranksPerLevel) {
            foreach ($ranksPerLevel as $levelValue => $skillRanks) {
                if (count($skillRanks) > self::MAX_SKILL_RANK_INCREASE_PER_NEXT_LEVEL) {
                    if (!isset($tooHighRankAdjustments[$skillName][$levelValue])) {
                        $tooHighRankAdjustments[$levelValue] = $skillRanks;
                    }
                }
            }
        }
        if ($tooHighRankAdjustments) {
            throw new \LogicException(
                'Only on first level can be skill ranks increased more then ' . self::MAX_SKILL_RANK_INCREASE_PER_NEXT_LEVEL . '.'
                . ' Got ' . count($tooHighRankAdjustments) . ' skills with too high rank-per-level adjustment'
                . ' (' . $this->getTooHighRankAdjustmentsDescription($tooHighRankAdjustments) . ')'
            );
        }
    }

    private function getTooHighRankAdjustmentsDescription(array $tooHighRankAdjustments)
    {
        $description = [];
        foreach ($tooHighRankAdjustments as $skillName => $ranksPerLevel) {
            $skillDescription = "skill $skillName over-increased on";
            $levelsDescription = [];
            foreach ($ranksPerLevel as $levelValue => $ranks) {
                $levelDescription = " level $levelValue to ranks";
                /** @var PersonSkillRank $rank */
                $rankValues = [];
                foreach ($ranks as $rank) {
                    $rankValues[] = $rank->getValue();
                }
                $levelDescription .= implode(',', $rankValues);
                $levelsDescription[] = $levelDescription;
            }
            $skillDescription .= ' ' . implode(',', $levelsDescription);
            $description[] = $skillDescription;
        }

        return implode(';', $description);
    }

    private function checkPaymentOfSkillPoints(
        ProfessionLevel $firstLevel,
        BackgroundSkillPoints $backgroundSkillPoints,
        NextLevelsProperties $nextLevelsProperties,
        Tables $tables
    )
    {
        $propertyPayments = $this->getPaymentsSkeleton();

        foreach ($this->getSkills() as $skill) {
            foreach ($skill->getSkillRanks() as $skillRank) {
                $paymentDetails = $this->extractPaymentDetails($skillRank->getPersonSkillPoint());
                $propertyPayments = $this->sumPayments([$propertyPayments, $paymentDetails]);
            }
        }

        $this->checkFirstLevelPayment($propertyPayments['firstLevel'], $firstLevel, $backgroundSkillPoints, $tables);
        $this->checkNextLevelsPayment($propertyPayments['nextLevels'], $nextLevelsProperties);
    }

    private function getPaymentsSkeleton()
    {
        return [
            'firstLevel' => $this->getFirstLevelPaymentSkeleton(),
            'nextLevels' => $this->getNextLevelsPaymentSkeleton()
        ];
    }

    private function getNextLevelsPaymentSkeleton()
    {
        return [
            PhysicalSkillPoint::PHYSICAL => ['paymentSum' => 0, 'relatedProperties' => []],
            PsychicalSkillPoint::PSYCHICAL => ['paymentSum' => 0, 'relatedProperties' => []],
            CombinedSkillPoint::COMBINED => ['paymentSum' => 0, 'relatedProperties' => []]
        ];
    }

    private function getFirstLevelPaymentSkeleton()
    {
        return [
            PhysicalSkillPoint::PHYSICAL => ['paymentSum' => 0, 'backgroundSkillPoints' => null],
            PsychicalSkillPoint::PSYCHICAL => ['paymentSum' => 0, 'backgroundSkillPoints' => null],
            CombinedSkillPoint::COMBINED => ['paymentSum' => 0, 'backgroundSkillPoints' => null]
        ];
    }

    private function extractPaymentDetails(PersonSkillPoint $skillPoint)
    {
        $propertyPayment = $this->getPaymentsSkeleton();

        $type = $skillPoint->getTypeName();
        if ($skillPoint->isPaidByFirstLevelBackgroundSkillPoints()) {
            /**
             * there are limited first level background skills,
             * @see \DrdPlus\Person\Background\BackgroundSkillPoints
             * and @see \DrdPlus\Person\Background\Heritage
             * check their sum
             */
            $propertyPayment['firstLevel'][$type]['paymentSum']++;
            $propertyPayment['firstLevel'][$type]['backgroundSkillPoints'] = $skillPoint->getBackgroundSkillPoints(); // one to one

            return $propertyPayment;
        } else if ($skillPoint->isPaidByOtherSkillPoints()) {
            $firstPaidOtherSkillPoint = $this->extractPaymentDetails($skillPoint->getFirstPaidOtherSkillPoint());
            $secondPaidOtherSkillPoint = $this->extractPaymentDetails($skillPoint->getSecondPaidOtherSkillPoint());

            return $this->sumPayments([$firstPaidOtherSkillPoint, $secondPaidOtherSkillPoint]);
            /**
             * the other skill points have to be extracted to first level background skills, see upper
             */
        } else if ($skillPoint->isPaidByNextLevelPropertyIncrease()) {
            /**
             * for every skill point of this type has to exists level property increase
             */
            $propertyPayment['nextLevels'][$type]['paymentSum']++;
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
    private function sumPayments(array $skillPointPayments)
    {
        $sumPayment = $this->getPaymentsSkeleton();

        foreach ($skillPointPayments as $skillPointPayment) {
            foreach ([PhysicalSkillPoint::PHYSICAL, PsychicalSkillPoint::PSYCHICAL, CombinedSkillPoint::COMBINED] as $type) {
                $sumPayment = $this->sumFirstLevelPaymentForType($sumPayment, $skillPointPayment, $type);
                $sumPayment = $this->sumNextLevelsPaymentForType($sumPayment, $skillPointPayment, $type);
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
    private function sumFirstLevelPaymentForType(array $sumPayment, array $skillPointPayment, $type)
    {
        $sumPaymentOfType = &$sumPayment['firstLevel'][$type];
        $skillPointPaymentOfType = $skillPointPayment['firstLevel'][$type];

        $sumPaymentOfType['paymentSum'] += $skillPointPaymentOfType['paymentSum'];
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

    private function sumNextLevelsPaymentForType(array $sumPayment, array $skillPointPayment, $type)
    {
        $sumPaymentOfType = &$sumPayment['nextLevels'][$type];
        $skillPointPaymentOfType = $skillPointPayment['nextLevels'][$type];

        $sumPaymentOfType['paymentSum'] += $skillPointPaymentOfType['paymentSum'];
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

    private function checkFirstLevelPayment(
        array $firstLevelPayment, ProfessionLevel $firstLevel, BackgroundSkillPoints $backgroundSkillPoints, Tables $tables
    )
    {
        foreach ($firstLevelPayment as $type => $payment) {
            if (!$payment['backgroundSkillPoints']) {
                throw new \LogicException("Background skills are missing for first level payment of type $type");
            }
            /** @var BackgroundSkillPoints $paymentBackgroundSkills */
            $paymentBackgroundSkills = $payment['backgroundSkillPoints'];
            if ($paymentBackgroundSkills->getBackgroundPointsValue() !== $backgroundSkillPoints->getBackgroundPointsValue()) {
                throw new \LogicException(
                    "Background skills of current skills with value {$paymentBackgroundSkills->getBackgroundPointsValue()}"
                    . " are different to person background skills with value {$backgroundSkillPoints->getBackgroundPointsValue()}"
                );
            }
            $availableSkillPoints = 0;
            switch ($type) {
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
            if ($availableSkillPoints < $payment['paymentSum']) {
                throw new \LogicException(
                    "First level skills of type $type have higher ranks then possible."
                    . " Expected spent $availableSkillPoints skill points at most, counted " . $payment['paymentSum']
                );
            }
        }
    }

    private function checkNextLevelsPayment(array $nextLevelsPayment, NextLevelsProperties $nextLevelsProperties)
    {
        foreach ($nextLevelsPayment as $type => $payment) {
            $increasedPropertySum = 0;
            foreach ($payment['relatedProperties'] as $relatedProperty) {
                switch ($relatedProperty) {
                    case Strength::STRENGTH :
                        $increasedPropertySum += $nextLevelsProperties->getNextLevelsStrength()->getValue();
                        break;
                    case Agility::AGILITY :
                        $increasedPropertySum += $nextLevelsProperties->getNextLevelsAgility()->getValue();
                        break;
                    case Knack::KNACK :
                        $increasedPropertySum += $nextLevelsProperties->getNextLevelsKnack()->getValue();
                        break;
                    case Will::WILL :
                        $increasedPropertySum += $nextLevelsProperties->getNextLevelsWill()->getValue();
                        break;
                    case Intelligence::INTELLIGENCE :
                        $increasedPropertySum += $nextLevelsProperties->getNextLevelsIntelligence()->getValue();
                        break;
                    case Charisma::CHARISMA :
                        $increasedPropertySum += $nextLevelsProperties->getNextLevelsCharisma()->getValue();
                        break;
                }
            }
            if ($payment['paymentSum'] > $this->getSkillPointValueByPropertyIncrease($increasedPropertySum)) {
                throw new \LogicException(
                    "Skills from next levels of type $type have higher ranks then possible."
                    . " Max increase by next levels could be $increasedPropertySum, got " . $payment['paymentSum']
                );
            }
        }
    }

    /**
     * @param int $propertyIncrease
     * @return int
     */
    private function getSkillPointValueByPropertyIncrease($propertyIncrease)
    {
        return self::PROPERTY_TO_SKILL_POINT_MULTIPLIER * $propertyIncrease;
    }

}
