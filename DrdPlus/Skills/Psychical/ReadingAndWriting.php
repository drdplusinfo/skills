<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;

/**
 * @link https://pph.drdplus.info/#cteni_a_psani
 * @Doctrine\ORM\Mapping\Entity()
 */
class ReadingAndWriting extends PsychicalSkill
{
    public const READING_AND_WRITING = PsychicalSkillCode::READING_AND_WRITING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::READING_AND_WRITING;
    }

    /**
     * @return int
     */
    public function getBonusToReadingSpeed(): int
    {
        $currentSkillRankValue = $this->getCurrentSkillRank()->getValue();
        if ($currentSkillRankValue === 0) {
            return -164; // one hundred years
        }

        return ($this->getCurrentSkillRank()->getValue() - 1) * 3;
    }
}