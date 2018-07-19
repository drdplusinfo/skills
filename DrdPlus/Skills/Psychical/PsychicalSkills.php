<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\SameTypeSkills;

/**
 * @Doctrine\ORM\Mapping\Entity()
 */
class PsychicalSkills extends SameTypeSkills
{

    public const PSYCHICAL = SkillTypeCode::PSYCHICAL;

    /**
     * @var Astronomy
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Astronomy", cascade={"persist"}, orphanRemoval=true)
     */
    private $astronomy;
    /**
     * @var Botany
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Botany", cascade={"persist"}, orphanRemoval=true)
     */
    private $botany;
    /**
     * @var EtiquetteOfGangland
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="EtiquetteOfGangLand", cascade={"persist"}, orphanRemoval=true)
     */
    private $etiquetteOfGangland;
    /**
     * @var ForeignLanguage
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="ForeignLanguage", cascade={"persist"}, orphanRemoval=true)
     */
    private $foreignLanguage;
    /**
     * @var GeographyOfACountry
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="GeographyOfACountry", cascade={"persist"}, orphanRemoval=true)
     */
    private $geographyOfACountry;
    /**
     * @var HandlingWithMagicalItems
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="HandlingWithMagicalItems", cascade={"persist"}, orphanRemoval=true)
     */
    private $handlingWithMagicalItems;
    /**
     * @var Historiography
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Historiography", cascade={"persist"}, orphanRemoval=true)
     */
    private $historiography;
    /**
     * @var KnowledgeOfACity
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="KnowledgeOfACity", cascade={"persist"}, orphanRemoval=true)
     */
    private $knowledgeOfACity;
    /**
     * @var KnowledgeOfWorld
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="KnowledgeOfWorld", cascade={"persist"}, orphanRemoval=true)
     */
    private $knowledgeOfWorld;
    /**
     * @var MapsDrawing
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="MapsDrawing", cascade={"persist"}, orphanRemoval=true)
     */
    private $mapsDrawing;
    /**
     * @var Mythology
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Mythology", cascade={"persist"}, orphanRemoval=true)
     */
    private $mythology;
    /**
     * @var ReadingAndWriting
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="ReadingAndWriting", cascade={"persist"}, orphanRemoval=true)
     */
    private $readingAndWriting;
    /**
     * @var SocialEtiquette
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="SocialEtiquette", cascade={"persist"}, orphanRemoval=true)
     */
    private $socialEtiquette;
    /**
     * @var Technology
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Technology", cascade={"persist"}, orphanRemoval=true)
     */
    private $technology;
    /**
     * @var Theology
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Theology", cascade={"persist"}, orphanRemoval=true)
     */
    private $theology;
    /**
     * @var Zoology
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Zoology", cascade={"persist"}, orphanRemoval=true)
     */
    private $zoology;

