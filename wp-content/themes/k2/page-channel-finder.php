<?php 

/*
Template Name: Full Width
*/

get_header(); ?>
	
	<section class="content-full channel-finder-wrapper clearfix" role="main">

		<div class="channel-finder-loader" style="display: none;"><h1>Loading...</h1><br /><img src="<?php echo k2_get_static_url('v2') . '/img/www/channel-finder/loading-bars.svg'; ?>" alt="Loading" /></div>
		<div class="channel-finder-invalid">
			<h1>Search for free HDTV channels in your area</h1>
			<form class="invalid-zip"><input type="text" name="zip" placeholder="Enter your zip code"><button>View your channels</button></form>
		</div>
		<div class="channel-finder-wrapper" style="display: none;">
			<h1>There are <span class="channel-count"></span> free HDTV channels in your area</h1>
			<div class="channel-finder">
				<div class="channel-finder__map"></div>
				<div class="channel-finder__container"></div>
			</div>
		</div>
		<div class="channel-finder-disclaimer">This custom list of channels predicted to be available in your area is an estimate based on the ZIP code you entered and the approximate location of the TV transmitters near that ZIP code. Your ability to receive these predicted channels is not guaranteed. Estimated signal strength is based on distance of tower and your antenna at 18' (feet). It does not adjust for transmitter power, terrain obstructions, curvature of the Earth or any other factors that affect signal availability.</div>
		<script>
			jQuery(document).ready( function($) {

				if ( !document.createElementNS && !/SVGAnimate/.test(toString.call(document.createElementNS('http://www.w3.org/2000/svg', 'animate'))) ){
					$('.channel-finder-loader img').attr('src', '<?php echo k2_get_static_url('v2') . '/img/www/channel-finder/loading-bars.gif'; ?>');
				}

				function get_channels(zip) {
					var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
					var map;
					var circle_one;
					var circle_two;
					var circle_three;

					$.ajax({
						url: ajaxurl,
						data: {action: 'cab_channel_finder', zip: zip},
						dataType: 'json',
						success: function(data) {
							if(data.error) {
								$('.channel-finder-loader').hide();
								$('.channel-finder-invalid input').val(zip);
								$('.channel-finder-invalid h1').html('Invalid zip code, please try again.');
								$('.channel-finder-invalid').show();
								return;
							}

							$('.channel-finder__container').html(data.html);
							$('.channel-count').html(data.stations.length);
							$('.channel-finder-loader').hide();
							$('.channel-finder-wrapper').show();

							var mapOptions = {
								center: new google.maps.LatLng(data.location.lat, data.location.lon),
								zoom: 9
							};

							map = new google.maps.Map(document.getElementsByClassName('channel-finder__map')[0], mapOptions);

							circle_one = new google.maps.Circle({
								strokeWeight: 0,
								fillColor: '#6cbe45',
								fillOpacity: 0.3,
								map: map,
								center: map.center,
								radius: 40 * 1000
							});

							circle_two = new google.maps.Circle({
								strokeWeight: 0,
								fillColor: '#6cbe45',
								fillOpacity: 0.2,
								map: map,
								center: map.center,
								radius: 65 * 1000
							});

							circle_three = new google.maps.Circle({
								strokeWeight: 0,
								fillColor: '#6cbe45',
								fillOpacity: 0.16,
								map: map,
								center: map.center,
								radius: 80 * 1000
							});


							map.setOptions({ styles:
								[
									{ stylers: [
										{lightness: 20},
										{visibility: 'on'},
										{saturation: -100}
									]
									}
								]
							});

							var markers = [];
							$.each(data.stations, function(index, station) {
								markers.push(
									new google.maps.Marker({
										position: new google.maps.LatLng(station.lat, station.lon),
										map: map,
										title: station.callsign,
										icon: "<?php echo k2_get_static_url('v2') . '/img/www/channel-finder/cg-tower.png'; ?>"
									})
								);
							});
						}
					});
				}

				$('.invalid-zip').on('submit', function(e) {
					e.preventDefault();
					var zip = $(this).find('input').val();
					$('.channel-finder-invalid').hide();
					$('.channel-finder-loader').show();
					get_channels(zip);
				});
			});
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyAYwvZ0JITdHh8xHqR7V7XUL7dihp95wv4"></script>

	</section>

<?php get_footer(); ?>