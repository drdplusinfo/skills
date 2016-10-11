<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\ProtectiveArmamentCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Person\ProfessionLevels\ProfessionFirstLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Person\ProfessionLevels\ProfessionZeroLevel;
use DrdPlus\Professions\Commoner;
use DrdPlus\Skills\Physical\FightWithShields;
use DrdPlus\Skills\Physical\PhysicalSkillPoint;
use DrdPlus\Skills\Physical\PhysicalSkillRank;
use DrdPlus\Skills\Physical\FightUnarmed;
use DrdPlus\Skills\Physical\FightWithAxes;
use DrdPlus\Skills\Physical\FightWithKnifesAndDaggers;
use DrdPlus\Skills\Physical\FightWithMacesAndClubs;
use DrdPlus\Skills\Physical\FightWithMorningstarsAndMorgensterns;
use DrdPlus\Skills\Physical\FightWithSabersAndBowieKnifes;
use DrdPlus\Skills\Physical\FightWithStaffsAndSpears;
use DrdPlus\Skills\Physical\FightWithSwords;
use DrdPlus\Skills\Physical\FightWithThrowingWeapons;
use DrdPlus\Skills\Physical\FightWithTwoWeapons;
use DrdPlus\Skills\Physical\FightWithVoulgesAndTridents;
use DrdPlus\Skills\Physical\PhysicalSkills;
use DrdPlus\Tables\Armaments\Armourer;
use DrdPlus\Tables\Armaments\Shields\MissingShieldSkillTable;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use DrdPlus\Tests\Skills\SameTypeSkillsTest;
use Granam\Integer\PositiveInteger;

class PhysicalSkillsTest extends SameTypeSkillsTest
{

