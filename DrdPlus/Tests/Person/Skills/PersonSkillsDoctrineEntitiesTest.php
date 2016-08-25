<?php
namespace DrdPlus\Tests\Person\Skills;

use Doctrineum\Tests\Entity\AbstractDoctrineEntitiesTest;
use DrdPlus\Person\Background\BackgroundParts\BackgroundSkillPoints;
use DrdPlus\Person\Background\BackgroundParts\Heritage;
use DrdPlus\Person\ProfessionLevels\LevelRank;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\ProfessionLevels\ProfessionNextLevel;
use DrdPlus\Person\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Person\Skills\Combined\CombinedSkillRank;
use DrdPlus\Person\Skills\Combined\Cooking;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkill;
use DrdPlus\Person\Skills\Combined\PersonCombinedSkills;
use DrdPlus\Person\Skills\EnumTypes\PersonSkillsEnumsRegistrar;
use DrdPlus\Person\Skills\PersonSkills;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkill;
use DrdPlus\Person\Skills\Physical\PersonPhysicalSkills;
use DrdPlus\Person\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Person\Skills\Physical\PhysicalSkillRank;
use DrdPlus\Person\Skills\Physical\Swimming;
use DrdPlus\Person\Skills\Psychical\Astronomy;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkill;
use DrdPlus\Person\Skills\Psychical\PersonPsychicalSkills;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Person\Skills\Psychical\PsychicalSkillRank;
use DrdPlus\Professions\Fighter;
use DrdPlus\Professions\Priest;
use DrdPlus\Professions\Ranger;
use DrdPlus\Professions\Theurgist;
use DrdPlus\Professions\Thief;
use DrdPlus\Professions\Wizard;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Tables\Tables;
use Granam\Integer\PositiveIntegerObject;

class PersonSkillsDoctrineEntitiesTest extends AbstractDoctrineEntitiesTest
{
    protected function setUp()
    {
        PersonSkillsEnumsRegistrar::registerAll();
        parent::setUp();
    }

    protected function getDirsWithEntities()
    {
        $personSkillsReflection = new \ReflectionClass(PersonSkills::class);
        $professionLevelReflection = new \ReflectionClass(ProfessionLevel::class);

        return [
            dirname($professionLevelReflection->getFileName()),
            dirname($personSkillsReflection->getFileName()),
        ];
    }

    protected function createEntitiesToPersist()
    {
        $tables = new Tables();

        return array_merge(
            [
                self::createPersonSkillsEntity($tables),
                ProfessionFirstLevel::createFirstLevel(Theurgist::getIt()),
                ProfessionNextLevel::createNextLevel(
                    Priest::getIt(),
                    LevelRank::getIt(2),
                    Strength::getIt(0),
                    Agility::getIt(0),
                    Knack::getIt(0),
                    Will::getIt(1),
                    Intelligence::getIt(0),
                    Charisma::getIt(1)
                ),
                ProfessionLevels::createIt(
                    ProfessionFirstLevel::createFirstLevel($profession = Fighter::getIt()),
                    [
                        ProfessionNextLevel::createNextLevel(
                            $profession,
                            LevelRank::getIt(2),
                            Strength::getIt(0),
                            Agility::getIt(1),
                            Knack::getIt(0),
                            Will::getIt(0),
                            Intelligence::getIt(1),
                            Charisma::getIt(0)
                        ),
                        ProfessionNextLevel::createNextLevel(
                            $profession,
                            LevelRank::getIt(3),
                            Strength::getIt(1),
                            Agility::getIt(0),
                            Knack::getIt(0),
                            Will::getIt(0),
                            Intelligence::getIt(0),
                            Charisma::getIt(1)
                        )
                    ]
                )
            ],
            self::createPhysicalSkillEntities($tables, ProfessionFirstLevel::createFirstLevel(Wizard::getIt())),
            self::createPsychicalSkillEntities($tables, ProfessionFirstLevel::createFirstLevel(Thief::getIt())),
            self::createCombinedSkillEntities($tables, ProfessionFirstLevel::createFirstLevel(Ranger::getIt()))
        );
    }

    public static function createPersonSkillsEntity(Tables $tables)
    {
        return PersonSkills::createPersonSkills(
            ProfessionLevels::createIt(
                ProfessionFirstLevel::createFirstLevel($profession = Fighter::getIt()),
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
            $tables,
            new PersonPhysicalSkills(),
            new PersonPsychicalSkills(),
            new PersonCombinedSkills()
        );
    }

    private static function createPhysicalSkillEntities(Tables $tables, ProfessionFirstLevel $firstLevel)
    {
        $physicalSkillClasses = self::getListOfSkillClasses(PersonPhysicalSkill::class);
        $physicalSkillPoint = PhysicalSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $firstLevel,
            BackgroundSkillPoints::getIt(3, Heritage::getIt(5)),
            $tables
        );
        $requiredRankValue = new PositiveIntegerObject(1);
        $personPhysicalSkillList = array_map(
            function ($physicalSkillClass) use ($firstLevel, $physicalSkillPoint, $requiredRankValue) {
                /** @var PersonPhysicalSkill $physicalSkill */
                $physicalSkill = new $physicalSkillClass($firstLevel);
                $physicalSkillRank = new PhysicalSkillRank($physicalSkill, $physicalSkillPoint, $requiredRankValue);
                $physicalSkill->addSkillRank($physicalSkillRank);

                return $physicalSkill;
            },
            $physicalSkillClasses
        );

        $personPhysicalSkills = new PersonPhysicalSkills();
        foreach ($personPhysicalSkillList as $personPhysicalSkill) {
            $personPhysicalSkills->addPhysicalSkill($personPhysicalSkill);
        }
        $physicalSkillPoint = PhysicalSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $firstLevel,
            BackgroundSkillPoints::getIt(3, Heritage::getIt(5)),
            $tables
        );
        $physicalSkillRank = new PhysicalSkillRank(
            new Swimming($firstLevel),
            PhysicalSkillPoint::createFromFirstLevelBackgroundSkillPoints(
                $firstLevel,
                BackgroundSkillPoints::getIt(4, Heritage::getIt(5)),
                $tables
            ),
            new PositiveIntegerObject(1)
        );

