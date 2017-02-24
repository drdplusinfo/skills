<?php
namespace DrdPlus\Skills;

use Doctrine\ORM\Mapping as ORM;
use Doctrineum\Entity\Entity;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Background\BackgroundParts\SkillsFromBackground;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionNextLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionZeroLevel;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;
use Granam\Integer\PositiveInteger;
use Granam\Integer\Tools\Exceptions\PositiveIntegerCanNotBeNegative;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

/**
 * @ORM\MappedSuperclass()
 */
abstract class SkillPoint extends StrictObject implements PositiveInteger, Entity
{

    /**
     * @var integer|null
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var integer
     * @ORM\Column(type="integer", length=1)
     */
    private $value;
    /**
     * @var ProfessionZeroLevel|null
     * @ORM\ManyToOne(targetEntity="\DrdPlus\Person\ProfessionLevels\ProfessionZeroLevel", cascade={"persist"})
     */
    private $professionZeroLevel;
    /**
     * @var ProfessionFirstLevel|null
     * @ORM\ManyToOne(targetEntity="\DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel", cascade={"persist"})
     */
    private $professionFirstLevel;
    /**
     * @var ProfessionNextLevel|null
     * @ORM\ManyToOne(targetEntity="\DrdPlus\Person\ProfessionLevels\ProfessionNextLevel", cascade={"persist"})
     */
    private $professionNextLevel;
    /**
     * @var SkillsFromBackground|null
     * @ORM\Column(type="skills_from_background", nullable=true)
     */
    private $skillsFromBackground;
    /**
     * @var SkillPoint|null
     * @ORM\OneToOne(targetEntity="SkillPoint", cascade={"persist"})
     */
    private $firstPaidOtherSkillPoint;
    /**
     * @var SkillPoint|null
     * @ORM\OneToOne(targetEntity="SkillPoint", cascade={"persist"})
     */
    private $secondPaidOtherSkillPoint;

    /**
     * @return string
     */
    abstract public function getTypeName(): string;

    /**
     * @return array|string[]
     */
    abstract public function getRelatedProperties(): array;

