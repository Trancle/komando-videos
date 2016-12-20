<?php
/*
Plugin Name: K2 On Air Check
Plugin URI: http://www.komando.com
Description: Checks to see if Kim is on air based on the time. Has override functionality for previous recorded shows.
Author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/
add_action('admin_menu', 'k2_on_air_page_menu');
function k2_on_air_page_menu() {
    add_menu_page('On Air Override', 'On Air Override', 'edit_others_posts', 'on-air-settings', 'k2_on_air_page');
}

function k2_on_air_page() {

    if ($_POST['check'] == '1') {

        if (isset($_POST['on-air-override'])) {
            $active = '1';

            if (isset($_POST['on-air-override-toggle'])) {
                $override = $_POST['on-air-override-toggle'];
            }

        } else {
            $active = '0';
            $override = 'on';
        }

        $data = array(
            'active' => $active,
            'override' => $override
        );
        update_option('on_air_data', $data);
    }

    $data = get_option('on_air_data');

    ?>
    <div class="wrap">
        <h2>On Air Notice Settings</h2>

        <form method="post" action="">
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row"><label for="on-air-override">Override On Air Notification</label></th>
                    <td>
                        <label for="on-air-override">
                            <input name="on-air-override" type="checkbox" id="on-air-override" <?php if ($data['active'] == '1') { echo 'checked="checked"'; } ?>>
                            Use this to override the automatic notice then choose on or off below.
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="on-air-override-toggle">Force On/Off</label></th>
                    <td>
                        <label for="on-air-override-toggle-on">
                            <input name="on-air-override-toggle" type="radio" value="on" id="on-air-override-toggle-on" <?php if ($data['override'] == 'on') { echo 'checked="checked"'; } ?>>
                            Force On
                        </label><br/>
                        <label for="on-air-override-toggle-off">
                            <input name="on-air-override-toggle" type="radio" value="off" id="on-air-override-toggle-off" <?php if ($data['override'] == 'off') { echo 'checked="checked"'; } ?>>
                            Force Off
                        </label>
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="check" value="1"/>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
        </form>
    </div>
<?php }

function k2_now() {
    // Returns current date/time object
    //return new DateTime('2015-12-12 8:04:00', new DateTimeZone('America/Los_Angeles') ); // ------------------------------ For testing a Saturday
    //return new DateTime('2015-12-11 12:52:00', new DateTimeZone('America/Phoenix') ); // ------------------------------ For testing a Friday

    if ( date('N') == 6 ) {
        // Day is Saturday
        $tz = 'America/Los_Angeles';
    } else {
        // Any other day
        $tz = 'America/Phoenix';
    }
    return new DateTime('NOW', new DateTimeZone( $tz ) );
}

function k2_wday($datetime = NULL) {
    // Returns the number of the current day of the week, minus one.
    if (!$datetime) {
        $datetime = k2_now();
    }
    return intval($datetime->format('N')) - 1;
}

function k2_is_saturday($datetime = NULL) {
    // Returns TRUE if day is Saturday, else FALSE
    if (!$datetime) {
        $datetime = k2_now();
    }
    return 5 === k2_wday($datetime);
}

function k2_is_friday($datetime = NULL) {
    // Returns TRUE if day is Friday, else FALSE
    if (!$datetime) {
        $datetime = k2_now();
    }
    return 4 === k2_wday($datetime);
}

function k2_next_or_current_saturday() {
    $saturday = k2_now();                               // DateTime object for Now, PST time if Saturday
    $days_to_add = ((5 - k2_wday($saturday)) + 7) % 7;  // Number of days to Saturday
    $saturday->modify("+$days_to_add days");            // Add number of days to Saturday from Now
    $saturday->setTime(0, 0);                           // Set the Time of the object to 12:00 midnight
    return $saturday;                                   // Return the object for next Saturday
}

function k2_next_counter_start() {
    return k2_next_or_current_saturday()->setTime(4, 0); // 4:00 AM PST
}

function k2_next_show_start() {
    return k2_next_or_current_saturday()->setTime(6, 55); // 6:55 AM PST
}

function k2_next_show_end() {
    return k2_next_or_current_saturday()->setTime(10, 0); // 10:00 AM PST
}

function k2_on_air_is_override_on() {
  $data = get_option('on_air_data');
  return $data['active'] == '1';
}
function k2_on_air_is_override_off() {
  $data = get_option('on_air_data');
  return $data['active'] == '0';
}
function k2_on_air_is_forced_on() {
  $data = get_option('on_air_data');
  return k2_on_air_is_override_on() && $data['override'] == 'on';
}
function k2_on_air_is_forced_off() {
  $data = get_option('on_air_data');
  return k2_on_air_is_override_on() && $data['override'] == 'off';
}

function k2_on_air_should_show_on_air() {
  $present = k2_now();
  return k2_on_air_is_override_on() || 
    // Saturday between 4:00 am and 10:00 am (This is Los Angeles time)
    (k2_is_saturday() && (k2_next_counter_start() <= $present) && ($present < k2_next_show_end())) ||
    // Friday between 12:50 pm and 3:00 pm (This is Phoenix time)
    (k2_is_friday() && (date_format($present, 'H:i:s') >= '12:50:00') && (date_format($present, 'H:i:s') < '15:50:00'));
}

function k2_on_air() {
  // Slider appears on the Home page and on the Show page on Friday or Saturday at designated times, or when forced by override.
  // This function determines whether the slider should be shown or not.

  if ( !k2_on_air_is_forced_off() && k2_on_air_should_show_on_air() ) {
    // The slider is forced on.
    k2_on_air_slider();
  }
}

function k2_on_air_slider() {

    $text_for_screens = [];
    $dow = date('N');
    //$dow = 6;    // For testing a Saturday
    //$dow = 5;    // For testing a Friday
    $club_base_uri = CLUB_BASE_URI;
    $station_finder_base_uri = STATION_FINDER_BASE_URI;
    $videos_base_uri = VIDEOS_BASE_URI . "/live-from-the-studio/latest";

    switch ($dow) {
        case 6:
            // Text to show on Saturday before and during the show.
            $text_for_screens[1] = <<<EOT
<p>Tune in this weekend coast-to-coast on over 450 stations, 177 countries and every ship at sea! <a href="$station_finder_base_uri">Click here</a> to find your local station now!</p>
<p>Listen anytime, anywhere with Kim's Podcast. <a href=$club_base_uri>Click here</a> to get your free 15-day trial now!</p>
EOT;
            $text_for_screens[2] = <<<EOT
<p>Kim's Club members, <a href=$videos_base_uri>click here</a> now to watch the show and see all the behind-the-scenes fun!</p>
<p>Not a Kim's Club member? <a href=$club_base_uri>Click here</a> to get your free 15-day trial now to watch the show!</p>
EOT;
            $microphone_text = ' On Air Now';
            break;

        case 5:
            // Text to show on Friday before and during the recording of the show.
            $text_for_screens[1] = <<<EOT
<b><p>Kim's Club members, <a href=$videos_base_uri>Click here</a> now to watch the show and get all the behind-the-scenes fun!.</p>
<p>Not a Kim's Club member? <a href=$club_base_uri>Click here</a> to get your free 15-day trial now to watch the show!</p></b>
EOT;
            /*
            $text_for_screens[2] = <<<EOT
<p>Kim's Club members, <a href=$videos_base_uri>Click here</a> now to watch the show and get all the behind-the-scenes fun!.</p>
<p>Not a Kim's Club member? <a href=$club_base_uri>Click here</a> to get your free 15-day trial now to watch the show!</p>
EOT;
*/
            $microphone_text = ' Streaming Live Now';
            break;
        default:
            // Any other day -- this only appears when the slider is forced on.
            $text_for_screens[1] = <<<EOT
