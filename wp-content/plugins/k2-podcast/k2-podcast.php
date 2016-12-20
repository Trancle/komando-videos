<?php
/*
Plugin Name: K2 Podcast
Plugin URI: http://www.komando.com
Description: Show the video.
Author: Lisdanay Dominguez
Version: 0.1
Author URI: http://www.komando.com
*/

add_shortcode( 'podcast_player', 'k2_podcast_player_short_code' );
function k2_podcast_player_short_code($atts) {
    $episode_id        = $atts['id'];
    $podcast_title     = $atts['title'];

    $episode_anchor_id = "id-" . $episode_id;
    $download_link = "http://podcast.komando.com/episode/$episode_id/download.mp3";

    $podcast = '<style>
                .wrapper ul li .rf-info { 
                    min-height: auto; 
                }
                .default-bar { 
                    cursor:pointer; 
                }
                .featured-player-wrapper ul li { 
                    border-top: none;  
                }
                .featured-player-wrapper ul li .rf-listen .featured-player-listen { 
                    margin: 5px 0; 
                }
                .featured-player-wrapper ul li .rf-listen .featured-player-runtime { 
                    margin: 5px 8px; 
                }  
                </style> ';

  //If modifying this section, ensure that it does not break the replacePodcastPlayer() function in app/wp-content/plugins/publish-to-apple-news/includes/apple-exporter/class-exporter-content.php
    $podcast .= '<div class="featured-player-wrapper clearfix">
                    <ul>
                        <li class="listen-on-demand-player-root">                             
                            <a name="id-' . $episode_anchor_id . '" class="header-offset-anchor"></a> 
                            <div class="rf-info"> 
                                <h3 class="featured-player-text">' . $podcast_title. '</h3> 
                            </div>
                            <div class="featured-download"><a href="' . $download_link . '">
                                <span class="featured-player-download-button">DOWNLOAD <i class="fa fa-download hide-mobile"></i></span></a>
                            </div>
                            <div class="rf-listen">
                                <div class="featured-player audio-player">
                                <audio id="podcast' . $episode_anchor_id . '" class="featured-player-source" preload="preload">
                                    <source src="' . $download_link . '" type="audio/mp3">
                                </audio>
                                <span class="featured-player-play-button audio-player-play" id="audio-player-play"><i class="fa fa-play"></i></span>
                                <span class="featured-player-stop-button audio-player-stop" style="display:none;"><i class="fa fa-pause"></i></span>
                                <span class="featured-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span>
                            </div>
                            <span class="featured-player-listen">Listen</span>
                            
                            <div class="default-bar">
                                <div class="progress-bar"></div>
                            </div>
                            <span class="featured-player-runtime"><span class="current-index">0:00</span> / <span class="duration" id="podcast_duration"></span></span>
                            </div> 
                        </li>
                    </ul>
                </div>';


    $podcast .= '<script>
                $(document).ready(function(){
                    var duration = 0;
                    var audio = $("audio"); 
                     
                    audio.bind("loadend", function (e) {
                        e.preventDefault();
                    });
                    
                    audio.bind("canplay",function(e){
                        duration    = e.currentTarget.duration;                            
                        var hours   = Math.floor(duration / 3600);
                        var minutes = Math.floor((duration - (hours * 3600)) / 60);
                        var seconds = duration - (hours * 3600) - (minutes * 60);
        
                        if (hours   < 10) {hours   = "0"+hours;}
                        if (minutes < 10) {minutes = "0"+minutes;}
                        if (seconds < 10) {seconds = "0"+seconds;}
                        var podcast_duration    = hours+\':\'+minutes+\':\'+Math.floor(seconds);
                          
                        $("span#podcast_duration").html(podcast_duration);  
                    });
                    
                    $("div.default-bar").click(function (e) {                       
                        var width    = $(e.currentTarget).width();      
                        var position = e.pageX - $(e.currentTarget).offset().left;  
                        var percentage_div   = (position / width) * 100;                                 
                        var percentage_audio = (percentage_div /100)* duration;                         
                        audio[0].currentTime = percentage_audio;   
                    });
                    
                });
                </script>';

    return $podcast;
}

