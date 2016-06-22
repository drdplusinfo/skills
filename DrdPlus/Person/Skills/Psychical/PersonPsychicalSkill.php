<?php
namespace DrdPlus\Person\Skills\Psychical;

use Doctrine\Common\Collections\ArrayCollection;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="skillName", type="string")
 * @ORM\DiscriminatorMap({
 *  "astronomy" = "Astronomy",
 *  "botany" = "Botany",
 *  "etiquetteOfUnderworld" = "EtiquetteOfUnderworld",
 *  "foreignLanguage" = "ForeignLanguage",
 *  "geographyOfACountry" = "GeographyOfACountry",
 *  "handlingWithMagicalItems" = "HandlingWithMagicalItems",
 *  "historiography" = "Historiography",
 *  "knowledgeOfACity" = "KnowledgeOfACity",
 *  "knowledgeOfWorld" = "KnowledgeOfWorld",
 *  "mapsDrawing" = "MapsDrawing",
 *  "mythology" = "Mythology",
 *  "readingAndWriting" = "ReadingAndWriting",
 *  "socialEtiquette" = "SocialEtiquette",
 *  "technology" = "Technology",
 *  "theology" = "Theology",
 *  "zoology" = "Zoology"
 * })
 */
abstract class PersonPsychicalSkill extends PersonSkill
{

    /**
     * @var PsychicalSkillRank[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="PsychicalSkillRank", mappedBy="personPsychicalSkill", cascade={"persist"})
     */
    private $psychicalSkillRanks;

    public function __construct()
    {
        $this->psychicalSkillRanks = new ArrayCollection();
    }

    /**
     * @param PsychicalSkillRank $psychicalSkillRank
     */
    public function addSkillRank(PsychicalSkillRank $psychicalSkillRank)
    {
        parent::addTypeVerifiedSkillRank($psychicalSkillRank);
    }

    /**
     * @return ArrayCollection|PsychicalSkillRank[]
     */
    public function getPsychicalSkillRanks()
    {
        return $this->psychicalSkillRanks;
    }

    /**
     * @return ArrayCollection|PsychicalSkillRank[]
     */
    public function getSkillRanks()
    {
        return $this->getPsychicalSkillRanks();
    }

    /**
     * @return string[]
     */
    public function getRelatedPropertyCodes()
    {
        return [Intelligence::INTELLIGENCE, Will::WILL];
    }

    /**
     * @return bool
     */
    public function isPsychical()
    {
        return true;
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
    public function isCombined()
    {
        return false;
    }
}
