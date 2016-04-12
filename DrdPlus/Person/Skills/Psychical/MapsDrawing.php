<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class MapsDrawing extends PersonPsychicalSkill
{
    const MAPS_DRAWING = SkillCodes::MAPS_DRAWING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::MAPS_DRAWING;
    }
}
