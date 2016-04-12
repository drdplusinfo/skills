<?php
namespace DrdPlus\Tests\Person\Skills;

use Doctrineum\Tests\Entity\AbstractDoctrineEntitiesTest;
use DrdPlus\Person\Background\BackgroundParts\BackgroundSkillPoints;
use DrdPlus\Person\Background\BackgroundParts\Heritage;
use DrdPlus\Person\ProfessionLevels\LevelRank;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\ProfessionLevels\ProfessionNextLevel;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\EnumTypes\PersonSkillsEnumsRegistrar;
use DrdPlus\Person\Skills\PersonSkills;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\Professions\Fighter;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Tables\Tables;

class DoctrineEntitiesTest extends AbstractDoctrineEntitiesTest
{
    protected function setUp()
    {
        PersonSkillsEnumsRegistrar::registerAll();
        parent::setUp();
    }

    protected function getDirsWithEntities()
    {
        $personSkillsReflection = new \ReflectionClass(PersonSkills::class);

        return dirname($personSkillsReflection->getFileName());
    }

    protected function getExpectedEntityClasses()
    {
        return [
            PersonSkills::class
        ];
    }

    protected function createEntitiesToPersist()
    {
        return [
            PersonSkills::createPersonSkills(
                ProfessionLevels::createIt(
                    ProfessionFirstLevel::createFirstLevel(
                        $profession = Fighter::getIt()
                    ),
                    [ProfessionNextLevel::createNextLevel(
                        $profession,
                        LevelRank::getIt(2),
                        Strength::getIt(0),
                        Agility::getIt(1),
                        Knack::getIt(0),
                        Will::getIt(0),
                        Intelligence::getIt(1),
                        Charisma::getIt(0)
                    )]
                ),
                BackgroundSkillPoints::getIt(2, Heritage::getIt(7)),
                new Tables(),
                // TODO use every single skill to check its persistability
                new PersonPhysicalSkills(),
                new PersonPsychicalSkills(),
                new PersonCombinedSkills()
            )
        ];
    }

}