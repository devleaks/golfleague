Yii Golf League
===============

Introduction
------------

Yii Golf League is a web application for running a golf league.

Like all applications, Yii Golf League uses its own flexible approach to golf leagues.
The following paragraphs introduce things we talk about in this application.


The application is suitable for a single, small golf leagues run by a few friends, or a large, club-based league run by a golf coordinator.

A single installation of the application can host one or more leagues.

Each league can have its own, private area, while some elements such as golf courses are available across all leagues.

The web application has 2 sides run on the same computer server but accessed by two different URLs.

The first side is for managing the league: Creating competitions, confirming registration, preparing flight start lists, and reporting scores.

The second side is for golfers, to view league news and calendars of competitions, register to competitions, print custom scorecards, and view scoreboards.


 

Competitions
------------

The heart of Yii Golf League is the organisation of competitions from initial
scheduling to final scoreboard display.

Competitions can be as simple as a single match or as complex as a season-long,
weekly contest.

There are three types of « competition » to organise your league:

1.  Rounds

2.  Tournaments

3.  Seasons

Rounds are the only competitions that can be played by golfers. The two other
types of competitions (tournaments and seasons) aggregate scores from rounds.

For exemple, if you have a PGA Tour style of 4-day competitions, you would play a competition of type Round each day,
and all 4 competitions would be grouped into a competition of type Tournament, may be with a "cut" after 2 days.
Similarly, all your PGA Tour style Tournaments may be grouped into a season-long competition of type "Season".
 

Competition Rules
-----------------

Competition follow rules.

At round level, competition rule are traditional golf rule systems: Strokeplay, matchplay, Stableford, foursome, best ball...

Again, at tournament and season levels, rules traditionally found in common systems are available:
Simple sum of scores, Best of three scores, points per position...


If necessary, Yii Golf League allow you to develop and insert your personal rule system if you need through a simple plugin system.



Scoring
-------

Scoring can be done at different level, from global, match-based score, to
detailed hole-by-hole score and statistics.

 

 

Golf course and more
--------------------

 

 

Golfers
-------

 

Example of Use
--------------

Here would be an example of a common use of the application.

 

#### Preparation

First, a league « manager » creates the « environment » for the competition.

He adds golfers with their handicap. Entering handicap is necessary if you use
the handicap system. Optionally, when adding a golfer to your league, you can
also add a Golf League account to allow him to log in to the application.

The league manager then adds the golf course, and at least one tee set from which golfer will play.
Again, you need to enter the tee set’s slope and index rating if you want to use
the handicap system. You may also add hole details such as hole par, length, and
handicap index. You must enter these details if you wish to enter score hole by
hole or print custom scorecards.

This completes the setup of your « environment » . You can now refer to these
golfers and golf courses in your competitions.

 
#### New Competition

Second, you add or create your competition. If it is a simple, single match event, add a
Round. If your competition is more complex and involves weekly rounds over the
course of the year, you will need to add a Season and a Tournament before you
can create your Round.

This complete the creation of the competition. Golfer can now register to the
competion.

#### Registration

If a golfer has a Golf League account to the web site, he can log in and get a calendar of
planned competitions. A golfer can then register to a competiton.

The league « starter » will be able to register those golfers who do not have
access to the web site.

#### Planning

When the registration period is terminated, the starter can proceed with the
building of the flights, assign tees to golfers according to their gender,
handicap, and other constraints. He terminates the preparation of the
competition with the publication of the flights and optionally, the printing of
the indivudual, custom scorecards for golfers.

The competition is now ready to be played.


#### Scoring

Scores can be entered in many different ways.

Authorized golfers are able to access their online scorecard and enter their own
scores. If so planned, they can even enter their score live, while playing.

A league « scorer » will enter scores for golfers who cannot enter their own
scorecards.

When scores are being entered, a live leaderboard is available on the web site
and allow people to follow the competiton live.

When all scores have been entered and all scorecards approved, the score of the
competition is published.

If this competition was part of a larger, multi-match tournament, the tournament’
scores are adjusted.

The tournament’s progress can be followed on live tournament scoreboards on the
Golf League web site.


Finally, on the web site, League Managers can add pictures to competitoins and events, and add
simple blogging messages.

 

Now go. Play golf.
