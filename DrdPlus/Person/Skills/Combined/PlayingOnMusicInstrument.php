<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class PlayingOnMusicInstrument extends PersonCombinedSkill
{
    const PLAYING_ON_MUSIC_INSTRUMENT = SkillCodes::PLAYING_ON_MUSIC_INSTRUMENT;

    /**
     * @return string
     */
    public function getName()
    {
        return self::PLAYING_ON_MUSIC_INSTRUMENT;
    }
}
