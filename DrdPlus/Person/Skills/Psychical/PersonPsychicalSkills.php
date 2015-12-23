<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Codes\SkillCodes;
use DrdPlus\Person\Skills\PersonSameTypeSkills;

/**
 * PsychicalSkills
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class PersonPsychicalSkills extends PersonSameTypeSkills
{

    const PSYCHICAL = SkillCodes::PSYCHICAL;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @var Astronomy|null
     * @ORM\OneToOne(targetEntity="Astronomy")
     */
    private $astronomy;
    /** @var Botany|null
     * @ORM\OneToOne(targetEntity="Botany")
     */
    private $botany;
    /** @var EtiquetteOfUnderworld|null
     * @ORM\OneToOne(targetEntity="EtiquetteOfUnderworld")
     */
    private $etiquetteOfUnderworld;
    /** @var ForeignLanguage|null
     * @ORM\OneToOne(targetEntity="ForeignLanguage")
     */
    private $foreignLanguage;
    /** @var GeographyOfACountry|null
     * @ORM\OneToOne(targetEntity="GeographyOfACountry")
     */
    private $geographyOfACountry;
    /** @var HandlingOfMagicalItems|null
     * @ORM\OneToOne(targetEntity="HandlingOfMagicalItems")
     */
    private $handlingWithMagicalItems;
    /** @var Historiography|null
     * @ORM\OneToOne(targetEntity="Historiography")
     */
    private $historiography;
    /** @var KnowledgeOfACity|null
     * @ORM\OneToOne(targetEntity="KnowledgeOfACity")
     */
    private $knowledgeOfACity;
    /** @var KnowledgeOfWorld|null
     * @ORM\OneToOne(targetEntity="KnowledgeOfWorld")
     */
    private $knowledgeOfWorld;
    /** @var MapsDrawing|null
     * @ORM\OneToOne(targetEntity="MapsDrawing")
     */
    private $mapsDrawing;
    /** @var Mythology|null
     * @ORM\OneToOne(targetEntity="Mythology")
     */
    private $mythology;
    /** @var ReadingAndWriting|null
     * @ORM\OneToOne(targetEntity="ReadingAndWriting")
     */
    private $readingAndWriting;
    /** @var SocialEtiquette|null
     * @ORM\OneToOne(targetEntity="SocialEtiquette")
     */
    private $socialEtiquette;
    /** @var Technology|null
     * @ORM\OneToOne(targetEntity="Technology")
     */
    private $technology;
    /** @var Theology|null
     * @ORM\OneToOne(targetEntity="Theology")
     */
    private $theology;
    /** @var Zoology|null
     * @ORM\OneToOne(targetEntity="Zoology")
     */
    private $zoology;

    protected function createSkillsIterator()
    {
        return new \ArrayIterator(
            array_filter([
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
                $this->getZoology()
            ])
        );
    }

    /**
     * @return string
     */
    public function getSkillsGroupName()
    {
        return self::PSYCHICAL;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Astronomy|null
     */
    public function getAstronomy()
    {
        return $this->astronomy;
    }

    /**
     * @return Botany|null
     */
    public function getBotany()
    {
        return $this->botany;
    }

    /**
     * @return EtiquetteOfUnderworld|null
     */
    public function getEtiquetteOfUnderworld()
    {
        return $this->etiquetteOfUnderworld;
    }

    /**
     * @return ForeignLanguage|null
     */
    public function getForeignLanguage()
    {
        return $this->foreignLanguage;
    }

    /**
     * @return GeographyOfACountry|null
     */
    public function getGeographyOfACountry()
    {
        return $this->geographyOfACountry;
    }

    /**
     * @return HandlingOfMagicalItems|null
     */
    public function getHandlingWithMagicalItems()
    {
        return $this->handlingWithMagicalItems;
    }

    /**
     * @return Historiography|null
     */
    public function getHistoriography()
    {
        return $this->historiography;
    }

    /**
     * @return KnowledgeOfACity|null
     */
    public function getKnowledgeOfACity()
    {
        return $this->knowledgeOfACity;
    }

    /**
     * @return KnowledgeOfWorld|null
     */
    public function getKnowledgeOfWorld()
    {
        return $this->knowledgeOfWorld;
    }

    /**
     * @return MapsDrawing|null
     */
    public function getMapsDrawing()
    {
        return $this->mapsDrawing;
    }

    /**
     * @return Mythology|null
     */
    public function getMythology()
    {
        return $this->mythology;
    }

    /**
     * @return ReadingAndWriting|null
     */
    public function getReadingAndWriting()
    {
        return $this->readingAndWriting;
    }

    /**
     * @return SocialEtiquette|null
     */
    public function getSocialEtiquette()
    {
        return $this->socialEtiquette;
    }

    /**
     * @return Technology|null
     */
    public function getTechnology()
    {
        return $this->technology;
    }

    /**
     * @return Theology|null
     */
    public function getTheology()
    {
        return $this->theology;
    }

    /**
     * @return Zoology|null
     */
    public function getZoology()
    {
        return $this->zoology;
    }

}
