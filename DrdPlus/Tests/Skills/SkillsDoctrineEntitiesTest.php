<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Skills;

use Doctrineum\Tests\Entity\AbstractDoctrineEntitiesTest;
use DrdPlus\Background\BackgroundParts\Ancestry;
use DrdPlus\Background\BackgroundParts\SkillPointsFromBackground;
use DrdPlus\Person\ProfessionLevels\LevelRank;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\ProfessionLevels\ProfessionNextLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionZeroLevel;
use DrdPlus\Professions\Commoner;
use DrdPlus\Skills\Combined\CombinedSkillPoint;
use DrdPlus\Skills\Combined\CombinedSkillRank;
use DrdPlus\Skills\Combined\Cooking;
use DrdPlus\Skills\Combined\CombinedSkill;
use DrdPlus\Skills\Combined\CombinedSkills;
use DrdPlus\Skills\EnumTypes\SkillsEnumsRegistrar;
use DrdPlus\Skills\Psychical\Technology;
use DrdPlus\Skills\Skills;
use DrdPlus\Skills\Physical\PhysicalSkill;
use DrdPlus\Skills\Physical\PhysicalSkills;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\Physical\PhysicalSkillRank;
use DrdPlus\Skills\Physical\Swimming;
use DrdPlus\Skills\Psychical\PsychicalSkill;
use DrdPlus\Skills\Psychical\PsychicalSkills;
use DrdPlus\Skills\Psychical\PsychicalSkillPoint;
use DrdPlus\Skills\Psychical\PsychicalSkillRank;
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

class SkillsDoctrineEntitiesTest extends AbstractDoctrineEntitiesTest
{
    protected function setUp()
    {
        SkillsEnumsRegistrar::registerAll();
        parent::setUp();
    }

    protected function getDirsWithEntities(): array
    {
        $skillsReflection = new \ReflectionClass(Skills::class);
        $professionLevelReflection = new \ReflectionClass(ProfessionLevel::class);

        return [
            dirname($professionLevelReflection->getFileName()),
            dirname($skillsReflection->getFileName()),
        ];
    }

