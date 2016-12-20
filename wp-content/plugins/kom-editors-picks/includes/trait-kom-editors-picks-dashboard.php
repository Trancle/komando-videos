<?php
/**
 * User: gilbert
 * Date: 6/8/2015
 * Time: 10:33 AM
 *
 * Add an Editor's Picks widget to the dashboard
 *
 * This is hooked into the 'wp_dashboard_setup' action
 * It shows up to five posts marked as Editor's Picks
 * during the last 48 hours and up to three future posts.
 */

trait Kom_Editors_Picks_Dashboard
{
    function add_editors_picks_list( ) {

        wp_add_dashboard_widget(
            'editors_picks_list',           // Widget slug.
            "List of Current Editor's Picks for Google News",          // Title.
            array($this, 'display_editors_picks_list')  // Display function.
        );
    }

    /**
     * Output a list of recent Editor's Picks records
     */
    function display_editors_picks_list( ) {
        // Set query parameters for list of current and future posts
        $dNow = current_time( 'mysql' );    // Date and time of right now.
        $minimum_articles_required = 3;     // The minimum Editor's Picks required by Google at any given time.
        $maximum_life_of_editor_pick = (3600 * 48);  // The length of time a Pick stays on Google. 48 hours total in seconds. 172800
        $maximum_time_to_check_picks = (3600 * 72);  // This is 72 hours into the FUTURE in seconds. 259200
        $tStart = current_time( 'timestamp' ) - $maximum_life_of_editor_pick;   // Start at 48 hours ago.
        $tEnd = current_time( 'timestamp' ) + $maximum_time_to_check_picks;   // End at 72 hours from now.

        // Run a query on the database to get a list of the relevant post records.
        $args = array(
            'date_query' => array(
                array(
                    'after' => date('Y-m-d H:i:s',$tStart),
                    'before' => date('Y-m-d H:i:s',$tEnd),
                    'inclusive' => true,
                ),
            ),
            'orderby' => 'post_date',
            'order' => 'desc',
            'posts_per_page' => -1,
            'post_type' => array('post', 'columns', 'downloads', 'apps', 'cool_sites', 'tips', 'charts', 'happening_now', 'small_business', 'new_technologies'),
            'post_status' => array('publish', 'future', 'scheduled'),
            'meta_query' => array(
                array(
                    'key' => 'editors_picks_meta_id',
                    'value' => 1,
                    'compare' => '=',
                ),
            ),
        );

        // Get the list of Editor's Picks records.
        $picks_list = new WP_Query($args);

        $editors_picks_hole = 0;
        // Store the records in one of two arrays, depending upon status.
        if ($picks_list->have_posts()) {
            $start_times = array();
            $aPicks = array();
            $nPickNo = 0;
            $nPubNo = 0;
            $nFutNo = 0;
            // Go through the records found one at a time.
            while ($picks_list->have_posts()) {
                // Increment the list of posts.
                $picks_list->the_post();

                // Use this to determine if there is a hole in the future schedule
                $start_times[] = get_the_time('U', false);

                // $status will be either 'publish' or 'future'.
                $nPickNo++;
                $status = get_post_status();
                // Add record to Picks array if it's a future Pick or it's a current Pick within the last 48 hours.
                if (($status == 'future') OR (($status == 'publish') AND ((get_the_date("Y-m-d H:i:s") <= $dNow) AND (get_the_date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", $tStart))))){
                    if($status == 'publish') {
                        $nPubNo++;
                    } else {
                        $nFutNo++;
                    }
                    $aPicks[$nPickNo]['status'] = $status;
                    $aPicks[$nPickNo]['post_id'] = get_the_ID();
                    $aPicks[$nPickNo]['edit_url'] = get_edit_post_link($aPicks[$nPickNo]['post_id']); // This is working
                    $aPicks[$nPickNo]['title'] = ucwords(get_the_title());
                    $aPicks[$nPickNo]['author'] = ucwords(get_the_author());
                    $aPicks[$nPickNo]['post_type'] = ucwords(str_replace('_', ' ', get_post_type()));
                    // This needs to be the numeric Time
                    $aPicks[$nPickNo]['datetime'] = get_the_time("U");
                }
            }

            // If there are fewer than 3 future editors picks, indicate that we have a hole for the Warnings
            if ( count($start_times) < $minimum_articles_required ) {
                // There are less than three articles now!
                $editors_picks_hole = current_time('timestamp');    // NOW
            } else {
                // We now have an array of start times + 1 for 72 hours out
                sort($start_times);
                // Test having at least 3 articles at any given time across the 72-hours
                $start_times[] = $tEnd;

                for ($i = $minimum_articles_required; $i < count($start_times); ++$i) {
                    for ($j = $i - ($minimum_articles_required); $j < $i; ++$j) {
                        $end_time = ($start_times[$j] + $maximum_life_of_editor_pick);

                        if ($end_time < $start_times[$i]) {
                            $editors_picks_hole = $end_time;
                            break 2;
                        }
                    }
                }
            }
        } else {
            // Less than three Editor's Picks exist NOW
            $editors_picks_hole = current_time('timestamp');
        }

        // Display instructions
        echo "<br>This chart shows the articles which have been selected as Editor's Picks starting from 48 hours ago to the next 72 hours in the future. A warning message will ";
        echo "appear if there are not at least three Editor's Picks scheduled for any 48-hour period, or if there are not five Editor's Picks scheduled in the last 48 hours.<p>";

        // Add notice in red if there are fewer than five current Editor's Picks posted.
        if ($nPubNo == 0) {
            echo "<h3 style='color: #CC2900'>CRITICAL: No Editor's Picks articles for the last 48 hours were found. Add or select additional articles now and maintain a total of five.</h3>";

        } elseif ($nPubNo < 3) {
            echo "<h3 style='color: #CC2900'>URGENT: A minimum of three current Editor's Picks articles is required. Add or select additional articles now and maintain a total of five.</h3>";

        } elseif ($nPubNo < 5) {
            echo "<h3 style='color: #CC2900'>NOTICE: There are less than five current Editor's Picks articles right now. Add or select additional articles to maintain a total of five.</h3>";

        }
        // Display warning message if a hole of less than 3 Picks are scheduled during any 48 hour period.
        if ($editors_picks_hole > 0){
            echo "<h3 style='color: #CC2900'>WARNING: Less than three Editor's Picks are scheduled during the next 72 hours, starting " . date('l, F j, Y \a\t g:i a', $editors_picks_hole) .
                "! Add or select additional articles now.</h3> ";
        }

//        echo "count Picks: " . count($aPicks) . "<br>";
// ========================================================================================================================== JavaScript starts here
?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {packages:["timeline"]});
        google.setOnLoadCallback(drawChart);

        function drawChart() {
            var container = document.getElementById('timeline');
            var chart = new google.visualization.Timeline(container);
            var dataTable = new google.visualization.DataTable();

            dataTable.addColumn({ type: 'string', id: 'DateTime' });
            dataTable.addColumn({ type: 'string', id: 'Title' });
            dataTable.addColumn({ 'type': 'string', id: 'Link', 'role': 'tooltip', 'p': {'html': true}});
            dataTable.addColumn({ type: 'date', id: 'Start' });
            dataTable.addColumn({ type: 'date', id: 'End' });
<?php
            // Add the data within a loop here
            $nPickNo = 1;
            // Draw only if there is an article picked
            $draw = false;
            while ($nPickNo <= count($aPicks)){
                $draw = true;
                $cDateTime = date('D, M j, g:i a', $aPicks[$nPickNo]['datetime']);
                $cTitle = $aPicks[$nPickNo]['title'];
                if (strlen($cTitle) > 60){
                    $cTitle = substr(str_replace('"','\"',htmlspecialchars_decode($cTitle,ENT_QUOTES )),0,57) . '...';
                } else {
                    $cTitle = substr(str_replace('"','\"',htmlspecialchars_decode($cTitle,ENT_QUOTES )),0,60);
                }
                $cLink = $aPicks[$nPickNo]['edit_url'];
                $cStart = 'Date(' . date('Y, n - 1, j, G, i', $aPicks[$nPickNo]['datetime']) . ')';
                $cEnd = 'Date(' . date('Y, n - 1, j, G, i', $aPicks[$nPickNo]['datetime'] + (48 * 3600)) . ')';

                // Add the row to the timeline
                echo 'dataTable.addRow([ "' . $cDateTime . '", "' . $cTitle . '", "' . str_replace('"','\"',htmlspecialchars_decode($cLink,ENT_QUOTES )) . '", new ' . $cStart . ', new ' . $cEnd . ' ]);';

                $nPickNo++;
            }
            // End of data loop

            // Set depth (height) of chart on the page
            if (count($aPicks) > 2) {
                $nDepth = (count($aPicks)) * 58;
            } else {
                $nDepth = 150;
            }

?>
            // Specify the options to be used
            var options = {
                height: <?php echo $nDepth; ?>,
                title: 'Editor Picks Schedule',
                colors: ['9DCEFF','FFBFDD'],
                focusTarget: 'category',
                tooltip: { isHtml: true },
                timeline: { showRowLabels: true }
            };
            <?php if ($draw){ ?>
                // Add listener
                google.visualization.events.addListener(chart, 'select', function(){
                    var selection = chart.getSelection();
                    if (selection.length) {
                        window.location.href = dataTable.getFormattedValue(selection[0].row, 2);
                    }
                });
                // Draw the chart
                chart.draw(dataTable, options);
            <?php } ?>
        }
    </script>
    <div id="timeline" style="width: 1000px; height: <?php echo $nDepth; ?>px;"></div>
    <?php
    }
}
