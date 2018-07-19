<?php
declare(strict_types = 1);

namespace DrdPlus\Skills;

use Doctrine\Common\Collections\Collection;
use Doctrineum\Entity\Entity;
use DrdPlus\Person\ProfessionLevels\ProfessionLevel;
use Granam\Strict\Object\StrictObject;

/**
 * @Doctrine\ORM\Mapping\MappedSuperclass()
 */
abstract class Skill extends StrictObject implements Entity
{
    /**
     * @var int
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @param ProfessionLevel $professionLevel
     */
    protected function __construct(ProfessionLevel $professionLevel)
    {
        $this->getInnerSkillRanks()->add($this->createZeroSkillRank($professionLevel));
    }

    /**
     * @param SkillRank $skillRank
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedRankValue
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     */
    protected function addTypeVerifiedSkillRank(SkillRank $skillRank)
    {
        $this->guardSkillRankSequence($skillRank);
        $this->guardRelatedSkillOfRank($skillRank);
        $this->getInnerSkillRanks()->offsetSet($skillRank->getValue(), $skillRank);
    }

    /**
     * @param SkillRank $skillRank
     * @throws \DrdPlus\Skills\Exceptions\UnexpectedRankValue
     */
    private function guardSkillRankSequence(SkillRank $skillRank)
    {
        if (($this->getMaxSkillRankValue() + 1) !== $skillRank->getValue()) {
            throw new Exceptions\UnexpectedRankValue(
                'New skill rank has to follow rank sequence, expected '
                . ($this->getMaxSkillRankValue() + 1) . ", got {$skillRank->getValue()}"
            );
        }
    }

    /**
     * @param SkillRank $skillRank
     * @throws \DrdPlus\Skills\Exceptions\CanNotVerifyOwningSkill
     */
    private function guardRelatedSkillOfRank(SkillRank $skillRank)
    {
        if ($this !== $skillRank->getSkill()) {
            if (static::class !== \get_class($skillRank->getSkill())) {
                $message = 'New skill rank belongs to different skill class. Expecting ' . static::class . ', got '
                    . \get_class($skillRank->getSkill());
            } else {
                $message = 'New skill rank belongs to different instance of skill class ' . static::class;
            }
            throw new Exceptions\CanNotVerifyOwningSkill($message);
        }
    }

    /**
     * @return int
     */
    private function getMaxSkillRankValue(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gives cloned original skill ranks
     *
     * @return SkillRank[]|Collection
     */
    public function getSkillRanks(): Collection
    {
        return clone $this->getInnerSkillRanks();
    }

    /**
     * @return SkillRank[]|Collection
     */
    abstract protected function getInnerSkillRanks(): Collection;

    /**
     * @return SkillRank
     */
    public function getCurrentSkillRank(): SkillRank
    {
        return $this->getSkillRanks()->last();
    }

    /**
     * @param ProfessionLevel $professionLevel
     * @return SkillRank
     */
    abstract protected function createZeroSkillRank(ProfessionLevel $professionLevel): SkillRank;

    /**
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @return array|string[]
     */
    abstract public function getRelatedPropertyCodes(): array;

    /**
     * @return bool
     */
    abstract public function isPhysical(): bool;

    /**
     * @return bool
     */
    abstract public function isPsychical(): bool;

    /**
     * @return bool
     */
    abstract public function isCombined(): bool;

}