    /**
     * @param ProfessionLevel $professionLevel
     * @return static|SkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    public static function createZeroSkillPoint(ProfessionLevel $professionLevel): self
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(0 /* skill point value */, $professionLevel);
    }

    /**
     * @param ProfessionFirstLevel $professionFirstLevel
     * @param SkillsFromBackground $skillsFromBackground
     * @param Tables $tables
     * @return static|SkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    public static function createFromFirstLevelSkillsFromBackground(
        ProfessionFirstLevel $professionFirstLevel,
        SkillsFromBackground $skillsFromBackground,
        Tables $tables
    ): self
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(
            1, // skill point value
            $professionFirstLevel,
            $tables,
            $skillsFromBackground
        );
    }

    /**
     * @param ProfessionFirstLevel $professionFirstLevel
     * @param SkillPoint $firstPaidSkillPoint
     * @param SkillPoint $secondPaidSkillPoint
     * @param Tables $tables
     * @return static|SkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    public static function createFromFirstLevelCrossTypeSkillPoints(
        ProfessionFirstLevel $professionFirstLevel,
        SkillPoint $firstPaidSkillPoint,
        SkillPoint $secondPaidSkillPoint,
        Tables $tables
    ): self
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(
            1, // skill point value
            $professionFirstLevel,
            $tables,
            null /* background skill points */,
            $firstPaidSkillPoint,
            $secondPaidSkillPoint
        );
    }

    /**
     * @param ProfessionNextLevel $professionNextLevel
     * @param Tables $tables
     * @return static|SkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    public static function createFromNextLevelPropertyIncrease(
        ProfessionNextLevel $professionNextLevel,
        Tables $tables
    ): self
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(1 /* skill point value */, $professionNextLevel, $tables);
    }

    /**
     * You can pay by a level (by its property adjustment respectively) or by two another skill points
     * (for example combined and psychical for a new physical).
     *
     * @param int|IntegerInterface $skillPointValue zero or one
     * @param ProfessionLevel $professionLevel
     * @param Tables|null $tables = null
     * @param SkillsFromBackground|null $skillsFromBackground = null
     * @param SkillPoint $firstPaidOtherSkillPoint = null
     * @param SkillPoint $secondPaidOtherSkillPoint = null
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedSkillPointValue
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \DrdPlus\Skills\Exceptions\UnknownProfessionLevelGroup
     * @throws \DrdPlus\Skills\Exceptions\InvalidRelatedProfessionLevel
     * @throws \DrdPlus\Skills\Exceptions\EmptyFirstLevelSkillsFromBackground
     * @throws \DrdPlus\Skills\Exceptions\NonSensePaymentBySameType
     * @throws \DrdPlus\Skills\Exceptions\ProhibitedOriginOfPaidBySkillPoint
     * @throws \DrdPlus\Skills\Exceptions\MissingPropertyAdjustmentForPayment
     */
    protected function __construct(
        $skillPointValue,
        ProfessionLevel $professionLevel,
        Tables $tables = null,
        SkillsFromBackground $skillsFromBackground = null,
        SkillPoint $firstPaidOtherSkillPoint = null,
        SkillPoint $secondPaidOtherSkillPoint = null
    )
    {
        try {
            $skillPointValue = ToInteger::toPositiveInteger($skillPointValue);
        } catch (PositiveIntegerCanNotBeNegative $positiveIntegerCanNotBeNegative) {
            throw new Exceptions\UnexpectedSkillPointValue(
                'Expected zero or one, got ' . ValueDescriber::describe($skillPointValue)
            );
        }
        if ($professionLevel instanceof ProfessionZeroLevel) {
            $this->professionZeroLevel = $professionLevel;
        } else if ($professionLevel instanceof ProfessionFirstLevel) {
            $this->professionFirstLevel = $professionLevel;
        } else if ($professionLevel instanceof ProfessionNextLevel) {
            $this->professionNextLevel = $professionLevel;
        } else {
            throw new Exceptions\UnknownProfessionLevelGroup(
                'Expected one of ' . ProfessionZeroLevel::class . ', ' . ProfessionFirstLevel::class
                . ', ' . ProfessionNextLevel::class . ', got ' . ValueDescriber::describe($professionLevel)
            );
        }
        $this->checkSkillPointPayment(
            $skillPointValue,
            $professionLevel,
            $tables,
            $skillsFromBackground,
            $firstPaidOtherSkillPoint,
            $secondPaidOtherSkillPoint
        );
        $this->value = $skillPointValue;
        $this->skillsFromBackground = $skillsFromBackground;
        $this->firstPaidOtherSkillPoint = $firstPaidOtherSkillPoint;
        $this->secondPaidOtherSkillPoint = $secondPaidOtherSkillPoint;
    }

    /**
     * @param int $skillPointValue
     * @param ProfessionLevel $professionLevel
     * @param Tables|null $tables
     * @param SkillsFromBackground|null $skillsFromBackground
     * @param SkillPoint|null $firstPaidOtherSkillPoint
     * @param SkillPoint|null $secondPaidOtherSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedSkillPointValue
     * @throws \DrdPlus\Skills\Exceptions\InvalidRelatedProfessionLevel
     * @throws \DrdPlus\Skills\Exceptions\EmptyFirstLevelSkillsFromBackground
     * @throws \DrdPlus\Skills\Exceptions\NonSensePaymentBySameType
     * @throws \DrdPlus\Skills\Exceptions\ProhibitedOriginOfPaidBySkillPoint
     * @throws \DrdPlus\Skills\Exceptions\MissingPropertyAdjustmentForPayment
     */
    private function checkSkillPointPayment(
        $skillPointValue,
        ProfessionLevel $professionLevel,
        Tables $tables = null,
        SkillsFromBackground $skillsFromBackground = null,
        SkillPoint $firstPaidOtherSkillPoint = null,
        SkillPoint $secondPaidOtherSkillPoint = null
    )
    {
        if ($skillPointValue === 1) {
            if ($professionLevel instanceof ProfessionFirstLevel) {
                $this->checkFirstLevelPayment(
                    $professionLevel,
                    $tables,
                    $skillsFromBackground,
                    $firstPaidOtherSkillPoint,
                    $secondPaidOtherSkillPoint
                );
            } else if ($professionLevel instanceof ProfessionNextLevel) {
                $this->checkNextLevelPaymentByPropertyIncrement($professionLevel);
            } else {
                throw new Exceptions\InvalidRelatedProfessionLevel(
                    'For non-zero skill point is needed one of first level or next level of a profession, got '
                    . $professionLevel->getProfession() . ' of level ' . $professionLevel->getLevelRank()
                );
            }
        } else if ($skillPointValue === 0) {
            return; // ok
        } else {
            throw new Exceptions\UnexpectedSkillPointValue(
                'Expected zero or one, got ' . ValueDescriber::describe($skillPointValue)
            );
        }
    }

    /**
     * @param ProfessionFirstLevel $professionFirstLevel
     * @param Tables $tables
     * @param SkillsFromBackground|null $skillsFromBackground
     * @param SkillPoint|null $firstPaidSkillPoint
     * @param SkillPoint|null $secondPaidSkillPoint
     * @return bool
     * @throws \DrdPlus\Skills\Exceptions\EmptyFirstLevelSkillsFromBackground
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\NonSensePaymentBySameType
     * @throws \DrdPlus\Skills\Exceptions\ProhibitedOriginOfPaidBySkillPoint
     */
    private function checkFirstLevelPayment(
        ProfessionFirstLevel $professionFirstLevel,
        Tables $tables,
        SkillsFromBackground $skillsFromBackground = null,
        SkillPoint $firstPaidSkillPoint = null,
        SkillPoint $secondPaidSkillPoint = null
    ): bool
    {
        if ($skillsFromBackground) {
            return $this->checkPayByFirstLevelSkillsFromBackground($professionFirstLevel, $tables, $skillsFromBackground);
        }
        if ($firstPaidSkillPoint && $secondPaidSkillPoint) {
            return $this->checkPayByOtherFirstLevelSkillPoints($firstPaidSkillPoint, $secondPaidSkillPoint);
        }

        throw new Exceptions\UnknownPaymentForSkillPoint(
            'Unknown payment for skill point on level '
            . $professionFirstLevel->getLevelRank()->getValue()
            . ' of profession ' . $professionFirstLevel->getProfession()->getValue()
        );
    }

    /**
     * @param ProfessionFirstLevel $professionFirstLevel
     * @param Tables $tables
     * @param SkillsFromBackground $skillsFromBackground
     * @return bool
     * @throws \DrdPlus\Skills\Exceptions\EmptyFirstLevelSkillsFromBackground
     */
    private function checkPayByFirstLevelSkillsFromBackground(
        ProfessionFirstLevel $professionFirstLevel,
        Tables $tables,
        SkillsFromBackground $skillsFromBackground
    ): bool
    {
        $relatedProperties = $this->sortAlphabetically($this->getRelatedProperties());
        $firstLevelSkillPoints = 0;
        switch ($relatedProperties) {
            case $this->sortAlphabetically([PropertyCode::STRENGTH, PropertyCode::AGILITY]) :
                $firstLevelSkillPoints = $skillsFromBackground->getPhysicalSkillPoints(
                    $professionFirstLevel->getProfession(),
                    $tables
                );
                break;
            case $this->sortAlphabetically([PropertyCode::WILL, PropertyCode::INTELLIGENCE]) :
                $firstLevelSkillPoints = $skillsFromBackground->getPsychicalSkillPoints(
                    $professionFirstLevel->getProfession(),
                    $tables
                );
                break;
            case $this->sortAlphabetically([PropertyCode::KNACK, PropertyCode::CHARISMA]) :
                $firstLevelSkillPoints = $skillsFromBackground->getCombinedSkillPoints(
                    $professionFirstLevel->getProfession(),
                    $tables
                );
                break;
        }
        if ($firstLevelSkillPoints < 1) {
            throw new Exceptions\EmptyFirstLevelSkillsFromBackground(
                'First level skill point has to come from the background.'
                . ' No skill point for properties ' . implode(',', $relatedProperties) . ' is available.'
            );
        }

        return true;
    }

    /**
     * @param SkillPoint $firstPaidBySkillPoint
     * @param SkillPoint $secondPaidBySkillPoint
     * @return bool
     * @throws \DrdPlus\Skills\Exceptions\NonSensePaymentBySameType
     * @throws \DrdPlus\Skills\Exceptions\ProhibitedOriginOfPaidBySkillPoint
     */
    private function checkPayByOtherFirstLevelSkillPoints(
        SkillPoint $firstPaidBySkillPoint,
        SkillPoint $secondPaidBySkillPoint
    ): bool
    {
        foreach ([$firstPaidBySkillPoint, $secondPaidBySkillPoint] as $paidBySkillPoint) {
            if (!$paidBySkillPoint->isPaidByFirstLevelSkillsFromBackground()) {
                $message = 'Skill point to-pay-with has to origin from first level background skills.';
                if ($paidBySkillPoint->isPaidByNextLevelPropertyIncrease()) {
                    $message .= ' Next level skill point is not allowed to trade.';
                }
                if ($paidBySkillPoint->isPaidByOtherSkillPoints()) {
                    $message .= ' There is no sense to trade first level skill point multiple times.';
                }
                throw new Exceptions\ProhibitedOriginOfPaidBySkillPoint($message);
            }
            if ($paidBySkillPoint->getTypeName() === $this->getTypeName()) {
                throw new Exceptions\NonSensePaymentBySameType(
                    "There is no sense to pay for skill point by another one of the very same type ({$this->getTypeName()})."
                    . ' Got paid skill point from level ' . $paidBySkillPoint->getProfessionLevel()->getLevelRank()
                    . ' of profession ' . $paidBySkillPoint->getProfessionLevel()->getProfession()->getValue() . '.'
                );
            }
        }

        return true;
    }

    /**
     * @param ProfessionNextLevel $professionNextLevel
     * @throws \DrdPlus\Skills\Exceptions\MissingPropertyAdjustmentForPayment
     */
    private function checkNextLevelPaymentByPropertyIncrement(ProfessionNextLevel $professionNextLevel)
    {
        $relatedProperties = $this->sortAlphabetically($this->getRelatedProperties());
        $missingPropertyAdjustment = false;
        switch ($relatedProperties) {
            case $this->sortAlphabetically([PropertyCode::STRENGTH, PropertyCode::AGILITY]) :
                $missingPropertyAdjustment = $professionNextLevel->getStrengthIncrement()->getValue() === 0
                    && $professionNextLevel->getAgilityIncrement()->getValue() === 0;
                break;
            case $this->sortAlphabetically([PropertyCode::WILL, PropertyCode::INTELLIGENCE]) :
                $missingPropertyAdjustment = $professionNextLevel->getWillIncrement()->getValue() === 0
                    && $professionNextLevel->getIntelligenceIncrement()->getValue() === 0;
                break;
            case $this->sortAlphabetically([PropertyCode::KNACK, PropertyCode::CHARISMA]) :
                $missingPropertyAdjustment = $professionNextLevel->getKnackIncrement()->getValue() === 0
                    && $professionNextLevel->getCharismaIncrement()->getValue() === 0;
                break;
        }

        if ($missingPropertyAdjustment) {
            throw new Exceptions\MissingPropertyAdjustmentForPayment(
                'The profession ' . $professionNextLevel->getProfession()->getValue()
                . ' of level ' . $professionNextLevel->getLevelRank()->getValue()
                . ' has to have adjusted either ' . implode(' or ', $this->getRelatedProperties())
            );
        }
    }

    /**
     * @param array $array
     * @return array
     */
    private function sortAlphabetically(array $array): array
    {
        sort($array);

        return $array;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getValue();
    }

    /**
     * @return ProfessionZeroLevel|null
     */
    public function getProfessionZeroLevel()
    {
        return $this->professionZeroLevel;
    }

    /**
     * @return ProfessionFirstLevel|null
     */
    public function getProfessionFirstLevel()
    {
        return $this->professionFirstLevel;
    }

    /**
     * @return ProfessionNextLevel|null
     */
    public function getProfessionNextLevel()
    {
        return $this->professionNextLevel;
    }

    /**
     * @return ProfessionLevel|ProfessionZeroLevel|ProfessionFirstLevel|ProfessionNextLevel
     */
    public function getProfessionLevel(): ProfessionLevel
    {
        if ($this->getProfessionZeroLevel()) {
            return $this->getProfessionZeroLevel();
        }
        if ($this->getProfessionFirstLevel()) {
            return $this->getProfessionFirstLevel();
        }
        assert($this->getProfessionNextLevel() !== null);

        return $this->getProfessionNextLevel();
    }

    /**
     * @return SkillsFromBackground|null
     */
    public function getSkillsFromBackground()
    {
        return $this->skillsFromBackground;
    }

    /**
     * @return SkillPoint|null
     */
    public function getFirstPaidOtherSkillPoint()
    {
        return $this->firstPaidOtherSkillPoint;
    }

    /**
     * @return SkillPoint|null
     */
    public function getSecondPaidOtherSkillPoint()
    {
        return $this->secondPaidOtherSkillPoint;
    }

    /**
     * @return bool
     */
    public function isPaidByFirstLevelSkillsFromBackground(): bool
    {
        return $this->getSkillsFromBackground() !== null;
    }

    /**
     * @return bool
     */
    public function isPaidByOtherSkillPoints(): bool
    {
        return $this->getFirstPaidOtherSkillPoint() && $this->getSecondPaidOtherSkillPoint();
    }

    /**
     * @return bool
     */
    public function isPaidByNextLevelPropertyIncrease(): bool
    {
        return !$this->isPaidByFirstLevelSkillsFromBackground()
            && !$this->isPaidByOtherSkillPoints()
            && $this->getProfessionNextLevel() !== null;
    }

}