<?php
namespace DrdPlus\Skills;

use Doctrine\ORM\Mapping as ORM;
use Doctrineum\Entity\Entity;
use DrdPlus\Codes\Armaments\ProtectiveArmamentCode;
use DrdPlus\Codes\Armaments\WeaponCode;
use DrdPlus\Codes\PropertyCode;
use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Background\BackgroundParts\SkillsFromBackground;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\Combined\CombinedSkills;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\Physical\PhysicalSkills;
use DrdPlus\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Skills\Psychical\PsychicalSkills;
use DrdPlus\Tables\Armaments\Armourer;
use DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable;
use DrdPlus\Tables\Armaments\Weapons\WeaponSkillTable;
use DrdPlus\Tables\Tables;
use Granam\Strict\Object\StrictObject;

/**
 * @ORM\Entity()
 */
class Skills extends StrictObject implements \IteratorAggregate, \Countable, Entity
{
    const PHYSICAL = PhysicalSkills::PHYSICAL;
    const PSYCHICAL = PsychicalSkills::PSYCHICAL;
    const COMBINED = CombinedSkills::COMBINED;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var PhysicalSkills
     * @ORM\OneToOne(targetEntity="DrdPlus\Skills\Physical\PhysicalSkills", cascade={"persist"})
     */
    private $physicalSkills;

    /**
     * @var PsychicalSkills
     * @ORM\OneToOne(targetEntity="DrdPlus\Skills\Psychical\PsychicalSkills", cascade={"persist"})
     */
    private $psychicalSkills;

    /**
     * @var CombinedSkills
     * @ORM\OneToOne(targetEntity="DrdPlus\Skills\Combined\CombinedSkills", cascade={"persist"})
     */
    private $combinedSkills;