        return array_merge(
            $personPhysicalSkillList,
            [
                $personPhysicalSkills,
                $physicalSkillPoint,
                $physicalSkillRank
            ]
        );
    }

    private static function getListOfSkillClasses($personSkillParentClass)
    {
        $personSkillReflection = new \ReflectionClass($personSkillParentClass);
        $skillClasses = [];
        foreach (scandir(dirname($personSkillReflection->getFileName())) as $file) {
            if ($file === '..' || $file === '.') {
                continue;
            }
            $className = $personSkillReflection->getNamespaceName() . '\\' . basename($file, '.php');
            if (!is_a($className, $personSkillParentClass, true)
                || (new \ReflectionClass($className))->isAbstract()
            ) {
                continue;
            }
            $skillClasses[] = $className;
        }

        return $skillClasses;
    }

    private static function createPsychicalSkillEntities(Tables $tables, ProfessionFirstLevel $firstLevel)
    {
        $psychicalSkillClasses = self::getListOfSkillClasses(PersonPsychicalSkill::class);
        $psychicalSkillPoint = PsychicalSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $firstLevel,
            BackgroundSkillPoints::getIt(3, Heritage::getIt(5)),
            $tables
        );
        $requiredRankValue = new PositiveIntegerObject(1);
        $personPsychicalSkillList = array_map(
            function ($psychicalSkillClass) use ($firstLevel, $psychicalSkillPoint, $requiredRankValue) {
                /** @var PersonPsychicalSkill $psychicalSkill */
                $psychicalSkill = new $psychicalSkillClass($firstLevel);
                $psychicalSkillRank = new PsychicalSkillRank($psychicalSkill, $psychicalSkillPoint, $requiredRankValue);
                $psychicalSkill->addSkillRank($psychicalSkillRank);

                return $psychicalSkill;
            },
            $psychicalSkillClasses
        );

        $personPsychicalSkills = new PersonPsychicalSkills();
        foreach ($personPsychicalSkillList as $personPsychicalSkill) {
            $personPsychicalSkills->addPsychicalSkill($personPsychicalSkill);
        }

        $psychicalSkillPoint = PsychicalSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $firstLevel,
            BackgroundSkillPoints::getIt(4, Heritage::getIt(3)),
            $tables
        );

        $psychicalSkillRank = new PsychicalSkillRank(
            new Astronomy($firstLevel),
            PsychicalSkillPoint::createFromFirstLevelBackgroundSkillPoints(
                $firstLevel,
                BackgroundSkillPoints::getIt(4, Heritage::getIt(5)),
                $tables
            ),
            new PositiveIntegerObject(1)
        );

        return array_merge(
            $personPsychicalSkillList,
            [
                $personPsychicalSkills,
                $psychicalSkillPoint,
                $psychicalSkillRank
            ]
        );
    }

    private static function createCombinedSkillEntities(Tables $tables, ProfessionFirstLevel $firstLevel)
    {
        $combinedSkillClasses = self::getListOfSkillClasses(PersonCombinedSkill::class);
        $combinedSkillPoint = CombinedSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $firstLevel,
            BackgroundSkillPoints::getIt(3, Heritage::getIt(5)),
            $tables
        );
        $requiredRankValue = new PositiveIntegerObject(1);

        $personCombinedSkillList = array_map(
            function ($combinedSkillClass) use ($firstLevel, $combinedSkillPoint, $requiredRankValue) {
                /** @var PersonCombinedSkill $combinedSkill */
                $combinedSkill = new $combinedSkillClass($firstLevel);
                $combinedSkillRank = new CombinedSkillRank($combinedSkill, $combinedSkillPoint, $requiredRankValue);
                $combinedSkill->addSkillRank($combinedSkillRank);

                return $combinedSkill;
            },
            $combinedSkillClasses
        );

        $personCombinedSkills = new PersonCombinedSkills();
        foreach ($personCombinedSkillList as $personCombinedSkill) {
            $personCombinedSkills->addCombinedSkill($personCombinedSkill);
        }
        $combinedSkillPoint = CombinedSkillPoint::createFromFirstLevelBackgroundSkillPoints(
            $firstLevel,
            BackgroundSkillPoints::getIt(3, Heritage::getIt(5)),
            $tables
        );
        $combinedSkillRank = new CombinedSkillRank(
            new Cooking($firstLevel),
            CombinedSkillPoint::createFromFirstLevelBackgroundSkillPoints(
                $firstLevel,
                BackgroundSkillPoints::getIt(4, Heritage::getIt(5)),
                $tables
            ),
            new PositiveIntegerObject(1)
        );

        return array_merge(
            $personCombinedSkillList,
            [
                $personCombinedSkills,
                $combinedSkillPoint,
                $combinedSkillRank
            ]
        );
    }

}