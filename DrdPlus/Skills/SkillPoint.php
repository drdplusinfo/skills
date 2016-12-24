<?php
namespace DrdPlus\Skills;

use Doctrine\ORM\Mapping as ORM;
use Doctrineum\Entity\Entity;
use DrdPlus\Person\Background\BackgroundParts\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionNextLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionZeroLevel;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
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
     * @var BackgroundSkillPoints|null
     * @ORM\Column(type="background_skill_points", nullable=true)
     */
    private $backgroundSkillPoints;
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
    abstract public function getTypeName();

    /**
     * @return string[]
     */
    abstract public function getRelatedProperties();

    /**
     * @param ProfessionLevel $professionLevel
     * @return static|SkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    public static function createZeroSkillPoint(ProfessionLevel $professionLevel)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(0 /* skill point value */, $professionLevel);
    }

    /**
     * @param ProfessionFirstLevel $professionFirstLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param Tables $tables
     * @return static|SkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    public static function createFromFirstLevelBackgroundSkillPoints(
        ProfessionFirstLevel $professionFirstLevel,
        BackgroundSkillPoints $backgroundSkillPoints,
        Tables $tables
    )
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(
            1, // skill point value
            $professionFirstLevel,
            $tables,
            $backgroundSkillPoints
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
    )
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
    public static function createFromNextLevelPropertyIncrease(ProfessionNextLevel $professionNextLevel, Tables $tables)
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
     * @param BackgroundSkillPoints|null $backgroundSkillPoints = null
     * @param SkillPoint $firstPaidOtherSkillPoint = null
     * @param SkillPoint $secondPaidOtherSkillPoint = null
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedSkillPointValue
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \DrdPlus\Skills\Exceptions\UnknownProfessionLevelGroup
     * @throws \DrdPlus\Skills\Exceptions\InvalidRelatedProfessionLevel
     * @throws \DrdPlus\Skills\Exceptions\EmptyFirstLevelBackgroundSkillPoints
     * @throws \DrdPlus\Skills\Exceptions\NonSensePaymentBySameType
     * @throws \DrdPlus\Skills\Exceptions\ProhibitedOriginOfPaidBySkillPoint
     */
    protected function __construct(
        $skillPointValue,
        ProfessionLevel $professionLevel,
        Tables $tables = null,
        BackgroundSkillPoints $backgroundSkillPoints = null,
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
            $backgroundSkillPoints,
            $firstPaidOtherSkillPoint,
            $secondPaidOtherSkillPoint
        );
        $this->value = $skillPointValue;
        $this->backgroundSkillPoints = $backgroundSkillPoints;
        $this->firstPaidOtherSkillPoint = $firstPaidOtherSkillPoint;
        $this->secondPaidOtherSkillPoint = $secondPaidOtherSkillPoint;
    }

    /**
     * @param int $skillPointValue
     * @param ProfessionLevel $professionLevel
     * @param Tables|null $tables
     * @param BackgroundSkillPoints|null $backgroundSkillPoints
     * @param SkillPoint|null $firstPaidOtherSkillPoint
     * @param SkillPoint|null $secondPaidOtherSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedSkillPointValue
     * @throws \DrdPlus\Skills\Exceptions\InvalidRelatedProfessionLevel
     * @throws \DrdPlus\Skills\Exceptions\EmptyFirstLevelBackgroundSkillPoints
     * @throws \DrdPlus\Skills\Exceptions\NonSensePaymentBySameType
     * @throws \DrdPlus\Skills\Exceptions\ProhibitedOriginOfPaidBySkillPoint
     */
    private function checkSkillPointPayment(
        $skillPointValue,
        ProfessionLevel $professionLevel,
        Tables $tables = null,
        BackgroundSkillPoints $backgroundSkillPoints = null,
        SkillPoint $firstPaidOtherSkillPoint = null,
        SkillPoint $secondPaidOtherSkillPoint = null
    )
    {
        if ($skillPointValue === 1) {
            if ($professionLevel instanceof ProfessionFirstLevel) {
                $this->checkFirstLevelPayment(
                    $professionLevel,
                    $tables,
                    $backgroundSkillPoints,
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
     * @param BackgroundSkillPoints|null $backgroundSkillPoints
     * @param SkillPoint|null $firstPaidSkillPoint
     * @param SkillPoint|null $secondPaidSkillPoint
     * @return bool
     * @throws \DrdPlus\Skills\Exceptions\EmptyFirstLevelBackgroundSkillPoints
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\NonSensePaymentBySameType
     * @throws \DrdPlus\Skills\Exceptions\ProhibitedOriginOfPaidBySkillPoint
     */
    private function checkFirstLevelPayment(
        ProfessionFirstLevel $professionFirstLevel,
        Tables $tables,
        BackgroundSkillPoints $backgroundSkillPoints = null,
        SkillPoint $firstPaidSkillPoint = null,
        SkillPoint $secondPaidSkillPoint = null
    )
    {
        if ($backgroundSkillPoints) {
            return $this->checkPayByFirstLevelBackgroundSkillPoints($professionFirstLevel, $tables, $backgroundSkillPoints);
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
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @return bool
     * @throws \DrdPlus\Skills\Exceptions\EmptyFirstLevelBackgroundSkillPoints
     */
    private function checkPayByFirstLevelBackgroundSkillPoints(
        ProfessionFirstLevel $professionFirstLevel,
        Tables $tables,
        BackgroundSkillPoints $backgroundSkillPoints
    )
    {
        $relatedProperties = $this->sortAlphabetically($this->getRelatedProperties());
        $firstLevelSkillPoints = 0;
        switch ($relatedProperties) {
            case $this->sortAlphabetically([Strength::STRENGTH, Agility::AGILITY]) :
                $firstLevelSkillPoints = $backgroundSkillPoints->getPhysicalSkillPoints(
                    $professionFirstLevel->getProfession(), $tables
                );
                break;
            case $this->sortAlphabetically([Will::WILL, Intelligence::INTELLIGENCE]) :
                $firstLevelSkillPoints = $backgroundSkillPoints->getPsychicalSkillPoints(
                    $professionFirstLevel->getProfession(), $tables
                );
                break;
            case $this->sortAlphabetically([Knack::KNACK, Charisma::CHARISMA]) :
                $firstLevelSkillPoints = $backgroundSkillPoints->getCombinedSkillPoints(
                    $professionFirstLevel->getProfession(), $tables
                );
                break;
        }
        if ($firstLevelSkillPoints < 1) {
            throw new Exceptions\EmptyFirstLevelBackgroundSkillPoints(
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
    private function checkPayByOtherFirstLevelSkillPoints(SkillPoint $firstPaidBySkillPoint, SkillPoint $secondPaidBySkillPoint)
    {
        foreach ([$firstPaidBySkillPoint, $secondPaidBySkillPoint] as $paidBySkillPoint) {
            if (!$paidBySkillPoint->isPaidByFirstLevelBackgroundSkillPoints()) {
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

    private function checkNextLevelPaymentByPropertyIncrement(ProfessionNextLevel $professionNextLevel)
    {
        $relatedProperties = $this->sortAlphabetically($this->getRelatedProperties());
        $missingPropertyAdjustment = false;
        switch ($relatedProperties) {
            case $this->sortAlphabetically([Strength::STRENGTH, Agility::AGILITY]) :
                $missingPropertyAdjustment = $professionNextLevel->getStrengthIncrement()->getValue() === 0
                    && $professionNextLevel->getAgilityIncrement()->getValue() === 0;
                break;
            case $this->sortAlphabetically([Will::WILL, Intelligence::INTELLIGENCE]) :
                $missingPropertyAdjustment = $professionNextLevel->getWillIncrement()->getValue() === 0
                    && $professionNextLevel->getIntelligenceIncrement()->getValue() === 0;
                break;
            case $this->sortAlphabetically([Knack::KNACK, Charisma::CHARISMA]) :
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
     *
     * @return array
     */
    private function sortAlphabetically(array $array)
    {
        sort($array);

        return $array;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
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
     * @return ProfessionZeroLevel|ProfessionFirstLevel|ProfessionNextLevel
     */
    public function getProfessionLevel()
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
     * @return BackgroundSkillPoints|null
     */
    public function getBackgroundSkillPoints()
    {
        return $this->backgroundSkillPoints;
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
     * @return boolean
     */
    public function isPaidByFirstLevelBackgroundSkillPoints()
    {
        return $this->getBackgroundSkillPoints() !== null;
    }

    /**
     * @return boolean
     */
    public function isPaidByOtherSkillPoints()
    {
        return $this->getFirstPaidOtherSkillPoint() && $this->getSecondPaidOtherSkillPoint();
    }

    /**
     * @return boolean
     */
    public function isPaidByNextLevelPropertyIncrease()
    {
        return !$this->isPaidByFirstLevelBackgroundSkillPoints()
            && !$this->isPaidByOtherSkillPoints()
            && $this->getProfessionNextLevel() !== null;
    }

}