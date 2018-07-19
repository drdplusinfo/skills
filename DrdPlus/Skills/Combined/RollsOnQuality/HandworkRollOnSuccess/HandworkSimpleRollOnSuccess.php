<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined\RollsOnQuality\HandworkRollOnSuccess;

use DrdPlus\RollsOn\QualityAndSuccess\SimpleRollOnSuccess;
use DrdPlus\Skills\Combined\RollsOnQuality\HandworkQuality;

abstract class HandworkSimpleRollOnSuccess extends SimpleRollOnSuccess
{
    /**
     * @var int
     */
    private $difficultyModification;

    /**
     * @param int $baseDifficulty
     * @param int $difficultyModification
     * @param HandworkQuality $handworkQuality
     * @param string $successValue
     * @param string $failureValue
     */
    public function __construct(
        int $baseDifficulty,
        int $difficultyModification,
        HandworkQuality $handworkQuality,
        string $successValue,
        string $failureValue
    )
    {
        parent::__construct($baseDifficulty + $difficultyModification, $handworkQuality, $successValue, $failureValue);
        $this->difficultyModification = $difficultyModification;
    }

    /**
     * @return int
     */
    public function getDifficultyModification(): int
    {
        return $this->difficultyModification;
    }
}