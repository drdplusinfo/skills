<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Skills\SkillRank;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use DrdPlus\Tables\Tables;
use Granam\Tests\Tools\TestWithMockery;

abstract class CausingMalusesToWeaponUsageTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_malus_to_fight_number()
    {
        $sutClasses = self::getSutClasses();
        foreach ($sutClasses as $sutClass) {
            /** @var CausingMalusesToWeaponUsage $sut */
            $sut = new $sutClass($this->createProfessionFirstLevel());
            self::assertSame(
                136,
                $sut->getMalusToFightNumber($this->createTablesWithWeaponSkillsTable(136))
            );
        }
    }

    /**
     * @return string[]
     */
    protected static function getSutClasses(): array
    {
        $reflection = new \ReflectionClass(self::getSutClass());
        $sutClasses = [];
        foreach (new \DirectoryIterator(dirname($reflection->getFileName())) as $file) {
            if ($file->isFile()) {
                $baseName = $file->getBasename('.php');
                $foundClass = $reflection->getNamespaceName() . '\\' . $baseName;
                if (is_subclass_of($foundClass, self::getSutClass(), true)) {
                    $sutClasses[] = $foundClass;
                }
            }
        }

        return $sutClasses;
    }

    /**
     * @param int $result
     * @return \Mockery\MockInterface|Tables
     */
    private function createTablesWithWeaponSkillsTable(int $result)
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getMissingWeaponSkillTable')
            ->andReturn($weaponSkillTable = $this->mockery(MissingWeaponSkillTable::class));
        $returnFunction = function (SkillRank $skillRank) use ($result) {
            self::assertSame(0, $skillRank->getValue());

            return $result;
        };
        $weaponSkillTable->shouldReceive('getFightNumberMalusForSkillRank')
            ->with($this->type(SkillRank::class))
            ->andReturnUsing($returnFunction);
        $weaponSkillTable->shouldReceive('getAttackNumberMalusForSkillRank')
            ->with($this->type(SkillRank::class))
            ->andReturnUsing($returnFunction);
        $weaponSkillTable->shouldReceive('getCoverMalusForSkillRank')
            ->with($this->type(SkillRank::class))
            ->andReturnUsing($returnFunction);
        $weaponSkillTable->shouldReceive('getBaseOfWoundsMalusForSkillRank')
            ->with($this->type(SkillRank::class))
            ->andReturnUsing($returnFunction);

        return $tables;
    }

    /**
     * @return \Mockery\MockInterface|ProfessionFirstLevel
     */
    protected function createProfessionFirstLevel()
    {
        return $this->mockery(ProfessionFirstLevel::class);
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_attack_number()
    {
        $sutClasses = self::getSutClasses();
        foreach ($sutClasses as $sutClass) {
            /** @var CausingMalusesToWeaponUsage $sut */
            $sut = new $sutClass($this->createProfessionFirstLevel());
            self::assertSame(
                123,
                $sut->getMalusToAttackNumber($this->createTablesWithWeaponSkillsTable(123))
            );
        }
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_cover()
    {
        $sutClasses = self::getSutClasses();
        foreach ($sutClasses as $sutClass) {
            /** @var CausingMalusesToWeaponUsage $sut */
            $sut = new $sutClass($this->createProfessionFirstLevel());
            self::assertSame(
                456,
                $sut->getMalusToCover($this->createTablesWithWeaponSkillsTable(456))
            );
        }
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_base_of_wounds()
    {
        $sutClasses = self::getSutClasses();
        foreach ($sutClasses as $sutClass) {
            /** @var CausingMalusesToWeaponUsage $sut */
            $sut = new $sutClass($this->createProfessionFirstLevel());
            self::assertSame(
                789,
                $sut->getMalusToBaseOfWounds($this->createTablesWithWeaponSkillsTable(789))
            );
        }
    }
}