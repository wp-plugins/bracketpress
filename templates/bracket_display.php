<?php
/**
 * This file is included from the core bracket_edit function and is designed to be a
 * template the selections, selection_json and $post variables are all set before
 * including this file.
 *
 * see bracketpress.php: bracketpress::bracket_display
 *
 * @var array $selections
 * @var string $message
 * @var array $post
 */

/**
 * Add our CSS
 */

function bracketpress_display_enqueue_css() {
    wp_register_style('bracket_readonly', BRACKETPRESS_CSS_URL . 'bracket_readonly.css');
    wp_enqueue_style('bracket_readonly');
}
add_action('wp_enqueue_scripts', 'bracketpress_display_enqueue_css');


/**
 * This is the actual output code for each bracket.
 *
 * @param $this_match_id
 * @param $m
 * @param $team1
 * @param $team2
 */

function bracketpress_partial_display_bracket($this_match_id, $m, $team1, $team2, $final = false, $match = null) {

    // Special id tags to make the final bracket work
    if ($final) {
        $id1 = "id='slot127'";
        $id2 = "id='slot128'";
    } else {
        $id1 = '';
        $id2 = '';
    }

/*
    $class = '';
    if ($match) {
        if ($match->points_awarded > 0) {
            $class = 'won';
        } else if ($match->points_awarded === 0) {
            $class = 'lost';
        }
    }
*/
    ?>
<div id="match<?php print $this_match_id ?>" class="match m<?php print $m ?>">
    <p class="slot slot1 team_<?php echo $team1->ID ?>" <?php echo $id1 ?>>
            <span class="seed <?php echo $class ?>">
                <?php if ($team1) { ?>
                <span class="team_ids"> <?php echo $team1->seed; ?></span> <?php print bracketpress_display_name($team1->name) ?></span>
            <?php } ?>
                <em class="score"><?php  ?></em>
    </p>
    <p class="slot slot2 team_<?php echo $team1->ID ?>" <?php echo $id2 ?>>
            <span class="seed <?php echo $class ?>">
                <?php if ($team2) { ?>
                <span class="team_ids"> <?php echo $team2->seed; ?></span> <?php print bracketpress_display_name($team2->name) ?>
            </span>
            <?php } ?>
                <em class="score"><?php  ?></em>
    </p>
</div>
<?php
}

/**
 * Display the seed brackets for a region.
 * For this, we need the team list (with the seeds)
 *
 * @param $region
 */
function bracketpress_partial_display_seed($region) {

    $base = ($region - 1) * 15;
    $matchlist = new BracketPressMatchList(bracketpress()->post->ID);

    for ($x = 0; $x < BracketPressMatchList::$bracket_size; $x++) {
        $match_id = $base + $x + 1;
        $match = $matchlist->getMatch($match_id);

        $team1 = $match->getTeam1();
        $team2 = $match->getTeam2();

        $m = $x+1;

        bracketpress_partial_display_bracket($match_id, $m, $team1, $team2, false, $match);
    }
}


/**
 * Displays a round for one region
 *
 * @param $round current round, which tells us how many matches will be in this round
 * @param $region the region this match is for
 *
 * @internal param \List $selections of user selections
 * @internal param \the $loop_start start index in the array to use. (now ignored)
 *
 */
function bracketpress_partial_display_round($round, $region) {

    $matchlist = new BracketPressMatchList(bracketpress()->post->ID);

    $offset = $increment = 0;
    if ($round == 2) {  $increment = 8;   $offset = 0;  }
    if ($round == 3) {  $increment = 4;   $offset = 0 + 8;  }
    if ($round == 4) {  $increment = 2;   $offset = 0 + 8 + 4;   }
    if ($round == 5) {  $increment = 1;   $offset = 0 + 8 + 4 + 2;  } // Inaccurate

    $base = ($region - 1) * 15; // We have 15 matches per region
    $start = $base + $offset + 1;

    $end = $start + $increment;

    $match_count = 1;
    for ($x = $start; $x < $end; $x += 2) {

        /*
         * $x is sitting at the previous match
         */

        list($this_match_id, $slot) = BracketPressMatchList::getNextMatch($x);
        $this_match = $matchlist->getMatch($this_match_id);

        $team1 = $this_match->getTeam1();
        $team2 = $this_match->getTeam2();

        bracketpress_partial_display_bracket($this_match_id, $match_count++, $team1, $team2);
    }
}

/**
 * Display a round for each region
 *
 * @param $num
 * @param $name
 */
