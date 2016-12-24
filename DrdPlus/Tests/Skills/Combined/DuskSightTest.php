<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Person\ProfessionLevels\ProfessionZeroLevel;
use DrdPlus\Professions\Commoner;
use Granam\Tests\Tools\TestWithMockery;

class DuskSightTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_bonus_on_insufficient_lighting()
    {
        $duskSight = new DuskSight(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        self::assertSame(0, $duskSight->getInsufficientLightingBonus());
        $duskSight->increaseSkillRank($this->createCombinedSkillPoint());
        self::assertSame(1, $duskSight->getInsufficientLightingBonus());
        $duskSight->increaseSkillRank($this->createCombinedSkillPoint());
        self::assertSame(2, $duskSight->getInsufficientLightingBonus());
        $duskSight->increaseSkillRank($this->createCombinedSkillPoint());
        self::assertSame(3, $duskSight->getInsufficientLightingBonus());
    }

    /**
     * @return \Mockery\MockInterface|CombinedSkillPoint
     */
    private function createCombinedSkillPoint()
    {
        $combinedSkillPoint = $this->mockery(CombinedSkillPoint::class);
        $combinedSkillPoint->shouldReceive('getValue')
            ->andReturn(1);

        return $combinedSkillPoint;
    }

}