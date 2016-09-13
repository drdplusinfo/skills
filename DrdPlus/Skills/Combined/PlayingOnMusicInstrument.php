<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PlayingOnMusicInstrument extends CombinedSkill
{
    const PLAYING_ON_MUSIC_INSTRUMENT = CombinedSkillCode::PLAYING_ON_MUSIC_INSTRUMENT;

    /**
     * @return string
     */
    public function getName()
    {
        return self::PLAYING_ON_MUSIC_INSTRUMENT;
    }
}