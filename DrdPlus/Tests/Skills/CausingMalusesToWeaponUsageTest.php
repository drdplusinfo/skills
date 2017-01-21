<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Skills\SkillRank;
use DrdPlus\Tables\Armaments\Weapons\WeaponSkillTable;
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
                'bar',
                $sut->getMalusToFightNumber($this->createTablesWithWeaponSkillsTable('bar'))
            );
        }
    }

    /**
     * @return string[]
     */
    protected static function getSutClasses()
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
     * @param $result
     * @return \Mockery\MockInterface|Tables
     */
    private function createTablesWithWeaponSkillsTable($result)
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getWeaponSkillTable')
            ->andReturn($weaponSkillTable = $this->mockery(WeaponSkillTable::class));
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
                'bar',
                $sut->getMalusToAttackNumber($this->createTablesWithWeaponSkillsTable('bar'))
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
                'bar',
                $sut->getMalusToCover($this->createTablesWithWeaponSkillsTable('bar'))
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
                'bar',
                $sut->getMalusToBaseOfWounds($this->createTablesWithWeaponSkillsTable('bar'))
            );
        }
    }
}