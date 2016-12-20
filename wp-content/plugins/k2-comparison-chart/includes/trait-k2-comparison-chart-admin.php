<?php
/**
 * Plugin Name: K2 Comparison Chart
 * Plugin URI: http://www.komando.com
 * Description: Allows creation of a comparison chart within an article or page.
 * Version 0.1
 * Author: Yossi Wolfe
 * Date: 5/19/2016
 * Time: 9:42 AM
 *
 * File: k2-comparison-chart.php
 * Class to add a section in the Edit Article page (post.php) for adding
 * a comparison chart on the article page itself
 * 
 * This is the administrative display code
 */

/**
 * Meta box HTML
 */
trait K2_Comparison_Chart_Admin
{
    /* todo: remove below and replace with actual functions */

    public $header_types = array();
    public $comparison_chart_data;
    public $cc_rows;
    public $cc_options;

    public function add_header_type($header_type){
        $this->header_types[$header_type] = $header_type;
        return true;
    }

    public function header_type_exists($header_type){
        return isset($this->header_types[$header_type]);
    }

    public function add_header($header_name, $header_type){
        if($this->header_type_exists($header_type)){
            $this->cc_options["headers"][] = array("name" => $header_name, "type" => $header_type);
            return true;
        }
        return false;
    }

    public function comparison_chart_meta_box()
    {
        global $post;

        /* comparison_chart_data holds two objects: rows and options
         * contained within the options are the headers which are user customized and can be of the following
         * item types: image, number or text
         * */
        $this->add_header_type("image");
        $this->add_header_type("number");
        $this->add_header_type("text");

        // Convert the json encoded string into a multi-dimensional array
        $this->comparison_chart_data = json_decode(str_replace('<br \/>', "\\n", base64_decode(get_post_meta($post->ID, 'comparison_chart', true))),true);
        if(!isset($this->comparison_chart_data["rows"])){
            $this->comparison_chart_data["rows"] = array();
        }
        if(!isset($this->comparison_chart_data["options"])){
            $this->comparison_chart_data["options"] = array();
        }
        $this->cc_rows = &$this->comparison_chart_data["rows"];
        $this->cc_options = &$this->comparison_chart_data["options"];
        $this->cc_options["header_types"] = $this->header_types;
        if(!isset($this->comparison_chart_data["options"]["chart_enabled"])){
            $this->comparison_chart_data["options"]["chart_enabled"] = true;
        }

        if ((!is_array($this->cc_rows)) OR (count($this->cc_rows) < 1)) {
            $this->cc_rows = array(0 => array(
                'row_id' => time(),
                'row_sort_priority' => 0,
                'cols' => array()
            ));
            if(is_array($this->cc_options["headers"])){
/*                foreach($this->cc_options["headers"] as $key => $header){
                    if($this->header_type_exists($header['type'])){
                        $this->cc_rows[0]['cols'][$header_name]['value'] = '';
                        $this->cc_rows[0]['cols'][$header_name]['type'] = $header['type'];
                    }
                }
*/
            }
            else{
                $this->add_header('Name', 'text');
                $this->cc_rows[0]["cols"][0]['Name']['value'] = '';
                $this->cc_rows[0]["cols"][0]['Name']['type'] = 'text';
            }
        }

        //JSON encode the comparison chart for AngularJS
        $comparison_chart_json = json_encode($this->comparison_chart_data);

        ?>

        <script>

            angular.module("articleResponsiveComparisonChart", ['ngSanitize']).controller("articleResponsiveComparisonChartCtrl", function($scope) {

                $scope.new_header_name_value = '';
                $scope.new_header_type_value = '';

                //the wordpress media selection frame
                $scope.frame = wp.media({
                    title: 'Select or Upload an Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });

                //this stores all of the chart data
                $scope.chart_data = <?=$comparison_chart_json?>;
                for(var row_num in $scope.chart_data.rows){
                    for(var col_num in $scope.chart_data.rows[row_num].cols){
                        var key_name = Object.keys($scope.chart_data.rows[row_num].cols[col_num])[0];
                        if("number" == $scope.chart_data.rows[row_num].cols[col_num][key_name].type){
                            $scope.chart_data.rows[row_num].cols[col_num][key_name].value = parseInt($scope.chart_data.rows[row_num].cols[col_num][key_name].value);
                        }
                    }
                }

                    //delete an row from the charts object
                $scope.delete_row = function(index_num, silent_mode){
                    var actually_delete = true;
                    if(!silent_mode){
                        var actually_delete = confirm("Do you really want to delete row " + (index_num + 1) + "?");
                    }
                    if(actually_delete){
                        $scope.chart_data.rows.splice(index_num,1);
                        if($scope.chart_data.rows.length < 1){
                            $scope.chart_data = '';
                        }
                    }
                };

                //change the sorting priority of a row in the charts object
                $scope.move_row_location = function(index_num, direction){
                    current_item = $scope.chart_data.rows[index_num];
                    current_sort_priority = current_item.row_sort_priority;

                    if(direction == "up"){
                        $scope.chart_data.rows[index_num] = $scope.chart_data.rows[index_num - 1];
                        $scope.chart_data.rows[index_num - 1] = current_item;
                        $scope.chart_data.rows[index_num - 1].row_sort_priority = $scope.chart_data.rows[index_num].row_sort_priority;
                        $scope.chart_data.rows[index_num].row_sort_priority = current_sort_priority;

                    }
                    else if(direction == "down"){
                        $scope.chart_data.rows[index_num] = $scope.chart_data.rows[index_num + 1];
                        $scope.chart_data.rows[index_num + 1] = current_item;
                        $scope.chart_data.rows[index_num + 1].row_sort_priority = $scope.chart_data.rows[index_num].row_sort_priority;
                        $scope.chart_data.rows[index_num].row_sort_priority = current_sort_priority;
                    }
                };

                $scope.move_column_location = function(index_num, direction){
                    var current_row_item;
                    var current_item = $scope.chart_data.options.headers[index_num];

                    if(direction == "up"){
                        $scope.chart_data.options.headers[index_num] = $scope.chart_data.options.headers[index_num - 1];
                        $scope.chart_data.options.headers[index_num - 1] = current_item;
                        for(var i in $scope.chart_data.rows){
                            current_row_item = $scope.chart_data.rows[i].cols[index_num];
                            console.log($scope.chart_data.rows[i].cols)
                            $scope.chart_data.rows[i].cols[index_num] = $scope.chart_data.rows[i].cols[index_num - 1];
                            $scope.chart_data.rows[i].cols[index_num - 1] = current_row_item;
                        }
                    }
                    else if(direction == "down"){
                        $scope.chart_data.options.headers[index_num] = $scope.chart_data.options.headers[index_num + 1];
                        $scope.chart_data.options.headers[index_num + 1] = current_item;
                        for(var i in $scope.chart_data.rows){
                            current_row_item = $scope.chart_data.rows[i].cols[index_num];
                            $scope.chart_data.rows[i].cols[index_num] = $scope.chart_data.rows[i].cols[index_num + 1];
                            $scope.chart_data.rows[i].cols[index_num + 1] = current_row_item;
                        }
                    }

                };

                $scope.update_image_url = function (index_num, header, attachment){ //chart_data
                    var image_data = {};
                    image_data.url = attachment.url;
                    image_data.width = attachment.width;
                    image_data.height = attachment.height;
                    $scope.chart_data.rows[index_num].cols[$scope.get_header_id_by_name(header)][header].value = image_data.url.replace(/http:|https:/g,"");
                    $scope.chart_data.rows[index_num].cols[$scope.get_header_id_by_name(header)][header].width = image_data.width;
                    $scope.chart_data.rows[index_num].cols[$scope.get_header_id_by_name(header)][header].height = image_data.height;
                };

                $scope.get_header_id_by_name = function(name){
                    for(i in $scope.chart_data.options.headers){
                        if($scope.chart_data.options.headers[i].name == name){
                            return i;
                        }
                    }
                    return false;
                };

                $scope.get_wp_image_url = function(index_num, header){
                    $scope.current_upload_index_num = index_num;
                    // When an image is selected in the media frame...
                    $scope.frame.on( 'select', function() {
                        // Get media attachment details from the frame state
                        var attachment = $scope.frame.state().get('selection').first().toJSON();
                        // Send the attachment URL to our custom image input field.
                        $scope.update_image_url($scope.current_upload_index_num, header, attachment);
                        $scope.$apply();
                        $scope.current_upload_index_num = null;

                    });

                    // Finally, open the modal on click
                    $scope.frame.open();
                };

                $scope.delete_header = function(header){
                    if((header in $scope.chart_data.options.headers)) {
                        for(var i in $scope.chart_data.rows){
                            $scope.chart_data.rows[i].cols.splice(header,1);
                        }
                        $scope.chart_data.options.headers.splice(header, 1);
                    }
                };

                $scope.add_header = function(){

                    exists = false;
                    for(i in $scope.chart_data.options.headers){
                        if($scope.new_header_name_value == $scope.chart_data.options.headers[i].name){
                            exists = true;
                        }
                    }

                    if(!exists){

                        for(var i in $scope.chart_data.rows){
                            if(typeof $scope.chart_data.rows[i].cols == "undefined"){
                                $scope.chart_data.rows[i].cols = new Array();
                            }
                            $scope.chart_data.rows[i].cols[$scope.chart_data.options.headers.length] = {};
                            $scope.chart_data.rows[i].cols[$scope.chart_data.options.headers.length][$scope.new_header_name_value] = {};
                            $scope.chart_data.rows[i].cols[$scope.chart_data.options.headers.length][$scope.new_header_name_value]["value"] = '';
                            $scope.chart_data.rows[i].cols[$scope.chart_data.options.headers.length][$scope.new_header_name_value]["type"] = $scope.new_header_type_value;
                        }

                        $scope.chart_data.options.headers.push(
                            {type: $scope.new_header_type_value, name: $scope.new_header_name_value}
                        );

                        $scope.new_header_name_value = "";
                        $scope.new_header_type_value = "";
                    }
                    else{
                        alert("Header name already exists.");
                    }
                };

                $scope.add_new_row = function(){
                    next_sort_priority = $scope.chart_data.rows.length;
                    var new_row = {};
                    new_row["row_id"] = (Math.floor(Date.now()/1000));
                    new_row["row_sort_priority"] = next_sort_priority;
                    new_row["cols"] = new Array();
                    for(var i in $scope.chart_data.options.headers){
                        new_row["cols"][i] = {};
                        new_row["cols"][i][$scope.chart_data.options.headers[i].name] = {};
                        new_row["cols"][i][$scope.chart_data.options.headers[i].name].value = '';
                        new_row["cols"][i][$scope.chart_data.options.headers[i].name].type = $scope.chart_data.options.headers[i].type;
                    }
                    $scope.chart_data.rows.push(
                        new_row
                    );
                };

                $scope.intify = function(value){
                    return parseInt(value);
                };

                $scope.read_csv_data = function(){
                    $scope.file_reader.readAsText($scope.loaded_file);
                };

                $scope.import_csv_data = function(){
                    var temp_data = $scope.loaded_file_content.split("\n");
                    if(Array.isArray(temp_data) && temp_data[0].indexOf(",") > 0){
                        var headers = temp_data[0];
                        headers = headers.split(",");

                        for(var i = $scope.chart_data.options.headers.length; i >= 0; i--){
                            $scope.delete_header(i);
                        }

                        for(i in headers){
                            headers[i] = headers[i].split("~");
                            $scope.new_header_name_value = headers[i][0];
                            if(headers[i].length > 1){
                                $scope.new_header_type_value = headers[i][1];
                            }
                            else{
                                $scope.new_header_type_value = "text";
                            }
                            $scope.add_header();
                        }

                        for(i = $scope.chart_data.rows.length; i > 0; i--){
                            $scope.delete_row(i, true);
                        }

                        var new_row;
                        var row_data;
                        var header_name;
                        var counter = 0;

                        for(i = 1; i < temp_data.length; i++){
                            if(temp_data[i] == ""){
                                continue;
                            }
                            temp_data[i] = temp_data[i].replace(/,"/gi, ",[#]");
                            temp_data[i] = temp_data[i].replace(/",/gi, "[/#],");
                            temp_data[i] = temp_data[i].trim();
                            if(temp_data[i][temp_data[i].length - 1] == '"'){
                                temp_data[i] = temp_data[i].substring(0, temp_data[i].length - 1);
                                temp_data[i] += "[/#]";
                            }
                            var str_quoted = temp_data[i].match(/\[#\].*?\[\/#\]/g);
                            var temp_quoted;

                            for(j in str_quoted){
                                temp_quoted = str_quoted[j].replace(/,/gi, "[#U-COMMA]");
                                temp_quoted = temp_quoted.replace("[#]", '');
                                temp_quoted = temp_quoted.replace("[/#]", '');
                                temp_data[i] = temp_data[i].replace(str_quoted[j], temp_quoted);
                            }

                            row_data = temp_data[i].split(",");
                            if(i > 1){
                                $scope.add_new_row();
                            }
                            new_row = ($scope.chart_data.rows.length - 1);
                            for(j in $scope.chart_data.rows[new_row].cols){
                                header_name = Object.keys($scope.chart_data.rows[new_row].cols[j])[0];
                                $scope.chart_data.rows[new_row].cols[j][header_name].value = row_data[j].replace(/\[\#U-COMMA\]/gi, ",").replace(/u00a0/gi, " ");
                                if("number" == $scope.chart_data.rows[new_row].cols[j][header_name].type){
                                    $scope.chart_data.rows[new_row].cols[j][header_name].value = parseInt($scope.chart_data.rows[new_row].cols[j][header_name].value);
                                }
                            }
                        }

                    }
                    $scope.$apply();
                };

                $scope.file_reader = new FileReader();
                $scope.loaded_file = false;
                $scope.loaded_file_content = false;
                $scope.file_exists = false;
                $scope.finished_importing = false;
                $scope.import_original_label = "Import CSV Chart Data";
                $scope.import_done_label = "Done Importing!";
                $scope.import_label = $scope.import_original_label;
                jQuery('#file_to_upload').change( function(event){
                    $scope.loaded_file = false;
                    $scope.loaded_file_content = false;
                    $scope.file_exists = false;
                    $scope.finished_importing = false;
                    $scope.import_label = $scope.import_original_label;
                    if (window.File && window.FileReader && window.FileList && window.Blob) {
                        $scope.loaded_file = event.target.files[0];
                        if ($scope.loaded_file) {
                            $scope.file_reader.onload = function(e) {
                                $scope.loaded_file_content = e.target.result;
                                $scope.import_csv_data();
                                $scope.import_label = $scope.import_done_label;
                                $scope.finished_importing = true;
                                $scope.$apply();
                            };
                        }
                        $scope.file_exists = true;
                        $scope.$apply();
                    }
                });

            });

            angular.element(document).ready(function() {
                angular.bootstrap(document.getElementById("articleResponsiveComparisonChart"), ['articleResponsiveComparisonChart']);
            });
        </script>

        <style>
            .o-flow-a {
                overflow: auto;
                max-width: 100%;
                display: block;
            }
            .col-100-pc {
                width: 100%;
            }
            .col-50-pc {
                display: block;
                width: 45%;
                padding: 10px;
                margin-right: 10px;
                float:left;
            }
            .col-3rd-pc {
                display: block;
                width: 30%;
                padding: 10px;
                margin-right: 10px;
                float:left;
            }
            .header-box {
                border: 1px solid lightgrey;
                width: 80%;
                height: auto;
                padding: 15px;
                padding-top: 5px;
                border-radius: 5px;
            }
            .header-even-row .header-row div {
                background-color: lightgrey;
            }
            .header-odd-row .header-row div {
                background-color: darkgrey;
            }
            .header-row div {
                height:30px;
            }
            .header-row {
                height:50px;
                clear: both;
            }
            .add-new-header-box{
                clear: both;
                display: block;
                height: 200px;
                background-color: lightgrey;
                border: 1px solid darkgrey;
                padding: 20px;
            }
            .centered-text {
                text-align: center;
            }
            .k2-comparison-chart-image-display {
                width: 150px;
            }
            .k2-comparison-chart-number-input {
                width: 75px;
            }

            .charts-csv-file-box{
                border: 1px solid lightgrey;
                width: 40%;
                height: auto;
                padding: 15px;
                padding-top: 15px;
                border-radius: 5px;
                font-family: arial;
                margin: auto;
            }
            .charts-import-csv-file-box {
                clear: both;
                display: block;
                height: 50px;
                background-color: lightgrey;
                border: 1px solid darkgrey;
                padding: 20px;
            }
        </style>

        <div id="articleResponsiveComparisonChart" ng-controller="articleResponsiveComparisonChartCtrl">
            <table class="col-100-pc o-flow-a">
                <tr>
                    <td><strong>Row</strong></td>
                    <td ng-repeat="(header_key, header) in chart_data.options.headers"><div class="col-50-pc"><strong>{{header.name}}</strong></div></td>
                </tr>
                <tr ng-repeat="(row_key, row) in chart_data.rows">
                    <td>
                        <strong>{{$index + 1}}</strong>
                        <input type="button" ng-hide="$first" value="<" ng-click="move_row_location(row_key, 'up')" />
                        <input type="button" ng-hide="$last" value=">" ng-click="move_row_location(row_key, 'down')" />
                        <input type="button" value="X" ng-click="delete_row(row_key, false)" />
                    </td>
                    <td ng-repeat="(col_key, col) in row.cols">
                <span ng-repeat="(col_name, col_data) in col">
                    <div ng-if="col_data.type=='image'">
                        <img class="k2-comparison-chart-image-display" ng-src="{{col_data.value}}" /><br />
                        <input class="k2-comparison-chart-image-input" type="text" ng-model="col_data.value" /><br />
                        <input type="button" ng-click="get_wp_image_url(row_key, col_name)" value="Select Image" />
                    </div>
                    <input ng-if="col_data.type=='number'" class="k2-comparison-chart-number-input" type="number" ng-model="col_data.value" />
                    <textarea cols="10" rows="6" ng-if="col_data.type=='text'" class="k2-comparison-chart-text-input" ng-model="col_data.value"></textarea>
                </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="42" class="centered-text"><input type="button" value="+ Add New Row" ng-click="add_new_row()" /></td>
                </tr>
                </table>
                <table class="col-100-pc">
                <tr>
                    <td colspan="42" class="header-box">
                        <h1 class="header-box-title">Chart Columns</h1>
                        <div class="chart_enabled_status">
                            <label for="chart_enabled">Use Chart </label> <input name="chart_enabled" type="checkbox" ng-model="chart_data.options.chart_enabled" />
                        </div>
                        <strong class="col-3rd-pc">Column Name</strong><strong class="col-3rd-pc">Column Type</strong><strong class="col-3rd-pc">&nbsp;</strong>
                        <div ng-repeat="(header, header_type) in chart_data.options.headers" ng-class-odd="'header-odd-row'" ng-class-even="'header-even-row'">
                            <div class="header-row">
                                <div class="col-3rd-pc">
                                    <input type="button" ng-hide="$first" value="<" ng-click="move_column_location(header, 'up')" />
                                    <input type="button" ng-hide="$last" value=">" ng-click="move_column_location(header, 'down')" />
                                    <input type="text" ng-model="chart_data.options.headers[header].name" />
                                </div>
                                <div class="col-3rd-pc">
                                    {{chart_data.options.headers[header].type}}
                                </div>
                                <div class="col-3rd-pc">
                                    <input type="button" value="Delete" ng-click="delete_header(header)" />
                                </div>
                            </div>
                        </div>
                        <br /><hr /><br />
                        <div class="add-new-header-box">
                            <h2 class="header-box-title">Add New Column</h2>
                            <div class="col-50-pc">Name:</div><div class="col-50-pc"><input type="text" ng-model="new_header_name_value" /></div>
                            <div class="col-50-pc">Type:</div><div class="col-50-pc">
                                <select ng-model="new_header_type_value">
                                    <option ng-repeat="type in chart_data.options.header_types">{{type}}</option>
                                </select></div>
                            <div class="col-50-pc">&nbsp;</div><div class="col-50-pc"><input ng-hide="new_header_name_value == '' || new_header_type_value == ''" type="button" value="Add Column" ng-click="add_header()" /></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="charts-csv-file-box">
                            <h1>Upload a CSV chart file</h1>
                            <p>Header name format: 'Price&#126;text' or 'Image&#126;image' or 'Weight&#126;number'; Default is 'text' if no type is specified</p>
                            <p>For type 'image', the values should be image urls. For type 'text', the values can be any text. For type 'number', the values should be integers.</p>
                            <div class="charts-import-csv-file-box">
                                <input type="file" id="file_to_upload" ng-model="file_to_upload" />
                                <input ng-show="file_exists" ng-disabled="finished_importing" ng-click="read_csv_data()" type="button" value="{{import_label}}" />
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="chart_data_json" value="{{chart_data | json}}" />
        </div>


        <?php
    }

    /**
     * Save the meta box data
     */
    public function comparison_chart_meta_save_details($post_id)
    {
        global $post;

        // to prevent metadata or custom fields from disappearing...
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $chart_data = $_POST['chart_data_json'];
        if(empty($chart_data) || $chart_data == "[]") return false;

        //store the json string containing the chart object in the comparison_chart meta tag
        update_post_meta($post->ID, 'comparison_chart', $chart_data);

        $chart_data = json_decode(get_post_meta($post->ID, 'comparison_chart', true),true);

        $empty = false;
        if(!isset($chart_data["rows"])) return false;
        foreach($chart_data["rows"] as $row_key => $row){
            foreach($row["cols"] as $col_key => $col){
                foreach($col as $col_name => $col_contents){
                    if(sizeof($chart_data["rows"]) == 1 && sizeof($row["cols"]) == 1 && empty($col_contents["value"])){
                        $empty = true;
                    }
                    $chart_data["rows"][$row_key]["cols"][$col_key][$col_name]["value"] = str_replace('"', "'", $col_contents["value"]);
                    $chart_data["rows"][$row_key]["cols"][$col_key][$col_name]["value"] = str_replace('u00a0', " ", $col_contents["value"]);
                    $chart_data["rows"][$row_key]["cols"][$col_key][$col_name]["value"] = str_replace("\n", '<br />', $col_contents["value"]);
                }
            }
        }

        $chart_data = base64_encode(json_encode($chart_data));
        if($empty) $chart_data = "[]";
        update_post_meta($post->ID, 'comparison_chart', $chart_data);



   }
}