    /**
     * @param ProfessionLevel $professionLevel
     */
    protected function populateAllSkills(ProfessionLevel $professionLevel)
    {
        $this->astronomy = new Astronomy($professionLevel);
        $this->botany = new Botany($professionLevel);
        $this->etiquetteOfGangland = new EtiquetteOfGangland($professionLevel);
        $this->foreignLanguage = new ForeignLanguage($professionLevel);
        $this->geographyOfACountry = new GeographyOfACountry($professionLevel);
        $this->handlingWithMagicalItems = new HandlingWithMagicalItems($professionLevel);
        $this->historiography = new Historiography($professionLevel);
        $this->knowledgeOfACity = new KnowledgeOfACity($professionLevel);
        $this->knowledgeOfWorld = new KnowledgeOfWorld($professionLevel);
        $this->mapsDrawing = new MapsDrawing($professionLevel);
        $this->mythology = new Mythology($professionLevel);
        $this->readingAndWriting = new ReadingAndWriting($professionLevel);
        $this->socialEtiquette = new SocialEtiquette($professionLevel);
        $this->technology = new Technology($professionLevel);
        $this->theology = new Theology($professionLevel);
        $this->zoology = new Zoology($professionLevel);
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getUnusedFirstLevelPsychicalSkillPointsValue(ProfessionLevels $professionLevels): int
    {
        return $this->getUnusedFirstLevelSkillPointsValue($this->getFirstLevelPhysicalPropertiesSum($professionLevels));
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    private function getFirstLevelPhysicalPropertiesSum(ProfessionLevels $professionLevels): int
    {
        return $professionLevels->getFirstLevelWillModifier() + $professionLevels->getFirstLevelIntelligenceModifier();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getUnusedNextLevelsPsychicalSkillPointsValue(ProfessionLevels $professionLevels): int
    {
        return $this->getUnusedNextLevelsSkillPointsValue($this->getNextLevelsPsychicalPropertiesSum($professionLevels));
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    private function getNextLevelsPsychicalPropertiesSum(ProfessionLevels $professionLevels): int
    {
        return $professionLevels->getNextLevelsWillModifier() + $professionLevels->getNextLevelsIntelligenceModifier();
    }

    /**
     * @return \Traversable|\ArrayIterator|PsychicalSkill[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator([
            $this->getAstronomy(),
            $this->getBotany(),
            $this->getEtiquetteOfGangland(),
            $this->getForeignLanguage(),
            $this->getGeographyOfACountry(),
            $this->getHandlingWithMagicalItems(),
            $this->getHistoriography(),
            $this->getKnowledgeOfACity(),
            $this->getKnowledgeOfWorld(),
            $this->getMapsDrawing(),
            $this->getMythology(),
            $this->getReadingAndWriting(),
            $this->getSocialEtiquette(),
            $this->getTechnology(),
            $this->getTheology(),
            $this->getZoology(),
        ]);
    }

    /**
     * @return Astronomy
     */
    public function getAstronomy(): Astronomy
    {
        return $this->astronomy;
    }

    /**
     * @return Botany
     */
    public function getBotany(): Botany
    {
        return $this->botany;
    }

    /**
     * @return EtiquetteOfGangland
     */
    public function getEtiquetteOfGangland(): EtiquetteOfGangland
    {
        return $this->etiquetteOfGangland;
    }

    /**
     * @return ForeignLanguage
     */
    public function getForeignLanguage(): ForeignLanguage
    {
        return $this->foreignLanguage;
    }

    /**
     * @return GeographyOfACountry
     */
    public function getGeographyOfACountry(): GeographyOfACountry
    {
        return $this->geographyOfACountry;
    }

    /**
     * @return HandlingWithMagicalItems
     */
    public function getHandlingWithMagicalItems(): HandlingWithMagicalItems
    {
        return $this->handlingWithMagicalItems;
    }

    /**
     * @return Historiography
     */
    public function getHistoriography(): Historiography
    {
        return $this->historiography;
    }

    /**
     * @return KnowledgeOfACity
     */
    public function getKnowledgeOfACity(): KnowledgeOfACity
    {
        return $this->knowledgeOfACity;
    }

    /**
     * @return KnowledgeOfWorld
     */
    public function getKnowledgeOfWorld(): KnowledgeOfWorld
    {
        return $this->knowledgeOfWorld;
    }

    /**
     * @return MapsDrawing
     */
    public function getMapsDrawing(): MapsDrawing
    {
        return $this->mapsDrawing;
    }

    /**
     * @return Mythology
     */
    public function getMythology(): Mythology
    {
        return $this->mythology;
    }

    /**
     * @return ReadingAndWriting
     */
    public function getReadingAndWriting(): ReadingAndWriting
    {
        return $this->readingAndWriting;
    }

    /**
     * @return SocialEtiquette
     */
    public function getSocialEtiquette(): SocialEtiquette
    {
        return $this->socialEtiquette;
    }

    /**
     * @return Technology
     */
    public function getTechnology(): Technology
    {
        return $this->technology;
    }

    /**
     * @return Theology
     */
    public function getTheology(): Theology
    {
        return $this->theology;
    }

    /**
     * @return Zoology
     */
    public function getZoology(): Zoology
    {
        return $this->zoology;
    }

    /**
     * @return int
     */
    public function getBonusToAttackNumberAgainstFreeWillAnimal(): int
    {
        return $this->getZoology()->getBonusToAttackNumberAgainstFreeWillAnimal();
    }

    /**
     * @return int
     */
    public function getBonusToCoverAgainstFreeWillAnimal(): int
    {
        return $this->getZoology()->getBonusToCoverAgainstFreeWillAnimal();
    }

    /**
     * @return int
     */
    public function getBonusToBaseOfWoundsAgainstFreeWillAnimal(): int
    {
        return $this->getZoology()->getBonusToBaseOfWoundsAgainstFreeWillAnimal();
    }
}