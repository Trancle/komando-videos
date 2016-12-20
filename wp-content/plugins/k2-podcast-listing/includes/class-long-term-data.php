<?php

namespace K2\Podcast;

class LongTermData {

    protected static $long_term_data_key = "k2-podcast-listing-long-term-data";
    protected static $long_term_data = false;
    
    public static function init(){
        self::refresh_data();
    }

    public static function clear_all_long_term_data(){
        update_option(self::$long_term_data_key, "");
    }

    public static function move_object_position($type, $id, $direction){
        if(!isset(LongTermData::$long_term_data[$type])){
            return false;
        }

        $operation = 1;
        if($direction == "up"){
            $operation = -1;
        }

        $swap = false;
        $swap_key = false;
        foreach(LongTermData::$long_term_data[$type] as $key => $data){
            if($data['id'] == $id){

                if(!isset(LongTermData::$long_term_data[$type][$key + $operation])){
                    return false;
                }

                $swap = $data;
                $swap_key = $key;
                break;
            }
        }

        if($swap){
            LongTermData::$long_term_data[$type][$swap_key] = LongTermData::$long_term_data[$type][$swap_key + $operation];
            LongTermData::$long_term_data[$type][$swap_key + $operation] = $swap;
            self::save();
            return true;
        }

        return false;
    }

    public static function swap_position($type, $old_index, $new_index){
        if(!isset(LongTermData::$long_term_data[$type])){
            return false;
        }

        if(isset(LongTermData::$long_term_data[$type][$old_index]) && isset(LongTermData::$long_term_data[$type][$new_index])){
            $swap = LongTermData::$long_term_data[$type][$old_index];
            LongTermData::$long_term_data[$type][$old_index] = LongTermData::$long_term_data[$type][$new_index];
            LongTermData::$long_term_data[$type][$new_index] = $swap;
            self::save();
            return true;
        }
        //todo: throw error if not successful
        return false;
    }

    public static function add_object($type, $id, $extra_data = array()){
        if(!isset(self::$long_term_data[$type])){
            self::$long_term_data[$type] = array();
        }

        foreach(self::$long_term_data[$type] as $data){
            if($data['id'] == $id){
                return false;
            }
        }
        
        self::$long_term_data[$type][] = array(
            "id" => $id,
            "data" => $extra_data
        );

        self::save();
        return true;
    }

    public static function save(){
        if(!self::$long_term_data){
            return false;
        }
        update_option(self::$long_term_data_key, self::$long_term_data);
        return true;
    }

    public static function get_object_value($type, $id, $field){
        foreach(self::get_data($type) as $key => $data){
            if($data['id'] == $id){
                return self::$long_term_data[$type][$key]['data'][$field];
            }
        }
        return false;
    }

    public static function get_object_data($type, $id){
        foreach(self::get_data($type) as $key => $data){
            if($data['id'] == $id){
                return self::$long_term_data[$type][$key]['data'];
            }
        }
        return false;
    }

    public static function update_object($type, $id, $field, $value){
        $changed = false;
        foreach(self::get_data($type) as $key => $data){
            if($data['id'] == $id){
                self::$long_term_data[$type][$key]['data'][$field] = $value;
                $changed = true;
                break;
            }
        }
        self::save();
        return $changed;
    }

    public static function remove_object_field($type, $id, $field){
        foreach(self::get_data($type) as $key => $data){
            if($data['id'] == $id){
                unset(self::$long_term_data[$type][$key]['data'][$field]);
                self::save();
                return true;
            }
        }
        return false;
    }

    public static function update_all_objects($type, $data){
        self::$long_term_data[$type] = $data;
        self::save();
    }
    
    public static function remove_object($type, $id){
        if(isset(self::$long_term_data[$type])){
            foreach(self::get_data($type) as $key => $data){
                if($data['id'] == $id){
                    unset(self::$long_term_data[$type][$key]);
                    self::$long_term_data[$type] = array_values(self::$long_term_data[$type]);
                    self::save();
                    return true;
                }
            }
        }
        return false;
    }
    
    public static function refresh_data(){
        self::$long_term_data = get_option(self::$long_term_data_key);
        if(empty(self::$long_term_data)){
            self::$long_term_data = array();
            self::save();
            return true;
        }
        return false;
    }

    public static function get_data($type = false){
        if(!self::$long_term_data){
            self::refresh_data();
        }

        if(!$type || !isset(self::$long_term_data[$type])){
            return (array) self::$long_term_data;
        }
        elseif(isset(self::$long_term_data[$type])){
            return (array) self::$long_term_data[$type];
        }
        else{
            return false;
        }
    }

}