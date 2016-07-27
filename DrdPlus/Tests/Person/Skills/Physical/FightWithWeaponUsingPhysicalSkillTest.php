<?php
namespace DrdPlus\Tests\Person\Skills\Physical;

use DrdPlus\Person\Skills\Physical\FightWithWeaponUsingPhysicalSkill;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillsTable;
use Granam\Tests\Tools\TestWithMockery;

class FightWithWeaponUsingPhysicalSkillTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_malus_to_fight_number()
    {
        $sutClasses = $this->getSutClasses();
        foreach ($sutClasses as $sutClass) {
            /** @var FightWithWeaponUsingPhysicalSkill $sut */
            $sut = new $sutClass();
            self::assertSame(
                'bar',
                $sut->getMalusToFightNumber($this->createMissingWeaponSkillsTable('foo', 'bar'))
            );
        }
    }

    /**
     * @return string[]
     */
    protected function getSutClasses()
    {
        $reflection = new \ReflectionClass(FightWithWeaponUsingPhysicalSkill::class);
        $sutClasses = [];
        foreach (new \DirectoryIterator(dirname($reflection->getFileName())) as $file) {
            if ($file->isFile()) {
                $baseName = $file->getBasename('.php');
                $foundClass = $reflection->getNamespaceName() . '\\' . $baseName;
                if (is_subclass_of($foundClass, FightWithWeaponUsingPhysicalSkill::class, true)) {
                    $sutClasses[] = $foundClass;
                }
            }
        }

        return $sutClasses;
    }

    /**
     * @param $expectedSkillRank
     * @param $result
     * @return \Mockery\MockInterface|MissingWeaponSkillsTable
     */
    protected function createMissingWeaponSkillsTable($expectedSkillRank, $result)
    {
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillsTable::class);
        $missingWeaponSkillsTable->shouldReceive('getFightNumberForWeaponSkill')
            ->with($expectedSkillRank)
            ->andReturn($result);

        return $missingWeaponSkillsTable;
    }
}
