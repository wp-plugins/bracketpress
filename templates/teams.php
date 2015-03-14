<h1>Manage Teams</h1>

<?php  if (!class_exists( 'BracketPressPro' )) { ?>
<div class="updated">
<p>
    Don't want to mess with data entry? Rather have a beer than update scoring during the tournament?
    The <a href="http://www.bracketpress.com/downloads/bracketpress-pro-data-feed/" target="store">BracketPress Pro Data Plugin</a> automatically gives you sample data,
    (from 2014), PLUS updates your teams with 2015 on Selection Data.
</p>
<p>
    Every time a game is played, the Pro
    Plugin updates your master bracket, re-calculates scoring, and optionally notifies your users of their
    scores - <i>plus much more!</i>. Note: All features may not be available until after March 10th.
</p>
<p>
<center>
<table width=90%>
<tr><td><li>Automatically populate teams and seeds on Selection Sunday</li></td><td><li>2012 Data Pre-loaded for Testing</li></td><td><li>Premium Support Included w/ PRO</li></td></tr>
<tr><td><li>Automatic Updates of Game Winners and Scores</li></td><td><li>Automatic Re-calculations of Scoring</li></td><td><li>Exclusive Pro Member Forum</li></td></tr>
<tr><td><li>Optional User Notifications of Updates</li></td><td><li>Access to Add-Ons Before the Public</li></td><td><li>Develop Chat Invitations</li></td></tr>
</table>
</center>

</p>
<p>
   <a href="http://www.bracketpress.com/downloads/bracketpress-pro-data-feed/" target="store">Go Pro! Now</a>
</p>
</div>
<p>
    Or, enter the team names, below:
</p>
<?php } else {
?>
<p>BracketPress Pro is installed and is managing your team names. Please note changes below will be overwritten during the tournament. </p>
<?php } 

if($_REQUEST['changeposition'] && check_admin_referer( 'bracketpress_changeposition' )){
    update_option( 'bracketpress_regionname_1', $_REQUEST['position1_name']);

     update_option( 'bracketpress_regionname_2', $_REQUEST['position2_name']);

    update_option( 'bracketpress_regionname_3', $_REQUEST['position3_name']);

    update_option( 'bracketpress_regionname_4', $_REQUEST['position4_name']);

}

?>
<!--<textarea> --><?php
$regiondata = get_option( 'bracketpress_region_1', '1').",".get_option( 'bracketpress_regionname_1', 'SOUTH').",".
get_option( 'bracketpress_region_2', '2').",".get_option( 'bracketpress_regionname_2', 'WEST').",".
get_option( 'bracketpress_region_3', '3').",".get_option( 'bracketpress_regionname_3', 'EAST').",".
get_option( 'bracketpress_region_4', '4').",".get_option( 'bracketpress_regionname_4', 'MIDWEST');

//echo $regiondata;

?>

<!-- </textarea> -->
<form id="update_region_positions" method="POST">
<table>
    <tr>
        <td>Position 1</td>
        <td><input type="text" name="position1_name" value="<?php echo get_option( 'bracketpress_regionname_1', 'SOUTH'); ?>"></td>
        <td>
        </td>
        <td></td>
        <td>Position 3</td>
        <td><input type="text" name="position3_name" value="<?php echo get_option( 'bracketpress_regionname_3', 'WEST'); ?>"></td>
        <td>
        </td>
    </tr>
    <tr>
        <td>Position 2</td>
        <td><input type="text" name="position2_name" value="<?php echo get_option( 'bracketpress_regionname_2', 'EAST'); ?>"></td>
        <td>
        </td>
        <td></td>
        <td>Position 4</td>
        <td><input type="text" name="position4_name" value="<?php echo get_option( 'bracketpress_regionname_4', 'MIDWEST'); ?>"></td>
        <td>
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:right;"><input type="submit" class="button-secondary" value="Save Region Labels"></td>
    </tr>
</table>
<?php wp_nonce_field( 'bracketpress_changeposition' ); ?>
<input type="hidden" name="changeposition" value="TRUE">
</form>

<style>
.team-data-table{
    margin-right:30px;
}
</style>

<form id="bracket_fillout_form" name="bracket_fillout_form" method="post">
<input type="submit" class="button-primary" value="Save Changes" id="submit_the_form"/>
    <table cellpadding="10px">

    <?php

        $team_info = queries::readBracketData();
        $number_of_teams = queries::getTeamCount();

        function bracketpress_team_for($team_info, $id, $region, $seed) {

            foreach ($team_info as $team) {
                if ($team['ID'] == $id) return $team;
            }
            // Can't find it, done.
            return array('ID' => $id, 'region' => $region, 'seed' => $seed, 'name' => '', 'conference' => '');
        }

        for ($i = 0; $i < NUMBER_OF_TEAMS; $i++) {
            $seed = ($i % 16) + 1;
            $region = (int)($i / 16) + 1;
            $id = $region * 100 + $seed;
            $team = bracketpress_team_for($team_info, $id, $region, $seed);

            $selected_region = array('', '', '', '', '');
            $selected_region[$team['region']] = "selected='selected'";

            $y = $i + 1;
            

           

            
            if($seed == 1){

                if($team['region'] == 1 || $team['region'] == 3){
                    echo '<table><tr><td><table class="team-data-table">';
                }
                if($team['region'] == 2 || $team['region'] == 4){
                    echo '<td><table class="team-data-table">';
                }
                echo "<tr>
                        <td><h4>Team</h4></td>
                        <td><h4>Region</h4></td><td></td><td></td><td></td>
                        <td><h4>Seed</h4></td>
                    </tr>";
            }
            ?>

            <tr>
                <td>
                    <input type="text" id="team_<?php echo $y; ?>" name="team_<?php echo $y; ?>" placeholder="Team <?php echo $y; ?>" value="<?php echo $team['name'] ?>"/>
                </td>
                <td colspan="4">
                    <?php echo get_option( 'bracketpress_regionname_'.$team['region']); ?>
                    <input type="hidden" name="region_<?php echo $y; ?>" value="<?php echo $team['region']; ?>">

                </td>
                <td>
                    <select id="seed_<?php echo $y; ?>" name="seed_<?php echo $y; ?>">
                        <option value="<?php echo $team['seed']; ?>"><?php echo $team['seed'];?></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                    </select>
                </td>

            </tr>
            <?php
            if($seed == 16){

                if($team['region'] == 1 || $team['region'] == 3){
                    echo "</table></td>";
                }
                if($team['region'] == 2 || $team['region'] == 4){
                    echo "</table></td></tr></table>";
                }
            }
            $rowcount++;
        }
        ?>
    </table>
    <input type='hidden' id='form_submitter' name='form_submitter'/>
    <input type="submit" class="button-primary" value="Save Changes" id="submit_the_form"/>
</form>