    /**
     * @param ProfessionLevels $professionLevels
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param PhysicalSkills $physicalSkills
     * @param PsychicalSkills $psychicalSkills
     * @param CombinedSkills $combinedSkills
     * @param Tables $tables
     * @return Skills
     * @throws \DrdPlus\Skills\Exceptions\HigherSkillRanksFromFirstLevelThanPossible
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\HigherSkillRanksFromNextLevelsThanPossible
     */
    public static function createSkills(
        ProfessionLevels $professionLevels,
        SkillsFromBackground $backgroundSkillPoints,
        PhysicalSkills $physicalSkills,
        PsychicalSkills $psychicalSkills,
        CombinedSkills $combinedSkills,
        Tables $tables
    )
    {
        self::checkPaymentForSkillPoints(
            $professionLevels,
            $backgroundSkillPoints,
            $physicalSkills,
            $psychicalSkills,
            $combinedSkills,
            $tables
        );
        self::checkNextLevelsSkillRanks($physicalSkills, $psychicalSkills, $combinedSkills);

        return new self($physicalSkills, $psychicalSkills, $combinedSkills);
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param PhysicalSkills $physicalSkills
     * @param PsychicalSkills $psychicalSkills
     * @param CombinedSkills $combinedSkills
     * @param Tables $tables
     * @throws \DrdPlus\Skills\Exceptions\HigherSkillRanksFromFirstLevelThanPossible
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\HigherSkillRanksFromNextLevelsThanPossible
     */
    private static function checkPaymentForSkillPoints(
        ProfessionLevels $professionLevels,
        SkillsFromBackground $backgroundSkillPoints,
        PhysicalSkills $physicalSkills,
        PsychicalSkills $psychicalSkills,
        CombinedSkills $combinedSkills,
        Tables $tables
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
     * @param PhysicalSkills $physicalSkills ,
     * @param PsychicalSkills $psychicalSkills ,
     * @param CombinedSkills $combinedSkill
     * @return array
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    private static function extractPropertyPayments(
        PhysicalSkills $physicalSkills,
        PsychicalSkills $psychicalSkills,
        CombinedSkills $combinedSkill
    )
    {
        $propertyPayments = self::getPaymentsSkeleton();
        foreach ([$physicalSkills, $psychicalSkills, $combinedSkill] as $sameTypeSkills) {
            /** @var Skill[] $sameTypeSkills */
            foreach ($sameTypeSkills as $skill) {
                foreach ($skill->getSkillRanks() as $skillRank) {
                    $paymentDetails = self::extractPaymentDetails($skillRank->getSkillPoint());
                    $propertyPayments = self::sumPayments([$propertyPayments, $paymentDetails]);
                }
            }
        }

        return $propertyPayments;
    }

    private static function getPaymentsSkeleton()
    {
        return [
            'zeroLevel' => [
                PhysicalSkillPoint::PHYSICAL => [],
                PsychicalSkillPoint::PSYCHICAL => [],
                CombinedSkillPoint::COMBINED => [],
            ],
            'firstLevel' => [
                PhysicalSkillPoint::PHYSICAL => ['spentFirstLevelSkillPoints' => 0, 'backgroundSkillPoints' => null],
                PsychicalSkillPoint::PSYCHICAL => ['spentFirstLevelSkillPoints' => 0, 'backgroundSkillPoints' => null],
                CombinedSkillPoint::COMBINED => ['spentFirstLevelSkillPoints' => 0, 'backgroundSkillPoints' => null],
            ],
            'nextLevels' => [
                PhysicalSkillPoint::PHYSICAL => ['spentNextLevelsSkillPoints' => 0, 'relatedProperties' => []],
                PsychicalSkillPoint::PSYCHICAL => ['spentNextLevelsSkillPoints' => 0, 'relatedProperties' => []],
                CombinedSkillPoint::COMBINED => ['spentNextLevelsSkillPoints' => 0, 'relatedProperties' => []],
            ],
        ];
    }

    /**
     * @param SkillPoint $skillPoint
     * @return array
     * @throws Exceptions\UnknownPaymentForSkillPoint
     */
    private static function extractPaymentDetails(SkillPoint $skillPoint)
    {
        $propertyPayment = self::getPaymentsSkeleton();

        if ($skillPoint->isPaidByFirstLevelSkillsFromBackground()) {
            /**
             * There are limited first level background skill points,
             *
             * @see \DrdPlus\Background\SkillsFromBackground
             * and @see \DrdPlus\Background\Ancestry
             * check their sum
             */
            $type = $skillPoint->getTypeName();
            $propertyPayment['firstLevel'][$type]['spentFirstLevelSkillPoints'] += $skillPoint->getValue();
            $propertyPayment['firstLevel'][$type]['backgroundSkillPoints'] = $skillPoint->getSkillsFromBackground();

            return $propertyPayment;
        }
        if ($skillPoint->isPaidByOtherSkillPoints()) {
            $firstPaidOtherSkillPoint = self::extractPaymentDetails($skillPoint->getFirstPaidOtherSkillPoint());
            $secondPaidOtherSkillPoint = self::extractPaymentDetails($skillPoint->getSecondPaidOtherSkillPoint());

            // the other skill points have to be extracted to first level background skills, see upper
            return self::sumPayments([$firstPaidOtherSkillPoint, $secondPaidOtherSkillPoint]);
        }
        if ($skillPoint->isPaidByNextLevelPropertyIncrease()) {
            // for every skill point of this type has to exists level property increase
            $type = $skillPoint->getTypeName();
            $propertyPayment['nextLevels'][$type]['spentNextLevelsSkillPoints'] += $skillPoint->getValue();
            $propertyPayment['nextLevels'][$type]['relatedProperties'] = $skillPoint->getRelatedProperties();

            return $propertyPayment;
        }
        if ($skillPoint->getValue() === 0) {
            return $propertyPayment;
        }
        throw new Exceptions\UnknownPaymentForSkillPoint(
            'Unknown payment for skill point ' . get_class($skillPoint)
        );
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
                self::checkIfSkillsFromBackgroundAreTheSame(
                    $firstLevelSkillPointPaymentOfType['backgroundSkillPoints'], $firstLevelSumPaymentOfType['backgroundSkillPoints']
                );
            } else {
                $firstLevelSumPaymentOfType['backgroundSkillPoints'] = $firstLevelSkillPointPaymentOfType['backgroundSkillPoints'];
            }
            $firstLevelSumPaymentOfType['spentFirstLevelSkillPoints'] += $firstLevelSkillPointPaymentOfType['spentFirstLevelSkillPoints'];
        }

        return $firstLevelSumPaymentOfType;
    }

