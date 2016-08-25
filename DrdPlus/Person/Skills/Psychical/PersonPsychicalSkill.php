<?php
namespace DrdPlus\Person\Skills\Psychical;

use Doctrine\Common\Collections\ArrayCollection;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\Skills\PersonSkill;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;
use Doctrine\ORM\Mapping as ORM;
use Granam\Integer\PositiveIntegerObject;

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
 * @method PsychicalSkillRank getCurrentSkillRank
 */
abstract class PersonPsychicalSkill extends PersonSkill
{

    /**
     * @var PsychicalSkillRank[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="PsychicalSkillRank", mappedBy="personPsychicalSkill", cascade={"persist"})
     */
    private $psychicalSkillRanks;

    /**
     * @param ProfessionLevel $professionLevel
     */
    public function __construct(ProfessionLevel $professionLevel)
    {
        $this->psychicalSkillRanks = new ArrayCollection();
        parent::__construct($professionLevel);
    }

    /**
     * @param ProfessionLevel $professionLevel
     * @return PsychicalSkillRank
     * @throws \DrdPlus\Person\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyOwningPersonSkill
     * @throws \DrdPlus\Person\Skills\Exceptions\CanNotVerifyPaidPersonSkillPoint
     */
    protected function createZeroSkillRank(ProfessionLevel $professionLevel)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new PsychicalSkillRank(
            $this,
            PsychicalSkillPoint::createZeroSkillPoint($professionLevel),
            new PositiveIntegerObject(0)
        );
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
    protected function getInnerSkillRanks()
    {
        return $this->psychicalSkillRanks;
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
