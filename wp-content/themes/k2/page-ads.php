<?php
/**
 * Template Name: Ads Instant Article
 */

// finds the last URL segment
$unit   =  urlencode($wp_query->query_vars['unit']);
$width  =  intval($wp_query->query_vars['width']);
$height =  intval($wp_query->query_vars['height']);
$google_publisher_id = "1064811";
$ad_unit_div_id = "div-gpt-ad-1460505143125-0";
?>

<head>
    <script type='text/javascript'>
        var googletag = googletag || {};
        googletag.cmd = googletag.cmd || [];
        (function() {
            var gads = document.createElement('script');
            gads.async = true;
            gads.type = 'text/javascript';
            var useSSL = 'https:' == document.location.protocol;
            gads.src = (useSSL ? 'https:' : 'http:') +
                '//www.googletagservices.com/tag/js/gpt.js';
            var node = document.getElementsByTagName('script')[0];
            node.parentNode.insertBefore(gads, node);
        })();

        googletag.cmd.push(function() {
            googletag.defineSlot('/<?php echo $google_publisher_id; ?>/<?php echo $unit; ?>', [<?php echo $width; ?>, <?php echo $height; ?>], '<?php echo $ad_unit_div_id; ?>').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.enableServices();
        });
    </script>
</head>
<article>


        <!-- /1064811/fb-instant-article-content-1 -->
        <div id='<?php echo $ad_unit_div_id; ?>' style='height:<?php echo $height; ?>px; width:<?php echo $width; ?>px;'>
            <script type='text/javascript'>
                googletag.cmd.push(function() { googletag.display('<?php echo $ad_unit_div_id; ?>'); });
            </script>
        </div>
</article>
