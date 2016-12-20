<?php

namespace K2\Podcast;

trait GetterSetter {

    /**
     * This is a magic method used to create get_, set_ and has_ functions dynamically
     *
     * @param $method string [in]
     * @param $args array [in]
     * @return mixed
     */
    public function __call($method, $args){
        if(strpos($method, "get_") !== false){
            $attribute = str_replace("get_", "", $method);
            return $this->get_attribute($attribute);
        }
        elseif(strpos($method, "has_") !== false){
            $attribute = str_replace("has_", "", $method);
            return is_null($this->get_attribute($attribute));
        }
        elseif(strpos($method, "set_") !== false){
            $attribute = str_replace("set_", "", $method);
            if(isset($args[0])){
                $value = $args[0];
                return $this->set_attribute($attribute, $value);
            }
        }
        return null;
    }

    /**
     * Returns an attribute value
     * @param $attribute string [in] name of an attribute
     * @return mixed
     */
    private function get_attribute($attribute){
        if(!isset($this->attributes()[$attribute])){
            return "";
        }
        return $this->attributes()[$attribute];
    }

    /**
     * Sets an attribute value
     *
     * @param $attribute string [in] name of an attribute
     * @param $value mixed [in] value of an attribute
     * @return bool
     */
    private function set_attribute($attribute, $value){
        $this->attributes[$attribute] = $value;
        return true;
    }
    
}
