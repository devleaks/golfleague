Rules
=====

Rules is the cornerstone of the competition.

Rules allow you to organise competition in about any format.

At the Match level, the Rule simply gives the format of the match:

- Strokeplay,
- Matchplay,
- Stableford,
- 4BB

or  any other common golf game format.

At Tournament and Season levels, Rules dictates how two or more matches get combined
to elect a winner.

Each competition has two rules:

  1. The Competition Rule is the rule by which the competition is played, and how a winner is found.
  1. The Post-Competition Rule is a rule applied after the competition terminates and all scorecards are returned.

The Post-Competition Rule allows you to use the result of the competition to produce another result or ranking.
For example, after a Stableford match, you may want to distributes points per position: The first one in the match and ties get 10 points,
the second ones get 8 points, and so on.
The Post-Competition Rule should only affect the scorecards of the competition it applies to.

Rules depends on the competition type (Match, Tournament, or Season).
 


The Rule Source Type determines which value is fetched from the scorecard to get the score.
The Rule Source Direction determines the order, asscending or descending, used to sort scores.

The Rule Destination Type determines where (in which column of the scorecard) the result of a Post Rule Competition is saved.

The Classname is the name of the class that implements the Rule.


The Compute function of a Rule class is applied to the scorecards of the competition. It only affects the scorecards of the competition.
It is used, for example for a Tournament, to compute the sum of points or strokes from all Matches of that Tournament.
