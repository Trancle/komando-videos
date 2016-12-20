<?php

namespace K2\Podcast;

class Admin
{

	/**
     * Admin page
     */
    public function admin_init()
	{

		if(isset($_POST['podcast_data']) && !empty($_POST['podcast_data'])){
			\K2\Podcast\Listing::update_all_podcast_long_term_data($_POST['podcast_data']);
		}

		if(isset($_POST['podcast_show_rss_feed_url']) && !empty($_POST['podcast_show_rss_feed_url'])){
			\K2\Podcast\Listing::add_show($_POST['podcast_show_rss_feed_url']);
		}

		if(isset($_POST['form_action']) && 'delete' == $_POST['form_action'] && isset($_POST['show_id']) && !empty($_POST['show_id'])){
			\K2\Podcast\Listing::remove_show($_POST['show_id']);
		}


		?>

		<div class="wrap metabox-holder" ng-controller="podcastListingConfigurationCtrl">
			<h2>Podcast Listing Configuration</h2><br/>
					<table class="wp-list-table widefat plugins">
						<thead>
							<tr ng-show="unsaved_changes">
								<td colspan="20" class="centered-text alert">
									You have unsaved changes. Please click "Save Data" below in order to save your changes.
								</td>
							</tr>
							<tr>
								<th scope="col" id="name" class="manage-column column-name column-primary">Podcast Show Name</th>
								<th scope="col" class="manage-column column-description" ng-repeat="extra_data_field in get_extra_data_fields()">{{extra_data_field}}</th>
								<th scope="col" id="description" class="manage-column column-description">Description</th>
								<th scope="col" id="sort-priority" class="manage-column column-description">Sort Priority</th>
								<th scope="col" id="sort-priority" class="manage-column column-description">&nbsp;</th>
							</tr>
						</thead>

						<tbody id="the-list">
							<tr>
								<td colspan="20" ng-hide="podcasts_exists() > 0" class="centered-text"><h2>No Shows Exist Yet!</h2></td>
							</tr>
							<tr ng-repeat="show in shows" class="active" k2-change="current_show_index = $index">
								<td class="plugin-title column-primary">
									<strong>{{show.title}}</strong>
								</td>
								<td class="column-description desc" ng-repeat="(field, value) in podcast_long_term_data[current_show_index]['data']">
									<div class="plugin-description">
										<input type="text" ng-model="podcast_long_term_data[current_show_index]['data'][field]" ng-change="set_unsaved_changes(true)" />
									</div>
								</td>
								<td class="column-description desc">
									<div class="plugin-description">
										<p>{{show.description}}</p>
									</div>
								</td>
								<td class="column-description desc">
									<div class="plugin-description sort-priority">
										<p>{{current_show_index + 1}}</p>
										<a ng-click="move_position(current_show_index, 'up')" ng-hide="$first" href="#">Up</a><span ng-hide="$first || $last"> | </span><a ng-click="move_position($index, 'down')" ng-hide="$last" href="#">Down</a>
									</div>
								</td>
								<td class="column-description desc">
									<span class="delete">
										<form method="post" action="">
											<input type="submit" value="Delete" />
											<input type="hidden" value="{{show.id}}" name="show_id" />
											<input type="hidden" value="delete" name="form_action" /></form>
									</span>
								</td>
							</tr>
						</tbody>
						<tfoot>
						<tr>
							<th scope="col" class="manage-column column-name column-primary">Podcast Show Name</th>
							<th scope="col" class="manage-column column-description" ng-repeat="extra_data_field in get_extra_data_fields()">{{extra_data_field}}</th>
							<th scope="col" class="manage-column column-description">Description</th>
							<th scope="col" id="sort-priority" class="manage-column column-description">Sort Priority</th>
							<th scope="col" id="sort-priority" class="manage-column column-description">&nbsp;</th>
						</tr>
						<tr ng-show="unsaved_changes">
							<td colspan="20" class="centered-text alert">
								You have unsaved changes. Please click "Save Data" below in order to save your changes.
							</td>
						</tr>
						</tfoot>

					</table>
			<form method="post" action="" id="podcast_configuration">
				<div class="half-page auto-margins centered-text"><h3><a class="add-new-h2" href="#" ng-click="save_data()">Save Data</a></h3></div>
				<div class="meta-box-sortables ui-sortable">
					<div class="half-page auto-margins add-new-podcast-form" ng-show="show_error_box"><h3>{{form_error}}</h3></div>
					<div class="half-page auto-margins add-new-podcast-form">
						<h2>Add New Podcast Show</h2>
						<div class="col-xs-12">
							<div class="col-xs-6">
								<label>Paste show url here: </label><input type="text" name="podcast_show_rss_feed_url" placeholder="Podcast Show RSS Feed URL" />
							</div>
						</div>
						<span class="add"><a class="add-new-h2" href="#" ng-click="add_show()">Add new show</a></span>
					</div>
				</div>
				<input type="hidden" value="{{podcast_long_term_data | json}}" name="podcast_data" />
			</form>

		</div>
		<script>

			var app = angular.module("podcastListingConfigurationApp", []).directive('k2Change', function () {
				return {
					restrict: 'A',
					link: function postLink(scope, element, attrs) {
						var splits = attrs.k2Change.split("=");
						scope.$watch(splits[1], function (val) {
							scope.$eval(attrs.k2Change);
						});
					}
				};
			}).controller("podcastListingConfigurationCtrl", function($scope, $http) {

				$scope.extra_attributes = JSON.parse('<?php echo php_to_angular_json_encode(\K2\Podcast\Show::extra_attributes()); ?>');
				$scope.shows = JSON.parse('<?php echo php_to_angular_json_encode(\K2\Podcast\Listing::get_all_show_data_only()); ?>');
				$scope.podcast_long_term_data = JSON.parse('<?php echo \K2\Podcast\Listing::get_long_term_show_data_json(); ?>');
				$scope.logo_captions = [];

				$scope.form_error = '';
				$scope.show_error_box = false;
				$scope.raw_xml_content = "";
				$scope.parsed_xml = "";
				$scope.unsaved_changes = false;
				$scope.hide_checkboxes = true;
				$scope.extra_data_fields = false;

				$scope.capitalize_words = function(string){
					var words = string.split(" ");
					var output = "";
					for (i = 0 ; i < words.length; i ++){
						lowerWord = words[i].toLowerCase();
						lowerWord = lowerWord.trim();
						capitalizedWord = lowerWord.slice(0,1).toUpperCase() + lowerWord.slice(1);
						output += capitalizedWord;
						if (i != words.length-1){
							output+=" ";
						}
					}//for
					output[output.length-1] = '';
					return output;
				};

				$scope.get_extra_data_fields = function(){
					if(!$scope.extra_data_fields){
						$scope.extra_data_fields = [];
						for(var i in $scope.extra_attributes){
							i = i.replace(/_/g, " ");
							i = $scope.capitalize_words(i);
							$scope.extra_data_fields.push(i);
						}
					}
					return $scope.extra_data_fields;
				};

				$scope.set_unsaved_changes = function(value){
					$scope.unsaved_changes = value;
				};

				$scope.podcasts_exists = function(){
					if(typeof Object.keys($scope.podcast_long_term_data).length == "undefined"){
						return $scope.podcast_long_term_data.length;
					}
					return Object.keys($scope.podcast_long_term_data).length;
				};

				$scope.array_search = function(arr,val) {
					for (var i=0; i<arr.length; i++)
						if (arr[i] === val)
							return i;
					return false;
				};

				$scope.move_position = function(index, position){

					var operation = 1;
					if("up" == position){
						operation = -1;
					}

					var temp_data = $scope.shows[index + operation];
					$scope.shows[index + operation] = $scope.shows[index];
					$scope.shows[index] = temp_data;

					var temp_val = $scope.podcast_long_term_data[index + operation];
					$scope.podcast_long_term_data[index + operation] = $scope.podcast_long_term_data[index];
					$scope.podcast_long_term_data[index] = temp_val;

					$scope.unsaved_changes = true;
				};

				$scope.add_show = function(){
					$scope.save_data();
				};

				$scope.display_error = function(error){
					$scope.form_error = error;
					$scope.show_error_box = true;
					setTimeout($scope.hide_error, 3000);
				};

				$scope.hide_error = function(){
					$scope.show_error_box = false;
					$scope.form_error = '';
				};

				$scope.save_data = function(){
					document.getElementById("podcast_configuration").submit();
				}

			});
			angular.bootstrap(document, ['podcastListingConfigurationApp']);
		</script>

		<style>
			.alert {
				background-color: #d98500;
				color: white !important;
				font-weight: bold !important;
			}
			.sort-priority {
				width: 100px;
			}
			.auto-margins {
				margin: auto;
			}
			.centered-text {
				text-align: center !important;
			}
			.half-page {
				width: 50%;
			}
			.add-new-podcast-form {
				padding: 20px;
				border: 1px solid #e5e5e5;
				background-color: #ffffff;
				margin-top: 20px;
			}
			.add-new-podcast-form:after{
				clear: both;
				content: "";
				display: block;
			}

			.add-new-podcast-form div div {
				margin: 1%;
			}
			.col-xs-12 {
				width: 98%;
				float: left;
			}
			.col-xs-11 {
				width: 89.66%;
				float: left;
			}
			.col-xs-10 {
				width: 81.33%;
				float: left;
			}
			.col-xs-9 {
				width: 73%;
				float: left;
			}
			.col-xs-8 {
				width: 64.66%;
				float: left;
			}
			.col-xs-7 {
				width: 56.33%;
				float: left;
			}
			.col-xs-6 {
				width: 48%;
				float: left;
			}
			.col-xs-5 {
				width: 39.66%;
				float: left;
			}
			.col-xs-4 {
				width: 31.33%;
				float: left;
			}
			.col-xs-3 {
				width: 23%;
				float: left;
			}
			.col-xs-2 {
				width: 14.66%;
				float: left;
			}
			.col-xs-1 {
				width: 6.33%;
				float: left;
			}
		</style>

		<?php
	}
}
?>