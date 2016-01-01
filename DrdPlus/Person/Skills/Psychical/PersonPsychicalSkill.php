<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "astronomy" = "Astronomy,
 *  "botany" = "Botany,
 *  "etiquetteOfUnderworld" = "EtiquetteOfUnderworld,
 *  "foreignLanguage" = "ForeignLanguage,
 *  "geographyOfACountry" = "GeographyOfACountry,
 *  "handlingOfMagicalItems" = "HandlingOfMagicalItems,
 *  "historiography" = "Historiography,
 *  "knowledgeOfACity" = "KnowledgeOfACity,
 *  "knowledgeOfWorld" = "KnowledgeOfWorld,
 *  "mapsDrawing" = "MapsDrawing,
 *  "mythology" = "Mythology,
 *  "readingAndWriting" = "ReadingAndWriting,
 *  "socialEtiquette" = "SocialEtiquette,
 *  "technology" = "Technology,
 *  "theology" = "Theology,
 *  "zoology" = "Zoology
 * })
 */
abstract class PersonPsychicalSkill extends PersonSkill
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     **/
    private $id;

    public function __construct(PsychicalSkillRank $psychicalSkillRank)
    {
        parent::__construct($psychicalSkillRank);
    }

    public function addPsychicalSkillRank(PsychicalSkillRank $psychicalSkillRank)
    {
        parent::addSkillRank($psychicalSkillRank);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function isPhysical()
    {
        return false;
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
    public function isCombined()
    {
        return false;
    }
}
