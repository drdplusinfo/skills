<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#vyucovani
 */
/**
 * @ORM\Entity()
 */
class Teaching extends CombinedSkill implements WithBonus
{
    const TEACHING = CombinedSkillCode::TEACHING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TEACHING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 2;
    }

}