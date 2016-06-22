<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ReadingAndWriting extends PersonPsychicalSkill
{
    const READING_AND_WRITING = PsychicalSkillCode::READING_AND_WRITING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::READING_AND_WRITING;
    }
}
