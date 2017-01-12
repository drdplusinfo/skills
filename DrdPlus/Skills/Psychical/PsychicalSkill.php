<?php
namespace DrdPlus\Skills\Psychical;

use Doctrine\Common\Collections\ArrayCollection;
use DrdPlus\Codes\PropertyCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Skills\Skill;
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
abstract class PsychicalSkill extends Skill
{

    /**
     * @var PsychicalSkillRank[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="PsychicalSkillRank", mappedBy="psychicalSkill", cascade={"persist"})
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
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
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
     * @param PsychicalSkillPoint $psychicalSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedRankValue
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     */
    public function increaseSkillRank(PsychicalSkillPoint $psychicalSkillPoint)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::addTypeVerifiedSkillRank(
            new PsychicalSkillRank(
                $this,
                $psychicalSkillPoint,
                new PositiveIntegerObject($this->getCurrentSkillRank()->getValue() + 1)
            )
        );
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
        return [PropertyCode::INTELLIGENCE, PropertyCode::WILL];
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