    /**
     * @test
     */
    public function I_can_get_unused_skill_points_from_first_level()
    {
        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $professionLevels = $this->createProfessionLevels(
            $firstLevelStrength = 123, $firstLevelAgility = 456, $nextLevelStrength = 321, $nextLevelAgility = 654
        );

        self::assertSame(
            $firstLevelStrength + $firstLevelAgility,
            $skills->getUnusedFirstLevelPhysicalSkillPointsValue($professionLevels)
        );

        $professionFirstLevel = $this->createProfessionFirstLevel();
        $skills->getArmorWearing()->increaseSkillRank($this->createSkillPoint($professionFirstLevel)); // 1
        $skills->getArmorWearing()->increaseSkillRank($this->createSkillPoint($professionFirstLevel)); // 2
        $skills->getArmorWearing()->increaseSkillRank($this->createSkillPoint($professionFirstLevel)); // 3
        $skills->getAthletics()->increaseSkillRank($this->createSkillPoint($professionFirstLevel)); // 1
        $skills->getAthletics()->increaseSkillRank($this->createSkillPoint($this->createProfessionNextLevel())); // 2 - from next level
        self::assertSame(
            ($firstLevelStrength + $firstLevelAgility) - (1 + 2 + 3 + 1),
            $skills->getUnusedFirstLevelPhysicalSkillPointsValue($professionLevels),
            'Expected ' . (($firstLevelStrength + $firstLevelAgility) - (1 + 2 + 3 + 1))
        );
        self::assertSame(
            ($nextLevelStrength + $nextLevelAgility) - 2,
            $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels),
            'Expected ' . (($nextLevelStrength + $nextLevelAgility) - 2)
        );
    }

    /**
     * @param int $firstLevelStrengthModifier
     * @param int $firstLevelAgilityModifier
     * @param int $nextLevelsStrengthModifier
     * @param int $nextLevelsAgilityModifier
     * @return \Mockery\MockInterface|ProfessionLevels
     */
    private function createProfessionLevels(
        $firstLevelStrengthModifier,
        $firstLevelAgilityModifier,
        $nextLevelsStrengthModifier,
        $nextLevelsAgilityModifier
    )
    {
        $professionLevels = $this->mockery(ProfessionLevels::class);
        $professionLevels->shouldReceive('getFirstLevelStrengthModifier')
            ->andReturn($firstLevelStrengthModifier);
        $professionLevels->shouldReceive('getFirstLevelAgilityModifier')
            ->andReturn($firstLevelAgilityModifier);
        $professionLevels->shouldReceive('getNextLevelsStrengthModifier')
            ->andReturn($nextLevelsStrengthModifier);
        $professionLevels->shouldReceive('getNextLevelsAgilityModifier')
            ->andReturn($nextLevelsAgilityModifier);

        return $professionLevels;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Exceptions\CanNotUseZeroSkillPointForNonZeroSkillRank
     * @expectedExceptionMessageRegExp ~0~
     */
    public function I_can_not_increase_rank_by_zero_skill_point()
    {
        $skills = new PhysicalSkills($professionZeroLevel = ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $skills->getAthletics()->increaseSkillRank(PhysicalSkillPoint::createZeroSkillPoint($professionZeroLevel));
    }

    /**
     * @test
     */
    public function I_can_get_unused_skill_points_from_next_levels()
    {
        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $professionLevels = $this->createProfessionLevels(
            $firstLevelStrength = 123, $firstLevelAgility = 456, $nextLevelsStrength = 321, $nextLevelsAgility = 654
        );

        self::assertSame($nextLevelsStrength + $nextLevelsAgility, $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels));
        $professionFirstLevel = $this->createProfessionFirstLevel();
        $skills->getBlacksmithing()->increaseSkillRank($this->createSkillPoint($professionFirstLevel)); // 1 - first level
        $professionNextLevel = $this->createProfessionNextLevel();
        $skills->getBlacksmithing()->increaseSkillRank($this->createSkillPoint($professionNextLevel)); // 2 - next level
        self::assertSame($firstLevelStrength + $firstLevelAgility - 1, $skills->getUnusedFirstLevelPhysicalSkillPointsValue($professionLevels));
        self::assertSame($nextLevelsStrength + $nextLevelsAgility - 2, $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels));

        $skills->getBoatDriving()->increaseSkillRank($this->createSkillPoint($professionFirstLevel)); // 1 - first level
        $skills->getBoatDriving()->increaseSkillRank($this->createSkillPoint($professionFirstLevel)); // 2 - first level
        $skills->getBoatDriving()->increaseSkillRank($this->createSkillPoint($professionNextLevel)); // 3 - next level
        $skills->getFlying()->increaseSkillRank($this->createSkillPoint($professionNextLevel)); // 1 - next level
        self::assertSame(
            ($firstLevelStrength + $firstLevelAgility) - (1 + 1 + 2),
            $skills->getUnusedFirstLevelPhysicalSkillPointsValue($professionLevels),
            'Expected ' . (($firstLevelStrength + $firstLevelAgility) - (1 + 1 + 2))
        );
        self::assertSame(
            ($nextLevelsStrength + $nextLevelsAgility) - (2 + 3 + 1),
            $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels),
            'Expected ' . (($nextLevelsStrength + $nextLevelsAgility) - (2 + 3 + 1))
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_fight_with_melee_weapon_skills_at_once()
    {
        $expectedFightWithClasses = [
            FightUnarmed::class,
            FightWithAxes::class,
            FightWithKnifesAndDaggers::class,
            FightWithMacesAndClubs::class,
            FightWithMorningstarsAndMorgensterns::class,
            FightWithSabersAndBowieKnifes::class,
            FightWithVoulgesAndTridents::class,
            FightWithStaffsAndSpears::class,
            FightWithSwords::class,
            FightWithThrowingWeapons::class,
            FightWithTwoWeapons::class,
            FightWithShields::class,
        ];
        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $givenFightWithSkillClasses = [];
        $fightWithSkills = $skills->getFightWithMeleeWeaponSkills();
        foreach ($fightWithSkills as $fightWithSkill) {
            self::assertSame(0, $fightWithSkill->getCurrentSkillRank()->getValue());
            $givenFightWithSkillClasses[] = get_class($fightWithSkill);
        }
        sort($expectedFightWithClasses);
        sort($givenFightWithSkillClasses);
        self::assertSame(
            $expectedFightWithClasses,
            $givenFightWithSkillClasses,
            'missing: ' . implode(',', array_diff($expectedFightWithClasses, $givenFightWithSkillClasses))
            . "\n" . 'exceeding: ' . implode(',', array_diff($givenFightWithSkillClasses, $expectedFightWithClasses))
        );
    }

    /**
     * @test
     * @dataProvider provideWeaponCategories
     * @param string $weaponCategory
     * @param $isMelee
     * @param $isThrowing
     */
    public function I_can_get_malus_for_every_type_of_weapon($weaponCategory, $isMelee, $isThrowing)
    {
        $weaponlikeCode = $this->createWeaponCode($weaponCategory, $isMelee, $isThrowing);

        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        self::assertSame(
            $expectedMalus = 'foo',
            $skills->getMalusToFightNumberWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable('fightNumber', 0 /* expected weapon skill value */, $expectedMalus),
                false // fighting with single weapon only
            )
        );
        $skills->getFightWithTwoWeapons()->increaseSkillRank($this->createSkillPoint($this->createProfessionFirstLevel()));
        self::assertSame(
            ($expectedWeaponSkillMalus = 456) + ($expectedTwoWeaponsSkillMalus = 789),
            $skills->getMalusToFightNumberWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable(
                    'fightNumber',
                    0 /* expected weapon skill value */,
                    $expectedWeaponSkillMalus,
                    $skills->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue(), // expected fight with two weapons skill rank value
                    $expectedTwoWeaponsSkillMalus
                ),
                true // fighting with two weapons
            )
        );

        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        self::assertSame(
            $expectedMalus = 'bar',
            $skills->getMalusToAttackNumberWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable('attackNumber', 0 /* expected weapon skill value */, $expectedMalus),
                false // fighting with single weapon only
            )
        );
        $skills->getFightWithTwoWeapons()->increaseSkillRank($this->createSkillPoint($this->createProfessionFirstLevel()));
        self::assertSame(
            ($expectedWeaponSkillMalus = 567) + ($expectedTwoWeaponsSkillMalus = 891),
            $skills->getMalusToAttackNumberWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable(
                    'attackNumber',
                    0 /* expected weapon skill value */,
                    $expectedWeaponSkillMalus,
                    $skills->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue(), // expected fight with two weapons skill rank value
                    $expectedTwoWeaponsSkillMalus
                ),
                true // fighting with two weapons
            )
        );

        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        self::assertSame(
            $expectedMalus = 'baz',
            $skills->getMalusToCoverWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable('cover', 0 /* expected weapon skill value */, $expectedMalus),
                false // fighting with single weapon only
            )
        );
        $skills->getFightWithTwoWeapons()->increaseSkillRank($this->createSkillPoint($this->createProfessionFirstLevel()));
        self::assertSame(
            ($expectedWeaponSkillMalus = 678) + ($expectedTwoWeaponsSkillMalus = 987),
            $skills->getMalusToCoverWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable(
                    'cover',
                    0 /* expected weapon skill value */,
                    $expectedWeaponSkillMalus,
                    $skills->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue(), // expected fight with two weapons skill rank value
                    $expectedTwoWeaponsSkillMalus
                ),
                true // fighting with two weapons
            )
        );

        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        self::assertSame(
            $expectedMalus = 'qux',
            $skills->getMalusToBaseOfWoundsWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable('baseOfWounds', 0 /* expected weapon skill value */, $expectedMalus),
                false // fighting with single weapon only
            )
        );
        $skills->getFightWithTwoWeapons()->increaseSkillRank($this->createSkillPoint($this->createProfessionFirstLevel()));
        self::assertSame(
            ($expectedWeaponSkillMalus = 789) + ($expectedTwoWeaponsSkillMalus = 2223),
            $skills->getMalusToBaseOfWoundsWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable(
                    'baseOfWounds',
                    0 /* expected weapon skill value */,
                    $expectedWeaponSkillMalus,
                    $skills->getFightWithTwoWeapons()->getCurrentSkillRank()->getValue(), // expected fight with two weapons skill rank value
                    $expectedTwoWeaponsSkillMalus
                ),
                true // fighting with two weapons
            )
        );
    }

    /**
     * @return array|string[][]
     */
    public function provideWeaponCategories()
    {
        return array_merge(
            array_map(
                function ($code) {
                    return [$code, true /* is melee */, false /* is not throwing */];
                },
                WeaponCategoryCode::getMeleeWeaponCategoryCodes()
            ),
            [['foo', false /* not melee */, true /* is throwing */]]
        );
    }

    /**
     * @param string $weaponCategory
     * @param bool $isMelee
     * @param bool $isThrowing
     * @return \Mockery\MockInterface|WeaponlikeCode
     */
    private function createWeaponCode($weaponCategory, $isMelee, $isThrowing)
    {
        $weaponlikeCode = $this->mockery(WeaponlikeCode::class);
        $weaponlikeCode->shouldReceive('isMelee')
            ->andReturn($isMelee);
        if ($isMelee) {
            $weaponlikeCode->shouldReceive('convertToMeleeWeaponCodeEquivalent')
                ->andReturn($weaponlikeCode);
        }
        $weaponlikeCode->shouldReceive('isThrowingWeapon')
            ->andReturn($isThrowing);
        $weaponlikeCode->shouldReceive('is' . implode(array_map('ucfirst', explode('_', $weaponCategory))))
            ->andReturn('true');
        $weaponlikeCode->shouldIgnoreMissing(false /* return value for non-mocked methods */);
        $weaponlikeCode->shouldReceive('__toString')
            ->andReturn((string)$weaponCategory);

        return $weaponlikeCode;
    }

    /**
     * @param string $weaponParameterName
     * @param $expectedSkillValue
     * @param $weaponSkillMalus
     * @param int|null $fightsWithTwoWeaponsSkillRankValue
     * @param $fightWithTwoWeaponsSkillMalus
     * @return \Mockery\MockInterface|MissingWeaponSkillTable
     */
    private function createMissingWeaponSkillsTable(
        $weaponParameterName,
        $expectedSkillValue,
        $weaponSkillMalus,
        $fightsWithTwoWeaponsSkillRankValue = null,
        $fightWithTwoWeaponsSkillMalus = 123
    )
    {
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $missingWeaponSkillsTable->shouldReceive('get' . ucfirst($weaponParameterName) . 'MalusForSkill')
            ->with($expectedSkillValue)
            ->andReturn($weaponSkillMalus);
        if ($fightsWithTwoWeaponsSkillRankValue) {
            $missingWeaponSkillsTable->shouldReceive('get' . ucfirst($weaponParameterName) . 'MalusForSkill')
                ->with($fightsWithTwoWeaponsSkillRankValue)
                ->atLeast()->once()
                ->andReturn($fightWithTwoWeaponsSkillMalus);
        }

        return $missingWeaponSkillsTable;
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_cover_with_shield()
    {
        /*$missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(\Mockery::type(PhysicalSkillRank::class))
            ->andReturnUsing(function (PhysicalSkillRank $physicalSkillRank) {
                self::assertSame(0, $physicalSkillRank->getValue());

                return 'foo';
            });*/
        $physicalSkills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $missingShieldSkillsTable = $this->createMissingShieldSkillsTable();
        $missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(\Mockery::type(PhysicalSkillRank::class))
            ->andReturnUsing(function (PhysicalSkillRank $physicalSkillRank) {
                self::assertSame(0, $physicalSkillRank->getValue());

                return 'foo';
            });
        self::assertSame(
            'foo',
            $physicalSkills->getMalusToCoverWithShield($missingShieldSkillsTable)
        );

        $physicalSkills->getShieldUsage()->increaseSkillRank($this->createSkillPoint($this->createProfessionFirstLevel()));
        $missingShieldSkillsTable = $this->createMissingShieldSkillsTable();
        $missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(\Mockery::type(PhysicalSkillRank::class))
            ->andReturnUsing(function (PhysicalSkillRank $physicalSkillRank) {
                self::assertSame(1, $physicalSkillRank->getValue());

                return 'bar';
            });
        self::assertSame('bar', $physicalSkills->getMalusToCoverWithShield($missingShieldSkillsTable));

        $physicalSkills->getShieldUsage()->increaseSkillRank($this->createSkillPoint($this->createProfessionFirstLevel()));
        $missingShieldSkillsTable = $this->createMissingShieldSkillsTable();
        $missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(\Mockery::type(PhysicalSkillRank::class))
            ->andReturnUsing(function (PhysicalSkillRank $physicalSkillRank) {
                self::assertSame(2, $physicalSkillRank->getValue());

                return 'baz';
            });
        self::assertSame('baz', $physicalSkills->getMalusToCoverWithShield($missingShieldSkillsTable));

        $physicalSkills->getShieldUsage()->increaseSkillRank($this->createSkillPoint($this->createProfessionFirstLevel()));
        $missingShieldSkillsTable = $this->createMissingShieldSkillsTable();
        $missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(\Mockery::type(PhysicalSkillRank::class))
            ->andReturnUsing(function (PhysicalSkillRank $physicalSkillRank) {
                self::assertSame(3, $physicalSkillRank->getValue());

                return 'qux';
            });
        self::assertSame('qux', $physicalSkills->getMalusToCoverWithShield($missingShieldSkillsTable));
    }

    /**
     * @return \Mockery\MockInterface|MissingShieldSkillTable
     */
    private function createMissingShieldSkillsTable()
    {
        return $this->mockery(MissingShieldSkillTable::class);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     * @expectedExceptionMessageRegExp ~plank~
     */
    public function I_can_not_get_malus_for_melee_weapon_of_unknown_category()
    {
        $physicalSkills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        /** @var MissingWeaponSkillTable $missingWeaponSkillsTable */
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $physicalSkills->getMalusToFightNumberWithWeaponlike(
            $this->createWeaponCode('plank', true /* is melee */, false /* not throwing */),
            $missingWeaponSkillsTable,
            false // fighting with single weapon only
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatWeapon
     * @expectedExceptionMessageRegExp ~artillery~
     */
    public function I_can_not_get_malus_for_non_melee_non_throwing_weapon()
    {
        $physicalSkills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        /** @var MissingWeaponSkillTable $missingWeaponSkillsTable */
        $missingWeaponSkillsTable = $this->mockery(MissingWeaponSkillTable::class);
        $physicalSkills->getMalusToFightNumberWithWeaponlike(
            $this->createWeaponCode('artillery', false /* not melee */, false /* not throwing */),
            $missingWeaponSkillsTable,
            false // fighting with single weapon only
        );
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_fight_for_armor()
    {
        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $armourer = $this->mockery(Armourer::class);

        $bodyArmor = $this->mockery(BodyArmorCode::class);
        $armourer->shouldReceive('getProtectiveArmamentRestrictionForSkill')
            ->andReturnUsing(function (BodyArmorCode $givenBodyArmorCode, PositiveInteger $rank) use ($bodyArmor) {
                self::assertSame($givenBodyArmorCode, $bodyArmor);
                self::assertSame(0, $rank->getValue());

                return 'foo';
            });
        self::assertSame(
            $expectedMalus = 'foo',
            $skills->getMalusToFightNumberWithProtective($bodyArmor, $armourer)
        );
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_fight_for_helm()
    {
        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $armourer = $this->mockery(Armourer::class);

        $helm = $this->mockery(HelmCode::class);
        $armourer->shouldReceive('getProtectiveArmamentRestrictionForSkill')
            ->andReturnUsing(function (HelmCode $givenHelm, PositiveInteger $rank) use ($helm) {
                self::assertSame($givenHelm, $helm);
                self::assertSame(0, $rank->getValue());

                return 'bar';
            });
        self::assertSame(
            $expectedMalus = 'bar',
            $skills->getMalusToFightNumberWithProtective($helm, $armourer)
        );
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_fight_for_shield()
    {
        $skills = new PhysicalSkills(ProfessionZeroLevel::createZeroLevel(Commoner::getIt()));
        $armourer = $this->mockery(Armourer::class);

        $shield = $this->mockery(ShieldCode::class);
        $armourer->shouldReceive('getProtectiveArmamentRestrictionForSkill')
            ->andReturnUsing(function (ShieldCode $givenShield, PositiveInteger $rank) use ($shield) {
                self::assertSame($givenShield, $shield);
                self::assertSame(0, $rank->getValue());

                return 'foo';
            });
        self::assertSame(
            $expectedMalus = 'foo',
            $skills->getMalusToFightNumberWithProtective($shield, $armourer)
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillsDoNotKnowHowToUseThatArmament
     */
    public function I_do_not_get_malus_to_fight_for_unknown_protective()
    {
        (new PhysicalSkills(ProfessionFirstLevel::createFirstLevel(Commoner::getIt())))->getMalusToFightNumberWithProtective(
            $this->mockery(ProtectiveArmamentCode::class),
            $this->mockery(Armourer::class)
        );
    }

}