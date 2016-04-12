<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ReadingAndWriting extends PersonPsychicalSkill
{
    const READING_AND_WRITING = SkillCodes::READING_AND_WRITING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::READING_AND_WRITING;
    }
}
