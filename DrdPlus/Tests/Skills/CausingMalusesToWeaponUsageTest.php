<?php
namespace DrdPlus\Tests\Skills\Combined;

use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
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
                $sut->getMalusToFightNumber($this->createMissingWeaponSkillsTable('foo', 'bar'))
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
     * @param $expectedSkillRank
     * @param $result
     * @return \Mockery\MockInterface|MissingWeaponSkillTable
     */
    protected function createMissingWeaponSkillsTable($expectedSkillRank, $result)
    {
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $missingWeaponSkillsTable->shouldReceive('getFightNumberForWeaponSkill')
            ->with($expectedSkillRank)
            ->andReturn($result);
        $missingWeaponSkillsTable->shouldReceive('getAttackNumberForWeaponSkill')
            ->with($expectedSkillRank)
            ->andReturn($result);
        $missingWeaponSkillsTable->shouldReceive('getCoverForWeaponSkill')
            ->with($expectedSkillRank)
            ->andReturn($result);
        $missingWeaponSkillsTable->shouldReceive('getBaseOfWoundsForWeaponSkill')
            ->with($expectedSkillRank)
            ->andReturn($result);

        return $missingWeaponSkillsTable;
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
                $sut->getMalusToAttackNumber($this->createMissingWeaponSkillsTable('foo', 'bar'))
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
                $sut->getMalusToCover($this->createMissingWeaponSkillsTable('foo', 'bar'))
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
                $sut->getMalusToBaseOfWounds($this->createMissingWeaponSkillsTable('foo', 'bar'))
            );
        }
    }
}