    private static function checkIfSkillsFromBackgroundAreTheSame(
        SkillsFromBackground $firstSkillsFromBackground,
        SkillsFromBackground $secondSkillsFromBackground
    )
    {
        if ($firstSkillsFromBackground->getSpentBackgroundPoints() !== $secondSkillsFromBackground->getSpentBackgroundPoints()) {
            throw new Exceptions\SkillsFromBackgroundAreNotSame(
                'All skill points, originated in person background, have to use same background skill points.'
                . " Got different background skill points with values {$firstSkillsFromBackground->getSpentBackgroundPoints()}"
                . " and {$secondSkillsFromBackground->getSpentBackgroundPoints()}"
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

    /**
     * @param array $firstLevelPayments
     * @param ProfessionLevel $firstLevel
     * @param SkillsFromBackground $backgroundSkillPoints
     * @param Tables $tables
     * @throws \DrdPlus\Skills\Exceptions\HigherSkillRanksFromFirstLevelThanPossible
     */
    private static function checkFirstLevelPayment(
        array $firstLevelPayments,
        ProfessionLevel $firstLevel,
        SkillsFromBackground $backgroundSkillPoints,
        Tables $tables
    )
    {
        foreach ($firstLevelPayments as $skillType => $payment) {
            if (!$payment['spentFirstLevelSkillPoints']) {
                continue; // no skills have been "bought" at all
            }
            $paymentBackgroundSkills = $payment['backgroundSkillPoints'];
            self::checkIfSkillsFromBackgroundAreTheSame($paymentBackgroundSkills, $backgroundSkillPoints);
            $availableSkillPoints = 0;
            switch ($skillType) {
                case self::PHYSICAL :
                    $availableSkillPoints = $backgroundSkillPoints->getPhysicalSkillPoints(
                        $firstLevel->getProfession(),
                        $tables
                    );
                    break;
                case self::PSYCHICAL :
                    $availableSkillPoints = $backgroundSkillPoints->getPsychicalSkillPoints(
                        $firstLevel->getProfession(),
                        $tables
                    );
                    break;
                case self::COMBINED :
                    $availableSkillPoints = $backgroundSkillPoints->getCombinedSkillPoints(
                        $firstLevel->getProfession(),
                        $tables
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

    /**
     * @param array $nextLevelsPayment
     * @param ProfessionLevels $professionLevels
     * @throws \DrdPlus\Skills\Exceptions\HigherSkillRanksFromNextLevelsThanPossible
     */
    private static function checkNextLevelsPayment(array $nextLevelsPayment, ProfessionLevels $professionLevels)
    {
        foreach ($nextLevelsPayment as $skillsType => $nextLevelPayment) {
            $increasedPropertySum = 0;
            /** @var string[][] $nextLevelPayment */
            foreach ($nextLevelPayment['relatedProperties'] as $relatedProperty) {
                switch ($relatedProperty) {
                    case PropertyCode::STRENGTH :
                        $increasedPropertySum += $professionLevels->getNextLevelsStrengthModifier();
                        break;
                    case PropertyCode::AGILITY :
                        $increasedPropertySum += $professionLevels->getNextLevelsAgilityModifier();
                        break;
                    case PropertyCode::KNACK :
                        $increasedPropertySum += $professionLevels->getNextLevelsKnackModifier();
                        break;
                    case PropertyCode::WILL :
                        $increasedPropertySum += $professionLevels->getNextLevelsWillModifier();
                        break;
                    case PropertyCode::INTELLIGENCE :
                        $increasedPropertySum += $professionLevels->getNextLevelsIntelligenceModifier();
                        break;
                    case PropertyCode::CHARISMA :
                        $increasedPropertySum += $professionLevels->getNextLevelsCharismaModifier();
                        break;
                }
            }
            $maxSkillPoint = self::getSkillPointByPropertyIncrease($increasedPropertySum);
            if ($nextLevelPayment['spentNextLevelsSkillPoints'] > $maxSkillPoint) {
                /** @noinspection PhpToStringImplementationInspection */
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
        PhysicalSkills $physicalSkills,
        PsychicalSkills $psychicalSkills,
        CombinedSkills $combinedSkills
    )
    {
        $nextLevelSkills = [];
        foreach ([$physicalSkills, $psychicalSkills, $combinedSkills] as $sameTypeSkills) {
            /** @var Skill[] $sameTypeSkills */
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
         * @var SkillRank[][] $ranksPerLevel
         */
        foreach ($nextLevelSkills as $skillName => $ranksPerLevel) {
            /**
             * @var int $levelValue
             * @var SkillRank[] $skillRanks
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
        /** @var SkillRank[][] $ranksPerLevel */
        foreach ($tooHighRankAdjustments as $skillName => $ranksPerLevel) {
            $skillDescription = "skill '$skillName' over-increased on";
            $levelsDescriptions = [];
            foreach ($ranksPerLevel as $levelValue => $skillRanks) {
                $levelDescription = "level $levelValue to ranks "
                    . implode(
                        ' and ',
                        array_map(function (SkillRank $rank) {
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
     *
     * @see Skills::createSkills
     *
     * @param PhysicalSkills $physicalSkills
     * @param PsychicalSkills $psychicalSkills
     * @param CombinedSkills $combinedSkills
     */
    private function __construct(
        PhysicalSkills $physicalSkills,
        PsychicalSkills $psychicalSkills,
        CombinedSkills $combinedSkills
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
     * @return PhysicalSkills
     */
    public function getPhysicalSkills()
    {
        return $this->physicalSkills;
    }

    /**
     * @return PsychicalSkills
     */
    public function getPsychicalSkills()
    {
        return $this->psychicalSkills;
    }

    /**
     * @return CombinedSkills
     */
    public function getCombinedSkills()
    {
        return $this->combinedSkills;
    }

    /**
     * @return array|Skill[]
     */
    public function getSkills()
    {
        return array_merge(
            $this->getPhysicalSkills()->getIterator()->getArrayCopy(),
            $this->getPsychicalSkills()->getIterator()->getArrayCopy(),
            $this->getCombinedSkills()->getIterator()->getArrayCopy()
        );
    }

    /**
     * @return array|string[]
     */
    public function getCodesOfAllSkills()
    {
        return array_merge(
            PhysicalSkillCode::getPossibleValues(),
            PsychicalSkillCode::getPossibleValues(),
            CombinedSkillCode::getPossibleValues()
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
     * Note about SHIELD: shield is considered as a weapon and therefore zero skill is used for it (rare
     * FightWithShield respectively). If you want fight number for shield as a PROTECTIVE item, use @see
     * getMalusToFightNumberWithProtective instead. If yu are using two weapons, you will be punished for imperfect
     * fight-with-two-weapons skill as well.
     *
     * @param WeaponlikeCode $weaponOrShieldForAttack
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @param bool $usesTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Exceptions\UnknownTypeOfWeapon
     */
    public function getMalusToFightNumberWithWeaponlike(
        WeaponlikeCode $weaponOrShieldForAttack,
        WeaponSkillTable $missingWeaponSkillsTable,
        $usesTwoWeapons
    )
    {
        if ($weaponOrShieldForAttack->isProjectile()) {
            return 0;
        }
        if ($weaponOrShieldForAttack->isMelee() || $weaponOrShieldForAttack->isThrowingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPhysicalSkills()->getMalusToFightNumberWithWeaponlike(
                $weaponOrShieldForAttack,
                $missingWeaponSkillsTable,
                $usesTwoWeapons
            );
        }
        if ($weaponOrShieldForAttack->isShootingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getCombinedSkills()->getMalusToFightNumberWithShootingWeapon(
                $weaponOrShieldForAttack->convertToRangedWeaponCodeEquivalent(),
                $missingWeaponSkillsTable
            );
        }
        throw new Exceptions\UnknownTypeOfWeapon($weaponOrShieldForAttack);
    }

    /**
     * @param ProtectiveArmamentCode $protectiveArmamentCode
     * @param Armourer $armourer
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatArmament
     */
    public function getMalusToFightNumberWithProtective(ProtectiveArmamentCode $protectiveArmamentCode, Armourer $armourer)
    {
        return $this->getPhysicalSkills()->getMalusToFightNumberWithProtective($protectiveArmamentCode, $armourer);
    }

    /**
     * Note about SHIELD: shield is considered as a weapon and therefore zero skill is used for it (rare
     * FightWithShield respectively). If you want to use shield as a PROTECTIVE item, there is no attack number malus
     * from that.
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     * @throws \DrdPlus\Skills\Combined\Exceptions\CombinedSkillsDoNotHowToUseThatWeapon
     * @throws \DrdPlus\Skills\Exceptions\UnknownTypeOfWeapon
     */
    public function getMalusToAttackNumberWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        WeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        if ($weaponlikeCode->isProjectile()) {
            return 0;
        }
        if ($weaponlikeCode->isMelee() || $weaponlikeCode->isThrowingWeapon()) {
            return $this->getPhysicalSkills()->getMalusToAttackNumberWithWeaponlike(
                $weaponlikeCode,
                $missingWeaponSkillsTable,
                $fightsWithTwoWeapons
            );
        }
        if ($weaponlikeCode->isShootingWeapon()) {
            return $this->getCombinedSkills()->getMalusToAttackNumberWithShootingWeapon(
                $weaponlikeCode->convertToRangedWeaponCodeEquivalent(),
                $missingWeaponSkillsTable
            );
        }
        throw new Exceptions\UnknownTypeOfWeapon($weaponlikeCode);
    }

    /**
     * Usable both for weapons and shields, but SHIELD as "weaponlike" means for attacking - for shield standard usage
     * as a protective armament @see getMalusToCoverWithShield
     *
     * @param WeaponCode $weaponCode
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws Exceptions\UnknownTypeOfWeapon
     */
    public function getMalusToCoverWithWeapon(
        WeaponCode $weaponCode,
        WeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        if ($weaponCode->isProjectile()) {
            return 0;
        }
        if ($weaponCode->isMelee() || $weaponCode->isThrowingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPhysicalSkills()->getMalusToCoverWithWeapon(
                $weaponCode,
                $missingWeaponSkillsTable,
                $fightsWithTwoWeapons
            );
        }
        if ($weaponCode->isShootingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getCombinedSkills()->getMalusToCoverWithShootingWeapon(
                $weaponCode->convertToRangedWeaponCodeEquivalent(),
                $missingWeaponSkillsTable
            );
        }
        throw new Exceptions\UnknownTypeOfWeapon($weaponCode);
    }

    /**
     * @param ShieldUsageSkillTable $missingShieldSkillTable
     * @return int
     */
    public function getMalusToCoverWithShield(ShieldUsageSkillTable $missingShieldSkillTable)
    {
        return $this->getPhysicalSkills()->getMalusToCoverWithShield($missingShieldSkillTable);
    }

    /**
     * If you want to use shield as a PROTECTIVE item, there is no base of wounds malus from that.
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @param bool $fightsWithTwoWeapons
     * @return int
     * @throws Exceptions\UnknownTypeOfWeapon
     */
    public function getMalusToBaseOfWoundsWithWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        WeaponSkillTable $missingWeaponSkillsTable,
        $fightsWithTwoWeapons
    )
    {
        if ($weaponlikeCode->isMelee() || $weaponlikeCode->isThrowingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getPhysicalSkills()->getMalusToBaseOfWoundsWithWeaponlike(
                $weaponlikeCode,
                $missingWeaponSkillsTable,
                $fightsWithTwoWeapons
            );
        }
        if ($weaponlikeCode->isShootingWeapon()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getCombinedSkills()->getMalusToBaseOfWoundsWithShootingWeapon(
                $weaponlikeCode->convertToRangedWeaponCodeEquivalent(),
                $missingWeaponSkillsTable
            );
        }
        if ($weaponlikeCode->isProjectile()) {
            return 0;
        }
        throw new Exceptions\UnknownTypeOfWeapon($weaponlikeCode);
    }

}