    protected function createEntitiesToPersist(): array
    {
        $tables = Tables::getIt();

        return array_merge(
            [
                self::createSkillsEntity($tables),
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
                    ProfessionZeroLevel::createZeroLevel(Commoner::getIt()),
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
                        ),
                    ]
                ),
                ProfessionZeroLevel::createZeroLevel(Commoner::getIt()),
            ],
            self::createPhysicalSkillEntities($tables, ProfessionFirstLevel::createFirstLevel(Wizard::getIt())),
            self::createPsychicalSkillEntities($tables, ProfessionFirstLevel::createFirstLevel(Thief::getIt())),
            self::createCombinedSkillEntities($tables, ProfessionFirstLevel::createFirstLevel(Ranger::getIt()))
        );
    }

    public static function createSkillsEntity(Tables $tables): Skills
    {
        return Skills::createSkills(
            ProfessionLevels::createIt(
                ProfessionZeroLevel::createZeroLevel(Commoner::getIt()),
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
            SkillPointsFromBackground::getIt(
                new PositiveIntegerObject(2),
                Ancestry::getIt(new PositiveIntegerObject(7), $tables),
                $tables
            ),
            new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt())),
            new PsychicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt())),
            new CombinedSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt())),
            $tables
        );
    }

    private static function createPhysicalSkillEntities(Tables $tables, ProfessionFirstLevel $firstLevel): array
    {
        $physicalSkillClasses = self::getListOfSkillClasses(PhysicalSkill::class);
        $physicalSkillPoint = PhysicalSkillPoint::createFromFirstLevelSkillPointsFromBackground(
            $firstLevel,
            SkillPointsFromBackground::getIt(
                new PositiveIntegerObject(3),
                Ancestry::getIt(new PositiveIntegerObject(5), $tables),
                $tables
            ),
            $tables
        );
        $physicalSkills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $physicalSkillList = [];
        foreach ($physicalSkillClasses as $physicalSkillClass) {
            $skillGetter = self::createSkillGetter($physicalSkillClass);
            $physicalSkillList[] = $physicalSkill = $physicalSkills->$skillGetter();
            /** @var PhysicalSkill $physicalSkill */
            $physicalSkill->increaseSkillRank($physicalSkillPoint);
        }
        $physicalSkillPoint = PhysicalSkillPoint::createFromFirstLevelSkillPointsFromBackground(
            $firstLevel,
            SkillPointsFromBackground::getIt(
                new PositiveIntegerObject(3),
                Ancestry::getIt(new PositiveIntegerObject(5), $tables),
                $tables
            ),
            $tables
        );
        $physicalSkillRank = new PhysicalSkillRank(
            new Swimming($firstLevel),
            PhysicalSkillPoint::createFromFirstLevelSkillPointsFromBackground(
                $firstLevel,
                SkillPointsFromBackground::getIt(
                    new PositiveIntegerObject(4),
                    Ancestry::getIt(new PositiveIntegerObject(5), $tables),
                    $tables
                ),
                $tables
            ),
            new PositiveIntegerObject(1)
        );

        return array_merge(
            $physicalSkillList,
            [
                $physicalSkills,
                $physicalSkillPoint,
                $physicalSkillRank,
            ]
        );
    }

    /**
     * @param string $skillClass
     * @return string
     */
    private static function createSkillGetter($skillClass): string
    {
        $baseName = preg_replace('~^.+[\\\]([^\\\]+)$~', '$1', $skillClass);

        return 'get' . $baseName;
    }

    private static function getListOfSkillClasses($skillParentClass): array
    {
        $skillReflection = new \ReflectionClass($skillParentClass);
        $skillClasses = [];
        foreach (scandir(dirname($skillReflection->getFileName()), SCANDIR_SORT_NONE) as $file) {
            if ($file === '..' || $file === '.') {
                continue;
            }
            $className = $skillReflection->getNamespaceName() . '\\' . basename($file, '.php');
            if (!is_a($className, $skillParentClass, true)
                || (new \ReflectionClass($className))->isAbstract()
            ) {
                continue;
            }
            $skillClasses[] = $className;
        }

        return $skillClasses;
    }

    private static function createPsychicalSkillEntities(Tables $tables, ProfessionFirstLevel $firstLevel): array
    {
        $psychicalSkillClasses = self::getListOfSkillClasses(PsychicalSkill::class);
        $psychicalSkillPoint = PsychicalSkillPoint::createFromFirstLevelSkillPointsFromBackground(
            $firstLevel,
            SkillPointsFromBackground::getIt(
                new PositiveIntegerObject(3),
                Ancestry::getIt(new PositiveIntegerObject(5), $tables),
                $tables
            ),
            $tables
        );
        $psychicalSkills = new PsychicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $psychicalSkillList = [];
        foreach ($psychicalSkillClasses as $psychicalSkillClass) {
            $skillGetter = self::createSkillGetter($psychicalSkillClass);
            /** @var PsychicalSkill $psychicalSkill */
            $psychicalSkillList[] = $psychicalSkill = $psychicalSkills->$skillGetter();
            $psychicalSkill->increaseSkillRank($psychicalSkillPoint);
        }

        $psychicalSkillPoint = PsychicalSkillPoint::createFromFirstLevelSkillPointsFromBackground(
            $firstLevel,
            SkillPointsFromBackground::getIt(
                new PositiveIntegerObject(4),
                Ancestry::getIt(new PositiveIntegerObject(3), $tables),
                $tables
            ),
            $tables
        );

        $psychicalSkillRank = new PsychicalSkillRank(
            new Technology($firstLevel),
            PsychicalSkillPoint::createFromFirstLevelSkillPointsFromBackground(
                $firstLevel,
                SkillPointsFromBackground::getIt(
                    new PositiveIntegerObject(4),
                    Ancestry::getIt(new PositiveIntegerObject(5), $tables),
                    $tables
                ),
                $tables
            ),
            new PositiveIntegerObject(1)
        );

        return array_merge(
            $psychicalSkillList,
            [
                $psychicalSkills,
                $psychicalSkillPoint,
                $psychicalSkillRank,
            ]
        );
    }

    private static function createCombinedSkillEntities(Tables $tables, ProfessionFirstLevel $firstLevel): array
    {
        $combinedSkillClasses = self::getListOfSkillClasses(CombinedSkill::class);
        $combinedSkillPoint = CombinedSkillPoint::createFromFirstLevelSkillPointsFromBackground(
            $firstLevel,
            SkillPointsFromBackground::getIt(
                new PositiveIntegerObject(3),
                Ancestry::getIt(new PositiveIntegerObject(5), $tables),
                $tables
            ),
            $tables
        );
        $combinedSkills = new CombinedSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $combinedSkillList = [];
        foreach ($combinedSkillClasses as $combinedSkillClass) {
            $skillGetter = self::createSkillGetter($combinedSkillClass);
            /** @var CombinedSkill $combinedSkill */
            $combinedSkillList[] = $combinedSkill = $combinedSkills->$skillGetter();
            $combinedSkill->increaseSkillRank($combinedSkillPoint);
        }
        $combinedSkillPoint = CombinedSkillPoint::createFromFirstLevelSkillPointsFromBackground(
            $firstLevel,
            SkillPointsFromBackground::getIt(
                new PositiveIntegerObject(3),
                Ancestry::getIt(new PositiveIntegerObject(5), $tables),
                $tables
            ),
            $tables
        );
        $combinedSkillRank = new CombinedSkillRank(
            new Cooking($firstLevel),
            CombinedSkillPoint::createFromFirstLevelSkillPointsFromBackground(
                $firstLevel,
                SkillPointsFromBackground::getIt(
                    new PositiveIntegerObject(4),
                    Ancestry::getIt(new PositiveIntegerObject(5), $tables),
                    $tables
                ),
                $tables
            ),
            new PositiveIntegerObject(1)
        );

        return array_merge(
            $combinedSkillList,
            [
                $combinedSkills,
                $combinedSkillPoint,
                $combinedSkillRank,
            ]
        );
    }

}