function bracketpress_display_rounds($num, $name) {
    ?>
<div id="round<?php print $num ?>" class="round">
    <h3>Round <?php print $name ?> (2013 NCAA Men's Basketball Tournament)</h3>

    <div class="region region1">
        <h4 class="region1">SOUTH</h4>
        <?php bracketpress_partial_display_round($num, BRACKETPRESS_REGION_SOUTH); ?>
    </div>
    <div class="region region2">
        <h4 class="region2">WEST</h4>
        <?php bracketpress_partial_display_round($num, BRACKETPRESS_REGION_WEST); ?>
    </div>
    <div class="region region3">
        <h4 class="region3"> EAST </h4>
        <?php bracketpress_partial_display_round($num, BRACKETPRESS_REGION_EAST); ?>
    </div>
    <div class="region region4">
        <h4 class="region4">MIDWEST</h4>
        <?php bracketpress_partial_display_round($num, BRACKETPRESS_REGION_MIDWEST); ?>
    </div>
</div>
<?php
}

/* === Begin Page === */
?>

<?php if ($message) print $message; // Flash message?>
<?php if (bracketpress()->post->post_excerpt) {
   print "<p>".bracketpress()->post->post_excerpt . "</p>";
}
?>
<font size="+1">Current Bracket Score: <?php print bracketpress()->get_score(); ?></font>

<?php   if (bracketpress()->is_bracket_owner()) {  ?>
<a href="<?php print bracketpress()->get_bracket_permalink(bracketpress()->post->ID, true)?>" style="float: right;">Edit Bracket</a>
<?php } ?>

<div class="bracket standings light-blue">
<div id="content-wrapper">
<div id="table">

    <!-- Table Dates -->
    <table class="gridtable">
        <tr>
            <th class="round_1 current"> 1st ROUND</th>
            <th class="round_2 "> 2nd ROUND</th>
            <th class="round_3"> SWEET 16</th>
            <th class="round_4"> ELITE EIGHT</th>
            <th class="round5"> FINAL FOUR</th>
            <th class="round_6"> CHAMPION</th>
            <th class="round_5"> FINAL FOUR</th>
            <th class="round_4"> ELITE EIGHT</th>
            <th class="round_3"> SWEET 16</th>
            <th class="round_2"> 2nd ROUND</th>
            <th class="round_1 current"> 1st ROUND</th>
        </tr>
<!--
        <tr>
            <td class="current"> March 18-19</td>
            <td> March 20-21</td>
            <td> March 25-26</td>
            <td> March 27-28</td>
            <td> April 3</td>
            <td> April 5</td>
            <td> April 3</td>
            <td> March 27-28</td>
            <td> March 25-26</td>
            <td> March 20-21</td>
            <td class="current"> March 18-19</td>
        </tr>
-->
    </table>
</div>

<div id="bracket">
    <!-- Bracket -->
    <div id="round1" class="round">
        <h3>
            Round One (2013 NCAA Men's Basketball Tournament)
        </h3>
        <div class="region region1">
            <h4 class="region1 first_region">SOUTH</h4>
            <?php bracketpress_partial_display_seed(BRACKETPRESS_REGION_SOUTH) ?>
        </div>
        <div class="region region2">
            <h4 class="region2 first_region">WEST</h4>
            <?php bracketpress_partial_display_seed(BRACKETPRESS_REGION_WEST) ?>
        </div>
        <div class="region region3">
            <h4 class="region3 first_region">EAST</h4>
            <?php bracketpress_partial_display_seed(BRACKETPRESS_REGION_EAST) ?>
        </div>
        <div class="region region4">
            <h4 class="region4 first_region">MIDWEST</h4>
            <?php bracketpress_partial_display_seed(BRACKETPRESS_REGION_MIDWEST) ?>
        </div>
    </div>

<?php
    bracketpress_display_rounds(1, 'One');
    bracketpress_display_rounds(2, 'Two');
    bracketpress_display_rounds(3, 'Three');
    bracketpress_display_rounds(4, 'Four');
?>

    <div id="round5" class="round">
        <h3>Round Five (2013 NCAA Men's Basketball Tournament)</h3>

        <div class="region">
        <?php
            $matchlist = new BracketPressMatchList(bracketpress()->post->ID);

            for($x = 1; $x <3; $x++) {
                $match_id = 60 + $x;
                $match = $matchlist->getMatch($match_id);

                $team1 = $match->getTeam1();
                $team2 = $match->getTeam2();

                bracketpress_partial_display_bracket($match_id, $x, $team1, $team2, false, $match);
            }
        ?>
        </div>
    </div>
    <div id="round6" class="round">
        <h3> Round Six (2013 NCAA Men's Basketball Tournament) </h3>

        <div class="region">
        <?php

            $match = $matchlist->getMatch(63);

            $team1 = $match->getTeam1();
            $team2 = $match->getTeam2();

            bracketpress_partial_display_bracket(63, 1, $team1, $team2, $final = true, $match);
        ?>
        </div>
    </div>
</div>

