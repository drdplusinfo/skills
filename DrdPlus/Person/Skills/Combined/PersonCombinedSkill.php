<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Knack;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
  * "bigHandwork" = "BigHandwork",
  * "combinedSkills" = "CombinedSkills",
  * "cooking" = "Cooking",
  * "dancing" = "Dancing",
  * "duskSight" = "DuskSight",
  * "fightWithShootingWeapons" = "FightWithShootingWeapons",
  * "firstAid" = "FirstAid",
  * "gambling" = "Gambling",
  * "handingWithAnimals" = "HandingWithAnimals",
  * "handwork" = "Handwork",
  * "herbalism" = "Herbalism",
  * "huntingAndFishing" = "HuntingAndFishing",
  * "knotting" = "Knotting",
  * "painting" = "Painting",
  * "pedagogy" = "Pedagogy",
  * "flayingOnMusicInstrument" = "PlayingOnMusicInstrument",
  * "seduction" = "Seduction",
  * "showmanship" = "Showmanship",
  * "singing" = "Singing",
  * "statuary" = "Statuary"
 * })
 */
abstract class PersonCombinedSkill extends PersonSkill
{
    /**
     * @return string[]
     */
    public function getRelatedPropertyCodes()
    {
        return [Knack::KNACK, Charisma::CHARISMA];
    }

    /**
     * @return bool
     */
    public function isPhysical()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isPsychical()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isCombined()
    {
        return true;
    }

}
