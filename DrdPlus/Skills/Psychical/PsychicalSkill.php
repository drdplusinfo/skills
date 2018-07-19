<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Skills\Skill;
use DrdPlus\Skills\SkillRank;
use Granam\Integer\PositiveIntegerObject;

/**
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\InheritanceType("SINGLE_TABLE")
 * @Doctrine\ORM\Mapping\DiscriminatorColumn(name="skillName", type="string")
 * @Doctrine\ORM\Mapping\DiscriminatorMap({
 *  "astronomy" = "Astronomy",
 *  "botany" = "Botany",
 *  "etiquetteOfGangland" = "EtiquetteOfGangland",
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
 * @method PsychicalSkillRank|SkillRank getCurrentSkillRank: SkillRank
 */
abstract class PsychicalSkill extends Skill
{

    /**
     * @var PsychicalSkillRank[]|ArrayCollection
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="PsychicalSkillRank", mappedBy="psychicalSkill", cascade={"persist"})
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
     * @return PsychicalSkillRank|SkillRank
     * @throws \DrdPlus\Skills\Exceptions\UnknownPaymentForSkillPoint
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyPaidSkillPoint
     */
    protected function createZeroSkillRank(ProfessionLevel $professionLevel): SkillRank
    {
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
        parent::addTypeVerifiedSkillRank(
            new PsychicalSkillRank(
                $this,
                $psychicalSkillPoint,
                new PositiveIntegerObject($this->getCurrentSkillRank()->getValue() + 1)
            )
        );
    }

    /**
     * @return Collection|ArrayCollection|PsychicalSkillRank[]
     */
    protected function getInnerSkillRanks(): Collection
    {
        return $this->psychicalSkillRanks;
    }

    /**
     * @return string[]
     */
    public function getRelatedPropertyCodes(): array
    {
        return [PropertyCode::INTELLIGENCE, PropertyCode::WILL];
    }

    /**
     * @return bool
     */
    public function isPsychical(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isPhysical(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isCombined(): bool
    {
        return false;
    }
}