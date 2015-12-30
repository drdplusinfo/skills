<?php
namespace DrdPlus\Person\Skills\Psychical;

use DrdPlus\Codes\SkillCodes;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
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
    /** @var HandlingWithMagicalItems|null
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

    /**
     * @param ProfessionLevels $professionLevels
     * @return int
     */
    public function getUnusedFirstLevelPsychicalSkillPointsValue(ProfessionLevels $professionLevels)
    {
        return $this->getUnusedFirstLevelSkillPointsValue($this->getFirstLevelPhysicalPropertiesSum($professionLevels));
    }

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

    private function getNextLevelsPsychicalPropertiesSum(ProfessionLevels $professionLevels)
    {
        return $professionLevels->getNextLevelsWillModifier() + $professionLevels->getNextLevelsIntelligenceModifier();
    }

    public function getIterator()
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

    public function addPsychicalSkill(PersonPsychicalSkill $psychicalSkill)
    {
        switch (true) {
            case is_a($psychicalSkill, Astronomy::class) :
                if (isset($this->astronomy)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('astronomy  is already set');
                }
                $this->astronomy = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, Botany::class) :
                if (isset($this->botany)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('botany  is already set');
                }
                $this->botany = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, EtiquetteOfUnderworld::class) :
                if (isset($this->etiquetteOfUnderworld)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('etiquetteOfUnderworld  is already set');
                }
                $this->etiquetteOfUnderworld = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, ForeignLanguage::class) :
                if (isset($this->foreignLanguage)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('foreignLanguage  is already set');
                }
                $this->foreignLanguage = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, GeographyOfACountry::class) :
                if (isset($this->geographyOfACountry)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('geographyOfACountry  is already set');
                }
                $this->geographyOfACountry = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, HandlingWithMagicalItems::class) :
                if (isset($this->handlingWithMagicalItems)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('handlingWithMagicalItems  is already set');
                }
                $this->handlingWithMagicalItems = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, Historiography::class) :
                if (isset($this->historiography)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('historiography  is already set');
                }
                $this->historiography = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, KnowledgeOfACity::class) :
                if (isset($this->knowledgeOfACity)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('knowledgeOfACity  is already set');
                }
                $this->knowledgeOfACity = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, KnowledgeOfWorld::class) :
                if (isset($this->knowledgeOfWorld)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('knowledgeOfWorld  is already set');
                }
                $this->knowledgeOfWorld = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, MapsDrawing::class) :
                if (isset($this->mapsDrawing)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('mapsDrawing  is already set');
                }
                $this->mapsDrawing = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, Mythology::class) :
                if (isset($this->mythology)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('mythology  is already set');
                }
                $this->mythology = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, ReadingAndWriting::class) :
                if (isset($this->readingAndWriting)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('readingAndWriting  is already set');
                }
                $this->readingAndWriting = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, SocialEtiquette::class) :
                if (isset($this->socialEtiquette)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('socialEtiquette  is already set');
                }
                $this->socialEtiquette = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, Technology::class) :
                if (isset($this->technology)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('technology  is already set');
                }
                $this->technology = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, Theology::class) :
                if (isset($this->theology)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('theology  is already set');
                }
                $this->theology = $psychicalSkill;    
                break;
            case is_a($psychicalSkill, Zoology::class) :
                if (isset($this->zoology)) {
                    throw new Exceptions\PsychicalSkillAlreadySet('zoology  is already set');
                }
                $this->zoology = $psychicalSkill;    
                break;
            default :
                throw new Exceptions\UnknownPsychicalSkill(
                    'Unknown psychical skill ' . get_class($psychicalSkill)
                );
        }
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
     * @return HandlingWithMagicalItems|null
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
