Rules
=====

A Rule is a mandatory parameter of a competition. It tells how a winner is found
when reading the scorecards of the competition.

Each competition has two rules:

1.  The Competition Rule.

2.  The Post-Competition Rule.

The Competition Rule is the rule by which the competition is played, and how a
winner is found.

The Post-Competition Rule is a rule applied after the competition terminates and
all scorecards are returned. The Post-Competition Rule allows you to use the
result of the competition to produce another result or ranking. For example,
after a Stableford match, you may want to distributes points per position: The
first one in the match and ties get 10 points, the second ones get 8 points, and
so on. The Post-Competition Rule should only affect the scorecards of the
competition it applies to.

Competition Rules
-----------------

Rules allow you to organise competition in about any format.

A Rule depends on the competition type (Round, Tournament, or Season).

Rules for Rounds specify the format of the match:

-   Strokeplay,

-   Matchplay,

-   Stableford,

-   4BB

or any other common golf game format.

Rules for Tournament (resp. Season), regulates how two or more Rounds (resp.
Tournaments) get combined to elect a winner.

Post Competition Rules
----------------------

The Post Competition Rule is a rule that is applied after all scorecards have been published.

An typical example of post-competition rule is the attribution of points depending on the ranking in the match.
For example, the winner of the match gets 10 points, second and ties get 8 points, and so on.
This attribution of points is performed throught the post-competition rule.

Another post-competition rule is the typical "cut" after a round.
For example, if a cut is applied after the 32nd player and ties, all contestants after the 32nd place and ties
would get their status switched to eliminated or cut.


More Rules
----------

The Golf League Application bundles a few common rules that cover numerous types
of competitions.

If your competition type cannot be regulated by a rule bundled with the
application, you can develop your own competition or post-competition rule.


Rule Attributes
---------------

The Source Type determines which value is fetched from the scorecard to get the
score.

The Source Direction determines the sorting order, asscending or descending.

The Destination Type determines where (in which column of the scorecard) the
result of a Post Rule Competition is saved.

The Classname is the name of the PHP class that implements the Rule. The PHP
class must be in \common\models\Rule domain name.

The Compute function of a Rule class is applied to the scorecards of the
competition. It only affects the scorecards of the competition. It is used, for
example for a Tournament, to compute the sum of points or strokes from all
Rounds of that Tournament.

Rule Parameters are semi-colon-separated (name, value) pairs. Parameters are
parsed from the parameter string and passed to the rule class instance before if
it is executed.

Example of parameters for a Rule would be, for example, the number or players
and ties kept after a cut after 2 matches in a 4 matches tournament. (A typical
PGA Tour tournament rule.)
