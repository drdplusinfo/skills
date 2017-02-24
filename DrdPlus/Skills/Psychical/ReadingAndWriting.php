<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ReadingAndWriting extends PsychicalSkill
{
    const READING_AND_WRITING = PsychicalSkillCode::READING_AND_WRITING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::READING_AND_WRITING;
    }
}