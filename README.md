[![Build Status](https://travis-ci.org/jaroslavtyc/drd-plus-person-skills.svg?branch=master)](https://travis-ci.org/jaroslavtyc/drd-plus-person-skills)

Skills of a person for DrD+

PersonSkills are all the person skills on one pile.
-> PersonSameTypeSkills are skills of same type, like physical, on one pile
  -> PersonSkill is specific learned "ability", like horse riding
    -> PersonSkillRank is a "level" of the skill
       => PersonSkillPoint is the only but required price of a PersonSkillRank

PersonSkillPoint is the currency unit for a PersonSkillRank, composed from specific values, in specific combinations
-> BackgroundSkillPoints are standard value given by first level
-> ProfessionLevel is a level increment, cary-ing a property increment, which provides a skill point
-> two PersonSkillPoint(s) of type(s)-different-than-paid-one can be used for trade of new PersonSkillPoint

Checks if payments haven't been used more times elsewhere:
(that should check at least PersonSkills as the final aggregator, or better every aggregator on the way)

PersonSkillPoint
- BackgroundSkillPoints - check their total usage against their availability by \DrdPlus\Person\Background\BackgroundSkillPoints::getSkillPoints
- ProfessionLevel - there is nothing to check, on every level can be obtained plenty of skill points
- cross-type PersonSkillPoint as a payment - has to be unique, therefore no one else can use it, for payment nor as standard skill point

PersonSkillRank
- PersonSkillPoint has to be unique in whole universe, see PersonSkillPoint cross-type payment check

PersonSkill
- can be used just as an arbiter due its aggregating meaning
- -can check if BackgroundSkillPoints are not overused- should not check it, its too tricky, let it to PersonSkills
- can check if PersonSkillPoint and PersonSkillRank are unique locally
- can check if cross-type PersonSkillPoint(s) as a payment are unique and not used as a standard point locally

PersonSameTypeSkills
(can sum all the first and next levels skill ranks)
- can be used just as an arbiter due its aggregating meaning

PersonCombinedSkills + PersonPhysicalSkills + PersonPsychicalSkills
(has ability to find out unused skill point values of same-type skills)
- all the same-type skills it aggregate have to be unique in whole universe

PersonSkills
- PersonCombinedSkills + PersonPhysicalSkills + PersonPsychicalSkills has to be unique in whole universe
- should be the final arbiter, -recommended way is to re-use existing checks of sub-aggregates-