<p>You can watch the Kim Komando Show right here on Saturdays as a Kim's Club member.</p>
<p>Not a Kim's Club member? <a href=$club_base_uri>Click here</a> to get your free 15-day trial now!</p>
EOT;
            $text_for_screens[2] = <<<EOT
<p>Kim's Club members can see the Kim Komando Show being recorded and streamed live on Fridays.</p>
<p><a href=$club_base_uri>Join now</a> to watch all the behind-the-scenes fun live!</p>
EOT;
            $microphone_text = ' Watch Now';
    }

    // This is TRUE if it is Saturday after the counter is started and before the show starts.
    $counter_should_display = ((k2_next_counter_start() <= k2_now()) && (k2_now() < k2_next_show_start()));

    if ($counter_should_display) {
        // This will show only between 4:00 AM and 6:55 AM on Saturday ?>
        <div class="on-air-alert on-air-countdown">
            <a href=<?php echo $videos_base_uri; ?>><div class="on-air-header">
                <div><i class="fa fa-microphone fa-2"></i> On Air In</div>
            </div></a>
            <div class="on-air-message-wrapper">
                <p><a href=<?php echo $club_base_uri; ?>>Kim's Club members</a> watch the show LIVE. The Kim Komando Show starts in:</p>
                <div id="kim-countdown"></div>
            </div>
        </div>
    <?php } else { ?>
        <div class="on-air-alert air-alert-wrapper">
            <a href=<?php echo $videos_base_uri; ?>><div class="on-air-header">
                <div><i class="fa fa-microphone fa-2" style="margin-bottom: 20px; float: left"></i><?php echo $microphone_text; ?></div>
            </div></a>
            <?php if ($dow == 5){ ?>
                <div style="position: relative; display: inline-block; margin-left: 200px">
                    <?php echo $text_for_screens[1]; ?>
                </div>
            <?php } else { ?>
                <div class="on-air-message-wrapper">
                    <ul>
                        <li><?php echo $text_for_screens[1]; ?></li>
                        <li style="display: none;"><?php echo $text_for_screens[2]; ?></li>
                    </ul>
                </div>
                <div class="on-air-pager hide-mobile">
                    <div class="on-air-back">
                        <i class="fa fa-angle-left"></i>
                    </div>
                    <div class="on-air-next">
                        <i class="fa fa-angle-right"></i>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            <?php if( $counter_should_display ) { ?>
            $('#kim-countdown').countdown({
                until: new Date(<?php echo k2_next_show_start()->getTimestamp() ?>000)
            });
            <?php } ?>

            ;
            (function ($) {

                $.fn.onairslider = function () {

                    var message_count = $('.on-air-message-wrapper ul li').length;

                    if (message_count < 3) {
                        $('.on-air-message-wrapper ul li').each(function () {
                            $(this).clone().appendTo('.on-air-message-wrapper ul');
                        });

                        message_count = $('.on-air-message-wrapper ul li').length;
                    }

                    $('.on-air-message-wrapper ul li:last-child').prependTo('.on-air-message-wrapper ul');

                    $(window).on('resize', function () {

                        $('.on-air-message-wrapper, .on-air-message-wrapper ul, .on-air-message-wrapper ul li').removeAttr('style');

                        var message_count = $('.on-air-message-wrapper ul li').length;
                        var message_width = $('.on-air-message-wrapper ul li').outerWidth();
                        var message_height = $('.on-air-message-wrapper ul li').outerHeight();
                        var message_total_width = message_count * message_width;

                        $('.on-air-message-wrapper').css({width: message_width});
                        $('.on-air-message-wrapper ul').css({width: message_total_width, marginLeft: -message_width});
                        $('.on-air-message-wrapper ul li').css({width: message_total_width / message_count});

                    }).trigger('resize');

                    var timer = setTimeout(message_delay, 5000);

                    function message_delay() {
                        tab_right();
                        timer = setTimeout(message_delay, 5000);
                    }

                    function tab_right() {
                        $('.on-air-message-wrapper ul').animate({
                            left: '-100%'
                        }, 1000, function () {
                            $('.on-air-message-wrapper ul li:first-child').appendTo('.on-air-message-wrapper ul');
                            $('.on-air-message-wrapper ul').css('left', '');
                        });
                    };

                    function tab_left() {
                        $('.on-air-message-wrapper ul').animate({
                            left: '+100%'
                        }, 1000, function () {
                            $('.on-air-message-wrapper ul li:last-child').prependTo('.on-air-message-wrapper ul');
                            $('.on-air-message-wrapper ul').css('left', '');
                        });
                    };

                    $('.on-air-next').click(function () {
                        clearTimeout(timer);
                        tab_right();
                        timer = setTimeout(message_delay, 5000);
                    });

                    $('.on-air-back').click(function () {
                        clearTimeout(timer);
                        tab_left();
                        timer = setTimeout(message_delay, 5000);
                    });

                }

            })(jQuery);

            $('.wrapper').css({borderTop: 0});
            $('.on-air-alert').onairslider();
        });
    </script>

<?php } ?>
