<?php
namespace DrdPlus\Tests\Skills\Physical;

use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\ProtectiveArmamentCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use DrdPlus\Person\ProfessionLevels\ProfessionLevels;
use DrdPlus\Skills\Physical\PhysicalSkillRank;
use DrdPlus\Skills\Physical\ShieldUsage;
use DrdPlus\Skills\Skill;
use DrdPlus\Skills\Physical\ArmorWearing;
use DrdPlus\Skills\Physical\Athletics;
use DrdPlus\Skills\Physical\Blacksmithing;
use DrdPlus\Skills\Physical\BoatDriving;
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
use DrdPlus\Skills\Physical\Flying;
use DrdPlus\Skills\Physical\PhysicalSkill;
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
     * @dataProvider provideSkill
     * @param Skill $skill
     * @expectedException \DrdPlus\Skills\Physical\Exceptions\PhysicalSkillAlreadySet
     */
    public function I_can_not_replace_skill(Skill $skill)
    {
        parent::I_can_not_replace_skill($skill);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Skills\Physical\Exceptions\UnknownPhysicalSkill
     */
    public function I_can_not_add_unknown_skill()
    {
        $skills = new PhysicalSkills();
        /** @var PhysicalSkill $strangePhysicalSkill */
        $strangePhysicalSkill = $this->mockery(PhysicalSkill::class);
        $skills->addPhysicalSkill($strangePhysicalSkill);
    }

    /**
     * @test
     */
    public function I_can_get_unused_skill_points_from_first_level()
    {
        $skills = new PhysicalSkills();
        $professionLevels = $this->createProfessionLevels(
            $firstLevelStrength = 123, $firstLevelAgility = 456, $nextLevelStrength = 321, $nextLevelAgility = 654
        );

        self::assertSame(
            $firstLevelStrength + $firstLevelAgility,
            $skills->getUnusedFirstLevelPhysicalSkillPointsValue($professionLevels)
        );

        $skills->addPhysicalSkill($this->createPhysicalSkill($usedRank = 3, 1, ArmorWearing::class));
        $skills->addPhysicalSkill($this->createPhysicalSkill($unusedRank = 2, 2, Athletics::class));
        self::assertSame(
            ($firstLevelStrength + $firstLevelAgility) - array_sum(range(1, $usedRank)),
            $skills->getUnusedFirstLevelPhysicalSkillPointsValue($professionLevels),
            'Expected ' . (($firstLevelStrength + $firstLevelAgility) - array_sum(range(1, $usedRank)))
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
     * @param int $finalSkillRankValue
     * @param int $levelValue
     * @param string $skillClass
     * @return \Mockery\MockInterface|PhysicalSkill
     */
    private function createPhysicalSkill($finalSkillRankValue, $levelValue, $skillClass)
    {
        $professionLevel = $this->mockery(ProfessionLevel::class);
        $professionLevel->shouldReceive('isFirstLevel')
            ->andReturn($levelValue === 1);
        $professionLevel->shouldReceive('isNextLevel')
            ->andReturn($levelValue > 1);
        $physicalSkill = $this->mockery($skillClass);
        /** @var ProfessionLevel $professionLevel */
        $physicalSkill->shouldReceive('getSkillRanks')
            ->andReturn($skillRanks = $this->createPhysicalSkillRanks($finalSkillRankValue, $professionLevel));
        $physicalSkill->shouldReceive('getCurrentSkillRank')
            ->andReturn(end($skillRanks));

        return $physicalSkill;
    }

    private function createPhysicalSkillRanks($finalSkillRankValue, ProfessionLevel $professionLevel)
    {
        $skillRanks = [];
        for ($value = 1; $value <= $finalSkillRankValue; $value++) {
            $skillRank = $this->mockery(PhysicalSkillRank::class);
            $skillRank->shouldReceive('getValue')
                ->andReturn($value);
            $skillRank->shouldReceive('getProfessionLevel')
                ->andReturn($professionLevel);
            $skillRanks[] = $skillRank;
        }

        return $skillRanks;
    }

    /**
     * @test
     */
    public function I_can_get_unused_skill_points_from_next_levels()
    {
        $skills = new PhysicalSkills();
        $professionLevels = $this->createProfessionLevels(
            $firstLevelStrength = 123, $firstLevelAgility = 456, $nextLevelsStrength = 321, $nextLevelsAgility = 654
        );

        self::assertSame($nextLevelsStrength + $nextLevelsAgility, $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels));
        $skills->addPhysicalSkill($this->createPhysicalSkill($rankFromFirstLevel = 2, 1, Blacksmithing::class));
        self::assertSame($nextLevelsStrength + $nextLevelsAgility, $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels));

        $skills->addPhysicalSkill($this->createPhysicalSkill($aRankFromNextLevel = 3, 2, BoatDriving::class));
        $skills->addPhysicalSkill($this->createPhysicalSkill($anotherRankFromNextLevel = 1, 3, Flying::class));
        self::assertSame(
            ($nextLevelsStrength + $nextLevelsAgility) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))),
            $skills->getUnusedNextLevelsPhysicalSkillPointsValue($professionLevels),
            'Expected ' . (($nextLevelsStrength + $nextLevelsAgility) - (array_sum(range(1, $aRankFromNextLevel)) + array_sum(range(1, $anotherRankFromNextLevel))))
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_fight_with_melee_weapon_skills_at_once()
    {
        $skills = new PhysicalSkills();
        $skills->addPhysicalSkill($fightUnarmed = new FightUnarmed($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithAxes = new FightWithAxes($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithKnifesAndDaggers = new FightWithKnifesAndDaggers($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithMacesAndClubs = new FightWithMacesAndClubs($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithMorningStarsAndMorgensterns = new FightWithMorningstarsAndMorgensterns($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithSabersAndBowieKnifes = new FightWithSabersAndBowieKnifes($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithVoulgesAndTridents = new FightWithVoulgesAndTridents($this->createProfessionFirstLevel()));
        self::assertSame(
            [
                $fightUnarmed,
                $fightWithAxes,
                $fightWithKnifesAndDaggers,
                $fightWithMacesAndClubs,
                $fightWithMorningStarsAndMorgensterns,
                $fightWithSabersAndBowieKnifes,
                $fightWithVoulgesAndTridents,
            ],
            $skills->getFightWithMeleeWeaponSkills()
        );

        $skills->addPhysicalSkill($fightWithStaffsAndSpears = new FightWithStaffsAndSpears($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithSwords = new FightWithSwords($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithThrowingWeapons = new FightWithThrowingWeapons($this->createProfessionFirstLevel()));
        $skills->addPhysicalSkill($fightWithTwoWeapons = new FightWithTwoWeapons($this->createProfessionFirstLevel()));
        self::assertSame(
            [
                $fightUnarmed,
                $fightWithAxes,
                $fightWithKnifesAndDaggers,
                $fightWithMacesAndClubs,
                $fightWithMorningStarsAndMorgensterns,
                $fightWithSabersAndBowieKnifes,
                $fightWithStaffsAndSpears,
                $fightWithSwords,
                $fightWithThrowingWeapons,
                $fightWithTwoWeapons,
                $fightWithVoulgesAndTridents,
            ],
            $skills->getFightWithMeleeWeaponSkills()
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

        $skills = new PhysicalSkills();
        self::assertSame(
            $expectedMalus = 'foo',
            $skills->getMalusToFightNumberWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable('fightNumber', 0 /* expected weapon skill value */, $expectedMalus),
                false // fighting with single weapon only
            )
        );
        $skills->addPhysicalSkill($this->createPhysicalSkill($fightWithTwoWeaponsRank = 1, 1, FightWithTwoWeapons::class));
        self::assertSame(
            ($expectedWeaponSkillMalus = 456) + ($expectedTwoWeaponsSkillMalus = 789),
            $skills->getMalusToFightNumberWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable(
                    'fightNumber',
                    0 /* expected weapon skill value */,
                    $expectedWeaponSkillMalus,
                    $fightWithTwoWeaponsRank, // expected fight with two weapons skill rank value
                    $expectedTwoWeaponsSkillMalus
                ),
                true // fighting with two weapons
            )
        );

        $skills = new PhysicalSkills();
        self::assertSame(
            $expectedMalus = 'bar',
            $skills->getMalusToAttackNumberWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable('attackNumber', 0 /* expected weapon skill value */, $expectedMalus),
                false // fighting with single weapon only
            )
        );
        $skills->addPhysicalSkill($this->createPhysicalSkill($fightWithTwoWeaponsRank = 2, 1, FightWithTwoWeapons::class));
        self::assertSame(
            ($expectedWeaponSkillMalus = 567) + ($expectedTwoWeaponsSkillMalus = 891),
            $skills->getMalusToAttackNumberWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable(
                    'attackNumber',
                    0 /* expected weapon skill value */,
                    $expectedWeaponSkillMalus,
                    $fightWithTwoWeaponsRank, // expected fight with two weapons skill rank value
                    $expectedTwoWeaponsSkillMalus
                ),
                true // fighting with two weapons
            )
        );

        $skills = new PhysicalSkills();
        self::assertSame(
            $expectedMalus = 'baz',
            $skills->getMalusToCoverWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable('cover', 0 /* expected weapon skill value */, $expectedMalus),
                false // fighting with single weapon only
            )
        );
        $skills->addPhysicalSkill($this->createPhysicalSkill($fightWithTwoWeaponsRank = 3, 1, FightWithTwoWeapons::class));
        self::assertSame(
            ($expectedWeaponSkillMalus = 678) + ($expectedTwoWeaponsSkillMalus = 987),
            $skills->getMalusToCoverWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable(
                    'cover',
                    0 /* expected weapon skill value */,
                    $expectedWeaponSkillMalus,
                    $fightWithTwoWeaponsRank, // expected fight with two weapons skill rank value
                    $expectedTwoWeaponsSkillMalus
                ),
                true // fighting with two weapons
            )
        );

        $skills = new PhysicalSkills();
        self::assertSame(
            $expectedMalus = 'qux',
            $skills->getMalusToBaseOfWoundsWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable('baseOfWounds', 0 /* expected weapon skill value */, $expectedMalus),
                false // fighting with single weapon only
            )
        );
        $skills->addPhysicalSkill($this->createPhysicalSkill($fightWithTwoWeaponsRank = 1, 1, FightWithTwoWeapons::class));
        self::assertSame(
            ($expectedWeaponSkillMalus = 789) + ($expectedTwoWeaponsSkillMalus = 2223),
            $skills->getMalusToBaseOfWoundsWithWeaponlike(
                $weaponlikeCode,
                $this->createMissingWeaponSkillsTable(
                    'baseOfWounds',
                    0 /* expected weapon skill value */,
                    $expectedWeaponSkillMalus,
                    $fightWithTwoWeaponsRank, // expected fight with two weapons skill rank value
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
        $missingShieldSkillsTable = $this->createMissingShieldSkillsTable();
        /*$missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(\Mockery::type(PhysicalSkillRank::class))
            ->andReturnUsing(function (PhysicalSkillRank $physicalSkillRank) {
                self::assertSame(0, $physicalSkillRank->getValue());

                return 'foo';
            });*/
        $missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(0)
            ->andReturn('foo');
        self::assertSame('foo', (new PhysicalSkills())->getMalusToCoverWithShield($missingShieldSkillsTable));

        $physicalSkills = new PhysicalSkills();
        $physicalSkills->addPhysicalSkill($this->createPhysicalSkill(1, 1, ShieldUsage::class));
        $missingShieldSkillsTable = $this->createMissingShieldSkillsTable();
        $missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(\Mockery::type(PhysicalSkillRank::class))
            ->andReturnUsing(function (PhysicalSkillRank $physicalSkillRank) {
                self::assertSame(1, $physicalSkillRank->getValue());

                return 'bar';
            });
        self::assertSame('bar', $physicalSkills->getMalusToCoverWithShield($missingShieldSkillsTable));

        $physicalSkills = new PhysicalSkills();
        $physicalSkills->addPhysicalSkill($this->createPhysicalSkill(2, 1, ShieldUsage::class));
        $missingShieldSkillsTable = $this->createMissingShieldSkillsTable();
        $missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(\Mockery::type(PhysicalSkillRank::class))
            ->andReturnUsing(function (PhysicalSkillRank $physicalSkillRank) {
                self::assertSame(2, $physicalSkillRank->getValue());

                return 'baz';
            });
        self::assertSame('baz', $physicalSkills->getMalusToCoverWithShield($missingShieldSkillsTable));

        $physicalSkills = new PhysicalSkills();
        $physicalSkills->addPhysicalSkill($this->createPhysicalSkill(3, 2, ShieldUsage::class));
        $missingShieldSkillsTable = $this->createMissingShieldSkillsTable();
        $missingShieldSkillsTable->shouldReceive('getCoverMalusForSkill')
            ->with(\Mockery::type(PhysicalSkillRank::class))
            ->andReturnUsing(function (PhysicalSkillRank $physicalSkillRank) {
                self::assertSame(3, $physicalSkillRank->getValue());

                return 'qux';
            });
        self::assertSame('qux', $physicalSkills->getMalusToCoverWithShield($missingShieldSkillsTable ));
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
        $physicalSkills = new PhysicalSkills();
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
        $physicalSkills = new PhysicalSkills();
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
        $skills = new PhysicalSkills();
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
        $skills = new PhysicalSkills();
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
        $skills = new PhysicalSkills();
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
        (new PhysicalSkills())->getMalusToFightNumberWithProtective(
            $this->mockery(ProtectiveArmamentCode::class),
            $this->mockery(Armourer::class)
        );
    }

}