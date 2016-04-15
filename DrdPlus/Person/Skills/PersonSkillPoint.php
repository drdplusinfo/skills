<?php
namespace DrdPlus\Person\Skills;

use Doctrine\ORM\Mapping as ORM;
use Doctrineum\Entity\Entity;
use DrdPlus\Person\Background\BackgroundParts\BackgroundSkillPoints;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionNextLevel;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

/**
 * @ORM\MappedSuperclass()
 */
abstract class PersonSkillPoint extends StrictObject implements IntegerInterface, Entity
{
    const SKILL_POINT_VALUE = 1;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
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
     * @var PersonSkillPoint|null
     * @ORM\OneToOne(targetEntity="PersonSkillPoint", cascade={"persist"})
     */
    private $firstPaidOtherSkillPoint;
    /**
     * @var PersonSkillPoint|null
     * @ORM\OneToOne(targetEntity="PersonSkillPoint", cascade={"persist"})
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
     * @param ProfessionFirstLevel $professionFirstLevel
     * @param BackgroundSkillPoints $backgroundSkillPoints
     * @param Tables $tables
     *
     * @return static
     */
    public static function createFromFirstLevelBackgroundSkillPoints(
        ProfessionFirstLevel $professionFirstLevel,
        BackgroundSkillPoints $backgroundSkillPoints,
        Tables $tables
    )
    {
        return new static($tables, $professionFirstLevel, null /* next levels */, $backgroundSkillPoints);
    }

    /**
     * @param ProfessionFirstLevel $professionFirstLevel
     * @param PersonSkillPoint $firstPaidSkillPoint
     * @param PersonSkillPoint $secondPaidSkillPoint
     * @param Tables $tables
     *
     * @return static
     */
    public static function createFromFirstLevelCrossTypeSkillPoints(
        ProfessionFirstLevel $professionFirstLevel,
        PersonSkillPoint $firstPaidSkillPoint,
        PersonSkillPoint $secondPaidSkillPoint,
        Tables $tables
    )
    {
        return new static(
            $tables,
            $professionFirstLevel,
            null /* next levels */,
            null /* background skill points */,
            $firstPaidSkillPoint,
            $secondPaidSkillPoint
        );
    }

    /**
     * @param ProfessionNextLevel $professionNextLevel
     * @param Tables $tables
     *
     * @return static
     */
    public static function createFromNextLevelPropertyIncrease(
        ProfessionNextLevel $professionNextLevel,
        Tables $tables
    )
    {
        return new static($tables, null /* first level */, $professionNextLevel);
    }

    /**
     * You can pay by a level (by its property adjustment respectively) or by two another skill points
     * (for example combined and psychical for a new physical).
     *
     * @param Tables $tables
     * @param ProfessionFirstLevel|null $professionFirstLevel = null
     * @param ProfessionNextLevel|null $professionNextLevel = null
     * @param BackgroundSkillPoints|null $backgroundSkillPoints = null
     * @param PersonSkillPoint $firstPaidOtherSkillPoint = null
     * @param PersonSkillPoint $secondPaidOtherSkillPoint = null
     * @throws \DrdPlus\Person\Skills\Exceptions\UnknownPaymentForSkillPoint
     */
    protected function __construct(
        Tables $tables,
        ProfessionFirstLevel $professionFirstLevel = null,
        ProfessionNextLevel $professionNextLevel = null,
        BackgroundSkillPoints $backgroundSkillPoints = null,
        PersonSkillPoint $firstPaidOtherSkillPoint = null,
        PersonSkillPoint $secondPaidOtherSkillPoint = null
    )
    {
        $this->checkSkillPointPayment(
            $tables,
            $professionFirstLevel,
            $professionNextLevel,
            $backgroundSkillPoints,
            $firstPaidOtherSkillPoint,
            $secondPaidOtherSkillPoint
        );

        $this->professionFirstLevel = $professionFirstLevel;
        $this->professionNextLevel = $professionNextLevel;
        $this->backgroundSkillPoints = $backgroundSkillPoints;
        $this->firstPaidOtherSkillPoint = $firstPaidOtherSkillPoint;
        $this->secondPaidOtherSkillPoint = $secondPaidOtherSkillPoint;
    }

