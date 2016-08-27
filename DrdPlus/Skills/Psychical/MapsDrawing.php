<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class MapsDrawing extends PsychicalSkill
{
    const MAPS_DRAWING = PsychicalSkillCode::MAPS_DRAWING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::MAPS_DRAWING;
    }
}
