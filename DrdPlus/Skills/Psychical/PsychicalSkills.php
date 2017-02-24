<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\SameTypeSkills;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PsychicalSkills extends SameTypeSkills
{

    const PSYCHICAL = SkillTypeCode::PSYCHICAL;

    /**
     * @var Astronomy
     * @ORM\OneToOne(targetEntity="Astronomy", cascade={"persist"}, orphanRemoval=true)
     */
    private $astronomy;
    /**
     * @var Botany
     * @ORM\OneToOne(targetEntity="Botany", cascade={"persist"}, orphanRemoval=true)
     */
    private $botany;
    /**
     * @var EtiquetteOfUnderworld
     * @ORM\OneToOne(targetEntity="EtiquetteOfUnderworld", cascade={"persist"}, orphanRemoval=true)
     */
    private $etiquetteOfUnderworld;
    /**
     * @var ForeignLanguage
     * @ORM\OneToOne(targetEntity="ForeignLanguage", cascade={"persist"}, orphanRemoval=true)
     */
    private $foreignLanguage;
    /**
     * @var GeographyOfACountry
     * @ORM\OneToOne(targetEntity="GeographyOfACountry", cascade={"persist"}, orphanRemoval=true)
     */
    private $geographyOfACountry;
    /**
     * @var HandlingWithMagicalItems
     * @ORM\OneToOne(targetEntity="HandlingWithMagicalItems", cascade={"persist"}, orphanRemoval=true)
     */
    private $handlingWithMagicalItems;
    /**
     * @var Historiography
     * @ORM\OneToOne(targetEntity="Historiography", cascade={"persist"}, orphanRemoval=true)
     */
    private $historiography;
    /**
     * @var KnowledgeOfACity
     * @ORM\OneToOne(targetEntity="KnowledgeOfACity", cascade={"persist"}, orphanRemoval=true)
     */
    private $knowledgeOfACity;
    /**
     * @var KnowledgeOfWorld
     * @ORM\OneToOne(targetEntity="KnowledgeOfWorld", cascade={"persist"}, orphanRemoval=true)
     */
    private $knowledgeOfWorld;
    /**
     * @var MapsDrawing
     * @ORM\OneToOne(targetEntity="MapsDrawing", cascade={"persist"}, orphanRemoval=true)
     */
    private $mapsDrawing;
    /**
     * @var Mythology
     * @ORM\OneToOne(targetEntity="Mythology", cascade={"persist"}, orphanRemoval=true)
     */
    private $mythology;
    /**
     * @var ReadingAndWriting
     * @ORM\OneToOne(targetEntity="ReadingAndWriting", cascade={"persist"}, orphanRemoval=true)
     */
    private $readingAndWriting;
    /**
     * @var SocialEtiquette
     * @ORM\OneToOne(targetEntity="SocialEtiquette", cascade={"persist"}, orphanRemoval=true)
     */
    private $socialEtiquette;
    /**
     * @var Technology
     * @ORM\OneToOne(targetEntity="Technology", cascade={"persist"}, orphanRemoval=true)
     */
    private $technology;
    /**
     * @var Theology
     * @ORM\OneToOne(targetEntity="Theology", cascade={"persist"}, orphanRemoval=true)
     */
    private $theology;
    /**
     * @var Zoology
     * @ORM\OneToOne(targetEntity="Zoology", cascade={"persist"}, orphanRemoval=true)
     */
    private $zoology;

    /**
     * @param ProfessionLevel $professionLevel
     */
    protected function populateAllSkills(ProfessionLevel $professionLevel)
    {
        $this->astronomy = new Astronomy($professionLevel);
        $this->botany = new Botany($professionLevel);
        $this->etiquetteOfUnderworld = new EtiquetteOfUnderworld($professionLevel);
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
    public function getUnusedFirstLevelPsychicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        return $this->getUnusedFirstLevelSkillPointsValue($this->getFirstLevelPhysicalPropertiesSum($professionLevels));
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    private function getFirstLevelPhysicalPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getFirstLevelWillModifier() + $professionLevels->getFirstLevelIntelligenceModifier();
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getUnusedNextLevelsPsychicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        return $this->getUnusedNextLevelsSkillPointsValue($this->getNextLevelsPsychicalPropertiesSum($professionLevels));
    }

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    private function getNextLevelsPsychicalPropertiesSum(ProfessionLevels $professionLevels)
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
            $this->getEtiquetteOfUnderworld(),
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
     * @return EtiquetteOfUnderworld
     */
    public function getEtiquetteOfUnderworld()
    {
        return $this->etiquetteOfUnderworld;
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
    public function getGeographyOfACountry()
    {
        return $this->geographyOfACountry;
    }

    /**
     * @return HandlingWithMagicalItems
     */
    public function getHandlingWithMagicalItems()
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
    public function getKnowledgeOfACity()
    {
        return $this->knowledgeOfACity;
    }

    /**
     * @return KnowledgeOfWorld
     */
    public function getKnowledgeOfWorld()
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
    public function getReadingAndWriting()
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

}