    private function checkSkillPointPayment(
        Tables $tables,
        ProfessionFirstLevel $professionFirstLevel = null,
        ProfessionNextLevel $professionNextLevel = null,
        BackgroundSkillPoints $backgroundSkillPoints = null,
        PersonSkillPoint $firstPaidOtherSkillPoint = null,
        PersonSkillPoint $secondPaidOtherSkillPoint = null
    )
    {
        if ($professionFirstLevel) {
            $this->checkFirstLevelPayment(
                $professionFirstLevel,
                $tables,
                $backgroundSkillPoints,
                $firstPaidOtherSkillPoint,
                $secondPaidOtherSkillPoint
            );
        } else if ($professionNextLevel) {
            $this->checkNextLevelPaymentByPropertyIncrement($professionNextLevel);
        } else {
            throw new Exceptions\UnknownPaymentForSkillPoint('Required first level of next level, got nothing');
        }
    }

    private function checkFirstLevelPayment(
        ProfessionFirstLevel $professionFirstLevel,
        Tables $tables,
        BackgroundSkillPoints $backgroundSkillPoints = null,
        PersonSkillPoint $firstPaidSkillPoint = null,
        PersonSkillPoint $secondPaidSkillPoint = null
    )
    {
        if ($backgroundSkillPoints) {
            $this->checkPayByFirstLevelBackgroundSkillPoints($professionFirstLevel, $tables, $backgroundSkillPoints);
        } else if ($firstPaidSkillPoint && $secondPaidSkillPoint) {
            $this->checkPayByOtherFirstLevelSkillPoints($firstPaidSkillPoint, $secondPaidSkillPoint);
        } else {
            throw new Exceptions\UnknownPaymentForSkillPoint(
                'Unknown payment for skill point on level '
                . $professionFirstLevel->getLevelRank()->getValue()
                . ' of profession ' . $professionFirstLevel->getProfession()->getValue()
            );
        }
    }

    /**
     * @param ProfessionFirstLevel $professionFirstLevel
     * @param Tables $tables
     * @param BackgroundSkillPoints $backgroundSkillPoints
     *
     * @return bool
     * @throws \DrdPlus\Person\Skills\Exceptions\EmptyFirstLevelBackgroundSkillPoints
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

    private function checkPayByOtherFirstLevelSkillPoints(PersonSkillPoint $firstPaidSkillPoint, PersonSkillPoint $secondPaidSkillPoint)
    {
        $this->checkPaidFirstLevelSkillPoint($firstPaidSkillPoint);
        $this->checkPaidFirstLevelSkillPoint($secondPaidSkillPoint);
    }

    private function checkPaidFirstLevelSkillPoint(PersonSkillPoint $paidSkillPoint)
    {
        if (!$paidSkillPoint->isPaidByFirstLevelBackgroundSkillPoints()) {
            $message = 'Skill point to-pay-with has to origin from first level background skills.';
            if ($paidSkillPoint->isPaidByNextLevelPropertyIncrease()) {
                $message .= ' Next level skill point is not allowed to trade.';
            }
            if ($paidSkillPoint->isPaidByOtherSkillPoints()) {
                $message .= ' There is no sense to trade first level skill point multiple times.';
            }
            throw new Exceptions\ProhibitedOriginOfPaidBySkillPoint($message);
        }
        if ($paidSkillPoint->getTypeName() === $this->getTypeName()) {
            throw new Exceptions\NonSensePaymentBySameType(
                "There is no sense to pay for skill point by another one of the very same type ({$this->getTypeName()})."
                . ' Got paid skill point from level ' . $paidSkillPoint->getProfessionLevel()->getLevelRank()
                . ' of profession ' . $paidSkillPoint->getProfessionLevel()->getProfession()->getValue() . '.'
            );
        }
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
        return static::SKILL_POINT_VALUE;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getValue();
    }

    /**
     * @return ProfessionFirstLevel|null
     */
    public function getProfessionFirstLevel()
    {
        return $this->professionFirstLevel;
    }

    /**
     * @return ProfessionLevel|null
     */
    public function getProfessionNextLevel()
    {
        return $this->professionNextLevel;
    }

    /**
     * @return ProfessionFirstLevel|ProfessionNextLevel
     */
    public function getProfessionLevel()
    {
        return $this->getProfessionFirstLevel() ?: $this->getProfessionNextLevel();
    }

    /**
     * @return BackgroundSkillPoints|null
     */
    public function getBackgroundSkillPoints()
    {
        return $this->backgroundSkillPoints;
    }

    /**
     * @return PersonSkillPoint|null
     */
    public function getFirstPaidOtherSkillPoint()
    {
        return $this->firstPaidOtherSkillPoint;
    }

    /**
     * @return PersonSkillPoint|null
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