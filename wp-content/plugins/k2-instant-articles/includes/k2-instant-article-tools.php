<?php
// Source: https://sourceforge.net/projects/simplehtmldom/ v. 1_5 on 2016-04-26
// The MIT License (MIT)
// Copyright (c) 2016  john_schlick, me578022 (SourceForge Usernames)
// Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
// The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
//
namespace K2 {
    define('HDOM_TYPE_ELEMENT', 1);
    define('HDOM_TYPE_COMMENT', 2);
    define('HDOM_TYPE_TEXT',    3);
    define('HDOM_TYPE_ENDTAG',  4);
    define('HDOM_TYPE_ROOT',    5);
    define('HDOM_TYPE_UNKNOWN', 6);
    define('HDOM_QUOTE_DOUBLE', 0);
    define('HDOM_QUOTE_SINGLE', 1);
    define('HDOM_QUOTE_NO',     3);
    define('HDOM_INFO_BEGIN',   0);
    define('HDOM_INFO_END',     1);
    define('HDOM_INFO_QUOTE',   2);
    define('HDOM_INFO_SPACE',   3);
    define('HDOM_INFO_TEXT',    4);
    define('HDOM_INFO_INNER',   5);
    define('HDOM_INFO_OUTER',   6);
    define('HDOM_INFO_ENDSPACE',7);
    define('DEFAULT_TARGET_CHARSET', 'UTF-8');
    define('DEFAULT_BR_TEXT', "\r\n");
    define('DEFAULT_SPAN_TEXT', " ");
    define('MAX_FILE_SIZE', 600000);

    // close the end tag from string
    function closeTags($html) {
        //Recive all openTags
        $arr_single_tags = array('meta','br','link','area');
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $opened_tags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closed_tags = $result[1];
        $len_opened = count($opened_tags);
        if ( count($closed_tags) == $len_opened ){
            return $html;
        }
        $opened_tags = array_reverse($opened_tags);
        for ( $i = 0; $i < $len_opened; $i++ ){
            if ( !in_array($opened_tags[$i],$arr_single_tags) ){
                if ( !in_array($opened_tags[$i], $closed_tags) ){
                    if ( $next_tag = $opened_tags[$i+1] ){
                        $html = preg_replace('#</'.$next_tag.'#iU','</'.$opened_tags[$i].'></'.$next_tag,$html);
                    }else{
                        $html .= '</'.$opened_tags[$i].'>';
                    }
                }
            }
        }
        return $html;
    }

    // get html dom from string
    function strGetHtml($str, $lowercase=true, $force_tags_closed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $default_span_text=DEFAULT_SPAN_TEXT){
        $dom = new ParseHtmlDom(null, $lowercase, $force_tags_closed, $target_charset, $stripRN, $defaultBRText, $default_span_text);
        if (empty($str) || strlen($str) > MAX_FILE_SIZE){
            $dom->clear();
            return false;
        }
        $dom->load($str, $lowercase, $stripRN);
        return $dom;
    }

    /**
     *  added ability for "find" routine to lowercase the value of the selector.
     *  added $tag_start to track the start position of the tag in the total byte index
     *
     */
    class ParseHtmlDomNode{
        public $nodetype  = HDOM_TYPE_TEXT;
        public $tag       = 'text';
        public $attr      = array();
        public $children  = array();
        public $nodes     = array();
        public $parent    = null;
        public $_         = array();
        public $tag_start = 0;
        private $dom      = null;

        function __construct($dom){
            $this->dom    = $dom;
            $dom->nodes[] = $this;
        }

        function __destruct(){
            $this->clear();
        }

        function __toString(){
            return $this->outertext();
        }

        // clean up memory due to php5 circular references memory leak...
        function clear(){
            $this->dom = null;
            $this->nodes = null;
            $this->parent = null;
            $this->children = null;
        }

        // dump node's tree
        function dump($show_attr=true, $deep=0){
            $lead = str_repeat('    ', $deep);
            echo $lead.$this->tag;
            if ( $show_attr && count($this->attr) > 0 ){
                echo '(';
                foreach ( $this->attr as $k => $v ){
                    echo "[$k]=>\"".$this->$k.'", ';
                }
                echo ')';
            }
            echo "\n";

            if ( $this->nodes ){
                foreach ( $this->nodes as $c ){
                    $c->dump($show_attr, $deep+1);
                }
            }
        }

        // Debugging function to dump a single dom node with a bunch of information about it.
        function dumpNode($echo=true){
            $string = $this->tag;
            if ( count($this->attr) > 0 ){
                $string .= '(';
                foreach ( $this->attr as $k => $v ){
                    $string .= "[$k]=>\"".$this->$k.'", ';
                }
                $string .= ')';
            }
            if ( count($this->_) > 0 ){
                $string .= ' $_ (';
                foreach ( $this->_ as $k => $v ){
                    if ( is_array($v) ){
                        $string .= "[$k]=>(";
                        foreach ( $v as $k2 => $v2 ){
                            $string .= "[$k2]=>\"".$v2.'", ';
                        }
                        $string .= ")";
                    } else {
                        $string .= "[$k]=>\"".$v.'", ';
                    }
                }
                $string .= ")";
            }

            if ( isset($this->text) ){
                $string .= " text: (" . $this->text . ")";
            }

            $string .= " HDOM_INNER_INFO: '";
            if ( isset($node->_[HDOM_INFO_INNER]) ){
                $string .= $node->_[HDOM_INFO_INNER] . "'";
            }
            else{
                $string .= ' NULL ';
            }

            $string .= " children: " . count($this->children);
            $string .= " nodes: " . count($this->nodes);
            $string .= " tag_start: " . $this->tag_start;
            $string .= "\n";

            if ($echo){
                echo $string;
                return;
            }
            else{
                return $string;
            }
        }

        // returns the parent of node
        // If a node is passed in, it will reset the parent of the current node to that one.
        function parent($parent=null){
            // I am SURE that this doesn't work properly.
            // It fails to unset the current node from it's current parents nodes or children list first.
            if ( $parent !== null ){
                $this->parent = $parent;
                $this->parent->nodes[] = $this;
                $this->parent->children[] = $this;
            }
            return $this->parent;
        }

        // verify that node has children
        function hasChild(){
            return !empty($this->children);
        }

        // returns children of node
        function children($idx=-1){
            if ( $idx === -1 ){
                return $this->children;
            }
            if (isset($this->children[$idx])){
                return $this->children[$idx];
            }
            return null;
        }

        // returns the first child of node
        function firstChildNode(){
            if ( count($this->children) > 0 ){
                return $this->children[0];
            }
            return null;
        }

        // returns the last child of node
        function lastChildNode(){
            if ( ( $count = count($this->children) ) > 0 ){
                return $this->children[$count-1];
            }
            return null;
        }

        // returns the next sibling of node
        function nextSiblingNode(){
            if ( $this->parent === null ){
                return null;
            }
            $idx = 0;
            $count = count($this->parent->children);
            while ( $idx < $count && $this !== $this->parent->children[$idx] ){
                ++$idx;
            }
            if ( ++$idx >= $count ){
                return null;
            }
            return $this->parent->children[$idx];
        }

        // returns the previous sibling of node
        function prevSibling(){
            if ($this->parent===null){
                return null;
            }
            $idx = 0;
            $count = count($this->parent->children);
            while ( $idx < $count && $this !== $this->parent->children[$idx] ){
                ++$idx;
            }
            if ( --$idx < 0 ){
                return null;
            }
            return $this->parent->children[$idx];
        }

        // function to locate a specific ancestor tag in the path to the root.
        function findAncestorTag($tag){
            global $debug_object;
            if ( is_object($debug_object) ) {
                $debug_object->debugLogEntry(1);
            }

            // Start by including ourselves in the comparison.
            $returnDom = $this;
            while ( !is_null($returnDom) ){
                if ( is_object($debug_object) ) {
                    $debug_object->debugLog(2, "Current tag is: " . $returnDom->tag);
                }
                if ( $returnDom->tag == $tag ){
                    break;
                }
                $returnDom = $returnDom->parent;
            }
            return $returnDom;
        }

        // get dom node's inner html
        function innertext(){
            if ( isset($this->_[HDOM_INFO_INNER]) ){
                return $this->_[HDOM_INFO_INNER];
            }
            if ( isset($this->_[HDOM_INFO_TEXT]) ){
                return $this->dom->restoreNoise($this->_[HDOM_INFO_TEXT]);
            }

            $ret = '';
            foreach ( $this->nodes as $n ){
                $ret .= $n->outertext();
            }
            return $ret;
        }

        // get dom node's outer text (with tag)
        function outertext(){
            global $debug_object;
            if ( is_object($debug_object) ){
                $text = '';
                if ( $this->tag == 'text' ) {
                    if ( !empty($this->text) ) {
                        $text = " with text: " . $this->text;
                    }
                }
                $debug_object->debugLog(1, 'Innertext of tag: ' . $this->tag . $text);
            }

            if ( $this->tag === 'root' ){
                return $this->innertext();
            }

            // trigger callback
            if ( $this->dom && $this->dom->callback !== null ){
                call_user_func_array($this->dom->callback, array($this));
            }

            if ( isset($this->_[HDOM_INFO_OUTER]) ){
                return $this->_[HDOM_INFO_OUTER];
            }
            if ( isset($this->_[HDOM_INFO_TEXT]) ){
                return $this->dom->restoreNoise($this->_[HDOM_INFO_TEXT]);
            }

            // render begin tag
            if ( $this->dom && $this->dom->nodes[$this->_[HDOM_INFO_BEGIN]] ){
                $ret = $this->dom->nodes[$this->_[HDOM_INFO_BEGIN]]->makeup();
            } else {
                $ret = "";
            }

            // render inner text
            if (isset($this->_[HDOM_INFO_INNER])){
                // If it's a br tag...  don't return the HDOM_INNER_INFO that we may or may not have added.
                if( $this->tag != "br" ){
                    $ret .= $this->_[HDOM_INFO_INNER];
                }
            } else {
                if( $this->nodes ){
                    foreach ( $this->nodes as $n ){
                        $ret .= $this->convertText($n->outertext());
                    }
                }
            }
            // render end tag
            if ( isset($this->_[HDOM_INFO_END]) && $this->_[HDOM_INFO_END] != 0 ){
                $ret .= '</'.$this->tag.'>';
            }
            return $ret;
        }

        // get dom node's plain text
        function text(){
            if ( isset($this->_[HDOM_INFO_INNER]) ) {
                return $this->_[HDOM_INFO_INNER];
            }
            switch( $this->nodetype ){
                case HDOM_TYPE_TEXT: return $this->dom->restoreNoise($this->_[HDOM_INFO_TEXT]);
                case HDOM_TYPE_COMMENT: return '';
                case HDOM_TYPE_UNKNOWN: return '';
            }
            if( strcasecmp($this->tag, 'script') === 0 ) {
                return '';
            }
            if( strcasecmp($this->tag, 'style') === 0 ) {
                return '';
            }

            $ret = '';
            // In rare cases, (always node type 1 or HDOM_TYPE_ELEMENT - observed for some span tags, and some p tags) $this->nodes is set to NULL.
            // NOTE: This indicates that there is a problem where it's set to NULL without a clear happening.
            // WHY is this happening?
            if ( !is_null($this->nodes) ){
                foreach ( $this->nodes as $n ){
                    $ret .= $this->convertText($n->text());
                }
                // If this node is a span... add a space at the end of it so multiple spans don't run into each other.  This is plaintext after all.
                if ($this->tag == "span"){
                    $ret .= $this->dom->default_span_text;
                }
            }
            return $ret;
        }

        function xmltext(){
            $ret = $this->innertext();
            $ret = str_ireplace('<![CDATA[', '', $ret);
            $ret = str_replace(']]>', '', $ret);
            return $ret;
        }

        // build node's text with tag
        function makeup(){
            if ( isset($this->_[HDOM_INFO_TEXT]) ){
                return $this->dom->restoreNoise($this->_[HDOM_INFO_TEXT]);
            }
            $ret = '<'.$this->tag;
            $i = -1;
            foreach ( $this->attr as $key=>$val ){
                ++$i;
                // skip removed attribute
                if ($val===null || $val===false){
                    continue;
                }
                $ret .= $this->_[HDOM_INFO_SPACE][$i][0];
                if ( $val === true ){
                    $ret .= $key;
                } else {
                    switch ($this->_[HDOM_INFO_QUOTE][$i]){
                        case HDOM_QUOTE_DOUBLE: $quote = '"'; break;
                        case HDOM_QUOTE_SINGLE: $quote = '\''; break;
                        default: $quote = '';
                    }
                    $ret .= $key.$this->_[HDOM_INFO_SPACE][$i][1].'='.$this->_[HDOM_INFO_SPACE][$i][2].$quote.$val.$quote;
                }
            }
            $ret = $this->dom->restoreNoise($ret);
            return $ret . $this->_[HDOM_INFO_ENDSPACE] . '>';
        }

        // find elements by css selector
        // added ability for find to lowercase the value of the selector.
        function find($selector, $idx=null, $lowercase=false){
            $selectors = $this->parseSelector($selector);
            if (($count=count($selectors))===0) {
                return array();
            }
            $found_keys = array();

            // find each selector
            for ($c=0; $c<$count; ++$c){
                if ( ($levle=count($selectors[$c])) === 0 ) {
                    return array();
                }
                if ( !isset($this->_[HDOM_INFO_BEGIN]) ){
                    return array();
                }
                $head = array($this->_[HDOM_INFO_BEGIN]=>1);

                // handle descendant selectors, no recursive!
                for ($l=0; $l<$levle; ++$l){
                    $ret = array();
                    foreach ($head as $k=>$v){
                        $n = ($k===-1) ? $this->dom->root : $this->dom->nodes[$k];
                        $n->seek($selectors[$c][$l], $ret, $lowercase);
                    }
                    $head = $ret;
                }
                foreach ( $head as $k=>$v ){
                    if (!isset($found_keys[$k])){
                        $found_keys[$k] = 1;
                    }
                }
            }

            // sort keys
            ksort($found_keys);

            $found = array();
            foreach ($found_keys as $k=>$v){
                $found[] = $this->dom->nodes[$k];
            }

            // return nth-element or array
            if (is_null($idx)){
                return $found;
            }else if ($idx<0) {
                $idx = count($found) + $idx;
            }
            return ( isset($found[$idx]) ) ? $found[$idx] : null;
        }

        // seek for given conditions. Added parameter to allow for case insensitive testing of the value of a selector.
        protected function seek($selector, &$ret, $lowercase=false) {
            global $debug_object;
            if (is_object($debug_object)) {
                $debug_object->debugLogEntry(1);
            }

            list($tag, $key, $val, $exp, $no_key) = $selector;

            // xpath index
            if ($tag && $key && is_numeric($key)){
                $count = 0;
                foreach ($this->children as $c){
                    if ($tag==='*' || $tag===$c->tag) {
                        if (++$count==$key) {
                            $ret[$c->_[HDOM_INFO_BEGIN]] = 1;
                            return;
                        }
                    }
                }
                return;
            }

            $end = (!empty($this->_[HDOM_INFO_END])) ? $this->_[HDOM_INFO_END] : 0;
            if ($end==0) {
                $parent = $this->parent;
                while (!isset($parent->_[HDOM_INFO_END]) && $parent!==null) {
                    $end -= 1;
                    $parent = $parent->parent;
                }
                $end += $parent->_[HDOM_INFO_END];
            }

            for ($i=$this->_[HDOM_INFO_BEGIN]+1; $i<$end; ++$i) {
                $node = $this->dom->nodes[$i];
                $pass = true;
                if ($tag==='*' && !$key) {
                    if (in_array($node, $this->children, true)){
                        $ret[$i] = 1;
                    }
                    continue;
                }

                // compare tag
                if ($tag && $tag!=$node->tag && $tag!=='*') {
                    $pass=false;
                }
                // compare key
                if ($pass && $key) {
                    if ($no_key) {
                        if (isset($node->attr[$key])) {
                            $pass=false;
                        }
                    } else {
                        if (($key != "plaintext") && !isset($node->attr[$key])) {
                            $pass=false;
                        }
                    }
                }
                // compare value
                if ($pass && $key && $val  && $val!=='*') {
                    // If they have told us that this is a "plaintext" search then we want the plaintext of the node - right?
                    if ($key == "plaintext") {
                        $nodeKeyValue = $node->text();
                    } else {
                        // this is a normal search, we want the value of that attribute of the tag.
                        $nodeKeyValue = $node->attr[$key];
                    }
                    if (is_object($debug_object)) {
                        $debug_object->debugLog(2, "testing node: " . $node->tag . " for attribute: " . $key . $exp . $val . " where nodes value is: " . $nodeKeyValue);
                    }

                    // If lowercase is set, do a case insensitive test of the value of the selector.
                    if ($lowercase) {
                        $check = $this->match($exp, strtolower($val), strtolower($nodeKeyValue));
                    } else {
                        $check = $this->match($exp, $val, $nodeKeyValue);
                    }
                    if (is_object($debug_object)) {
                        $debug_object->debugLog(2, "after match: " . ($check ? "true" : "false"));
                    }

                    // handle multiple class
                    if (!$check && strcasecmp($key, 'class')===0) {
                        foreach (explode(' ',$node->attr[$key]) as $k) {
                            if (!empty($k)) {
                                if ($lowercase) {
                                    $check = $this->match($exp, strtolower($val), strtolower($k));
                                } else {
                                    $check = $this->match($exp, $val, $k);
                                }
                                if ($check) break;
                            }
                        }
                    }
                    if (!$check) $pass = false;
                }
                if ($pass) $ret[$i] = 1;
                unset($node);
            }
            // It's passed by reference so this is actually what this function returns.
            if (is_object($debug_object)) {
                $debug_object->debugLog(1, "EXIT - ret: ", $ret);
            }
        }

        protected function match($exp, $pattern, $value) {
            global $debug_object;
            if (is_object($debug_object)) {
                $debug_object->debugLogEntry(1);
            }
            switch ($exp) {
                case '=':
                    return ($value===$pattern);
                case '!=':
                    return ($value!==$pattern);
                case '^=':
                    return preg_match("/^".preg_quote($pattern,'/')."/", $value);
                case '$=':
                    return preg_match("/".preg_quote($pattern,'/')."$/", $value);
                case '*=':
                    if ($pattern[0]=='/') {
                        return preg_match($pattern, $value);
                    }
                    return preg_match("/".$pattern."/i", $value);
            }
            return false;
        }

        protected function parseSelector($selector_string) {
            global $debug_object;
            if (is_object($debug_object)) {
                $debug_object->debugLogEntry(1);
            }

            // Add the colon to the attrbute, so that it properly finds <tag attr:ibute="something" > like google does.
            // Note: if you try to look at this attribute, yo MUST use getAttribute since $dom->x:y will fail the php syntax check.
            // Notice the \[ starting the attbute?  and the @? following?  This implies that an attribute can begin with an @ sign that is not captured.
            // This implies that an html attribute specifier may start with an @ sign that is NOT captured by the expression.
            // farther study is required to determine of this should be documented or removed.

            $pattern = "/([\w-:\*]*)(?:\#([\w-]+)|\.([\w-]+))?(?:\[@?(!?[\w-:]+)(?:([!*^$]?=)[\"']?(.*?)[\"']?)?\])?([\/, ]+)/is";
            preg_match_all($pattern, trim($selector_string).' ', $matches, PREG_SET_ORDER);
            if (is_object($debug_object)) {$debug_object->debugLog(2, "Matches Array: ", $matches);}

            $selectors = array();
            $result = array();
            //print_r($matches);

            foreach ($matches as $m) {
                $m[0] = trim($m[0]);
                if ($m[0]==='' || $m[0]==='/' || $m[0]==='//') continue;
                // for browser generated xpath
                if ($m[1]==='tbody') continue;

                list($tag, $key, $val, $exp, $no_key) = array($m[1], null, null, '=', false);
                if (!empty($m[2])) {
                    $key='id';
                    $val=$m[2];
                }
                if (!empty($m[3])) {
                    $key='class';
                    $val=$m[3];
                }
                if (!empty($m[4])) {
                    $key=$m[4];
                }
                if (!empty($m[5])) {
                    $exp=$m[5];
                }
                if (!empty($m[6])) {
                    $val=$m[6];
                }

                // convert to lowercase
                if ($this->dom->lowercase) {$tag=strtolower($tag); $key=strtolower($key);}
                //elements that do NOT have the specified attribute
                if (isset($key[0]) && $key[0]==='!') {$key=substr($key, 1); $no_key=true;}

                $result[] = array($tag, $key, $val, $exp, $no_key);
                if (trim($m[7])===',') {
                    $selectors[] = $result;
                    $result = array();
                }
            }
            if (count($result)>0){
                $selectors[] = $result;
            }
            return $selectors;
        }

        function __get($name) {
            if (isset($this->attr[$name])){
                return $this->convertText($this->attr[$name]);
            }
            switch ($name) {
                case 'outertext': return $this->outertext();
                case 'innertext': return $this->innertext();
                case 'plaintext': return $this->text();
                case 'xmltext': return $this->xmltext();
                default: return array_key_exists($name, $this->attr);
            }
        }

        function __set($name, $value) {
            switch ($name) {
                case 'outertext': return $this->_[HDOM_INFO_OUTER] = $value;
                case 'innertext':
                    if (isset($this->_[HDOM_INFO_TEXT])){
                        return $this->_[HDOM_INFO_TEXT] = $value;
                    }
                    return $this->_[HDOM_INFO_INNER] = $value;
            }
            if (!isset($this->attr[$name])) {
                $this->_[HDOM_INFO_SPACE][] = array(' ', '', '');
                $this->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_DOUBLE;
            }
            $this->attr[$name] = $value;
        }

        function __isset($name) {
            switch ($name) {
                case 'outertext': return true;
                case 'innertext': return true;
                case 'plaintext': return true;
            }
            return (array_key_exists($name, $this->attr)) ? true : isset($this->attr[$name]);
        }

        function __unset($name) {
            if (isset($this->attr[$name])){
                unset($this->attr[$name]);
            }
        }

        //  Function to convert the text from one character set to another if the two sets are not the same.
        function convertText($text){
            global $debug_object;
            if (is_object($debug_object)) {$debug_object->debugLogEntry(1);}

            $converted_text = $text;
            $source_charset = "";
            $target_charset = "";

            if ($this->dom){
                $source_charset = strtoupper($this->dom->_charset);
                $target_charset = strtoupper($this->dom->_target_charset);
            }
            if (is_object($debug_object)) {$debug_object->debugLog(3, "source charset: " . $source_charset . " target charaset: " . $target_charset);}

            if (!empty($source_charset) && !empty($target_charset) && (strcasecmp($source_charset, $target_charset) != 0)) {
                // Check if the reported encoding could have been incorrect and the text is actually already UTF-8
                if ((strcasecmp($target_charset, 'UTF-8') == 0) && ($this->isUtf8($text)))  {
                    $converted_text = $text;
                }
                else  {
                    $converted_text = iconv($source_charset, $target_charset, $text);
                }
            }

            // Lets make sure that we don't have that silly BOM issue with any of the utf-8 text we output.
            if ($target_charset == 'UTF-8')  {
                if (substr($converted_text, 0, 3) == "\xef\xbb\xbf") {
                    $converted_text = substr($converted_text, 3);
                }
                if (substr($converted_text, -3) == "\xef\xbb\xbf") {
                    $converted_text = substr($converted_text, 0, -3);
                }
            }
            return $converted_text;
        }

        /**
         * Returns true if $string is valid UTF-8 and false otherwise.
         *
         * @param mixed $str String to be tested
         * @return boolean
         */
        static function isUtf8($str){
            $c=0; $b=0;
            $bits=0;
            $len=strlen($str);
            for($i=0; $i<$len; $i++){
                $c=ord($str[$i]);
                if($c > 128){
                    if(($c >= 254)) return false;
                    elseif($c >= 252) $bits=6;
                    elseif($c >= 248) $bits=5;
                    elseif($c >= 240) $bits=4;
                    elseif($c >= 224) $bits=3;
                    elseif($c >= 192) $bits=2;
                    else return false;
                    if(($i+$bits) > $len) return false;
                    while($bits > 1){
                        $i++;
                        $b=ord($str[$i]);
                        if($b < 128 || $b > 191) return false;
                        $bits--;
                    }
                }
            }
            return true;
        }


        /**
         * Function to try a few tricks to determine the displayed size of an img on the page.
         * NOTE: This will ONLY work on an IMG tag. Returns FALSE on all other tag types.
         *
         * @return array an array containing the 'height' and 'width' of the image on the page or -1 if we can't figure it out.
         */
        function getDisplaySize(){
            global $debug_object;
            $width  = -1;
            $height = -1;

            if ( $this->tag !== 'img' ) {
                return false;
            }

            // See if there is aheight or width attribute in the tag itself.
            if ( isset($this->attr['width']) ){
                $width = $this->attr['width'];
            }

            if ( isset($this->attr['height']) ){
                $height = $this->attr['height'];
            }

            // Now look for an inline style.
            if ( isset($this->attr['style']) ){
                // Thanks to user gnarf from stackoverflow for this regular expression.
                $parseAttr = array();
                preg_match_all("/([\w-]+)\s*:\s*([^;]+)\s*;?/", $this->attr['style'], $matches, PREG_SET_ORDER);
                foreach ( $matches as $match ) {
                    $parseAttr[$match[1]] = $match[2];
                }

                // If there is a width in the style parseAttr:
                if ( isset($parseAttr['width']) && $width == -1 ) {
                    // check that the last two characters are px (pixels)
                    if ( strtolower(substr($parseAttr['width'], -2)) == 'px' ){
                        $proposed_width = substr($parseAttr['width'], 0, -2);
                        // Now make sure that it's an integer and not something stupid.
                        if ( filter_var($proposed_width, FILTER_VALIDATE_INT) ){
                            $width = $proposed_width;
                        }
                    }
                }

                // If there is a width in the style parseAttr:
                if ( isset($parseAttr['height']) && $height == -1 ){
                    // check that the last two characters are px (pixels)
                    if ( strtolower(substr($parseAttr['height'], -2)) == 'px' ) {
                        $proposed_height = substr($parseAttr['height'], 0, -2);
                        // Now make sure that it's an integer and not something stupid.
                        if (filter_var($proposed_height, FILTER_VALIDATE_INT)){
                            $height = $proposed_height;
                        }
                    }
                }

            }
            $result = array(
                'height' => $height,
                'width'  => $width
            );
            return $result;
        }

        // camel naming conventions
        function getAllparseAttr() {
            return $this->attr;
        }
        function getAttribute($name) {
            return $this->__get($name);
        }
        function setAttribute($name, $value) {
            $this->__set($name, $value);
        }
        function hasAttribute($name) {
            return $this->__isset($name);
        }
        function removeAttribute($name) {
            $this->__set($name, null);
        }
        function getElementById($id) {
            return $this->find("#$id", 0);
        }
        function getElementsById($id, $idx=null) {
            return $this->find("#$id", $idx);
        }
        function getElementByTagName($name) {
            return $this->find($name, 0);
        }
        function getElementsByTagName($name, $idx=null) {
            return $this->find($name, $idx);
        }
        function parentNode() {
            return $this->parent();
        }
        function childNodes($idx=-1) {
            return $this->children($idx);}
        function firstChild() {
            return $this->firstChildNode();
        }
        function lastChild() {
            return $this->lastChildNode();
        }
        function nextSibling() {
            return $this->nextSiblingNode();
        }
        function previousSibling() {
            return $this->prevSibling();
        }
        function hasChildNodes() {
            return $this->hasChild();
        }
        function nodeName() {
            return $this->tag;
        }
        function appendChild($node) {
            $node->parent($this);
            return $node;
        }
    }

    /**
     *  in the find routine: allow us to specify that we want case insensitive testing of the value of the selector.
     *  change $size from protected to public so we can easily access it
     *  added force_tags_closed in the constructor which tells us whether we trust the html or not.  Default is to NOT trust it.
     *
     */
    class ParseHtmlDom{
        public    $root               = null;
        public    $nodes              = array();
        public    $callback           = null;
        public    $lowercase          = false;
        public    $_charset           = '';
        public    $_target_charset    = '';
        public    $default_span_text  = "";
        protected $token_blank        = " \t\r\n";
        protected $token_equal        = ' =/>';
        protected $token_slash        = " />\r\n\t";
        protected $token_attr         = ' >';
        protected $default_br_text    = "";
        protected $noise              = array();
        public    $original_size;
        public    $size;
        protected $pos;
        protected $doc;
        protected $char;
        protected $cursor;
        protected $parent;

        // use isset instead of in_array, performance boost about 30%...
        protected $self_closing_tags = array(
            'img'    => 1,
            'br'     => 1,
            'input'  => 1,
            'meta'   => 1,
            'link'   => 1,
            'hr'     => 1,
            'base'   => 1,
            'embed'  => 1,
            'spacer' => 1
        );
        protected $block_tags  = array(
            'root'  => 1,
            'body'  => 1,
            'form'  => 1,
            'div'   => 1,
            'span'  => 1,
            'table' => 1
        );

        // B tags that are not closed cause us to return everything to the end of the document.
        protected $optional_closing_tags = array(
            'tr'     => array(
                'tr' => 1,
                'td' => 1,
                'th' => 1
            ),
            'th'     => array(
                'th' => 1
            ),
            'td'     => array(
                'td' => 1
            ),
            'li'     => array(
                'li' => 1
            ),
            'dt'     => array(
                'dt' => 1,
                'dd' => 1
            ),
            'dd'     => array(
                'dd' => 1,
                'dt' => 1
            ),
            'dl'     => array(
                'dd' => 1,
                'dt' => 1
            ),
            'p'      => array(
                'p'  => 1
            ),
            'nobr'   => array(
                'nobr' => 1
            ),
            'b'      => array(
                'b' => 1
            ),
            'option' => array(
                'option' => 1
            ),
        );

        function __construct($str=null, $lowercase=true, $force_tags_closed=true, $target_charset=DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $default_span_text=DEFAULT_SPAN_TEXT){
            if( $str ){
                if ( preg_match("/^http:\/\//i",$str) || is_file($str) ){
                    $this->load_file($str);
                }
                else{
                    $this->load($str, $lowercase, $stripRN, $defaultBRText, $default_span_text);
                }
            }
            // Forcing tags to be closed implies that we don't trust the html, but it can lead to parsing errors if we SHOULD trust the html.
            if ( !$force_tags_closed ) {
                $this->optional_closing_array = array();
            }
            $this->_target_charset = $target_charset;
        }

        function __destruct(){
            $this->clear();
        }

        // load html from string
        function load($str, $lowercase=true, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $default_span_text=DEFAULT_SPAN_TEXT){
            global $debug_object;

            // prepare
            $this->prepare($str, $lowercase, $stripRN, $defaultBRText, $default_span_text);
            // strip out comments
            $this->removeNoise("'<!--(.*?)-->'is");
            // strip out cdata
            $this->removeNoise("'<!\[CDATA\[(.*?)\]\]>'is", true);
            // Script tags removal now preceeds style tag removal.
            // strip out <script> tags
            $this->removeNoise("'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is");
            $this->removeNoise("'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is");
            // strip out <style> tags
            $this->removeNoise("'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is");
            $this->removeNoise("'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is");
            // strip out preformatted tags
            $this->removeNoise("'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is");
            // strip out server side scripts
            $this->removeNoise("'(<\?)(.*?)(\?>)'s", true);
            // strip smarty scripts
            $this->removeNoise("'(\{\w)(.*?)(\})'s", true);

            // parsing
            while ( $this->parse() );
            // end
            $this->root->_[HDOM_INFO_END] = $this->cursor;
            $this->parseCharset();

            // make load function chainable
            return $this;
        }

        // find dom node by css selector
        //  allow us to specify that we want case insensitive testing of the value of the selector.
        function find($selector, $idx=null, $lowercase=false){
            return $this->root->find($selector, $idx, $lowercase);
        }

        // clean up memory due to php5 circular references memory leak...
        function clear(){
            foreach ( $this->nodes as $n ) {
                $n->clear();
                $n = null;
            }
            // This add next line is documented in the sourceforge repository. 2977248 as a fix for ongoing memory leaks that occur even with the use of clear.
            if ( isset($this->children) ){
                foreach ( $this->children as $n ) {
                    $n->clear();
                    $n = null;
                }
            }
            if ( isset($this->parent) ) {
                $this->parent->clear();
                unset($this->parent);
            }
            if ( isset($this->root) ) {
                $this->root->clear();
                unset($this->root);
            }
            unset($this->doc);
            unset($this->noise);
        }

        // prepare HTML data and init everything
        protected function prepare($str, $lowercase=true, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $default_span_text=DEFAULT_SPAN_TEXT) {
            $this->clear();
            // set the length of content before we do anything to it.
            $this->size = strlen($str);
            // Save the original size of the html that we got in.  It might be useful to someone.
            $this->original_size = $this->size;

            //before we save the string as the doc...  strip out the \r \n's if we are told to.
            if ( $stripRN ) {
                $str = str_replace("\r", " ", $str);
                $str = str_replace("\n", " ", $str);
                // set the length of content since we have changed it.
                $this->size = strlen($str);
            }

            $this->doc               = $str;
            $this->pos               = 0;
            $this->cursor            = 1;
            $this->noise             = array();
            $this->nodes             = array();
            $this->lowercase         = $lowercase;
            $this->default_br_text   = $defaultBRText;
            $this->default_span_text = $default_span_text;

            $this->root      = new ParseHtmlDomNode($this);
            $this->root->tag = 'root';
            $this->root->_[HDOM_INFO_BEGIN] = -1;
            $this->root->nodetype = HDOM_TYPE_ROOT;
            $this->parent         = $this->root;
            if ( $this->size > 0 ) {
                $this->char = $this->doc[0];
            }
        }

        // parse html content
        protected function parse(){
            if ( ( $s = $this->copyUntilChar('<') ) === '' ){
                return $this->readTag();
            }
            $node = new ParseHtmlDomNode($this);
            ++$this->cursor;
            $node->_[HDOM_INFO_TEXT] = $s;
            $this->linkNodes($node, false);
            return true;
        }

        // Added this to try to identify the character set of the page we have just parsed so we know better how to spit it out later.
        protected function parseCharset(){
            global $debug_object;
            $charset = null;
            if ( function_exists('get_last_retrieve_url_contents_content_type') ){
                $contentTypeHeader = get_last_retrieve_url_contents_content_type();
                $success = preg_match('/charset=(.+)/', $contentTypeHeader, $matches);
                if( $success ) {
                    $charset = $matches[1];
                    if( is_object($debug_object) ) {
                        $debug_object->debugLog(2, 'header content-type found charset of: ' . $charset);
                    }
                }

            }
            if ( empty($charset) ){
                $el = $this->root->find('meta[http-equiv=Content-Type]',0);
                if ( !empty($el) ) {
                    $fullvalue = $el->content;
                    if ( is_object($debug_object) ) {
                        $debug_object->debugLog(2, 'meta content-type tag found' . $fullvalue);
                    }

                    if( !empty($fullvalue) ){
                        $success = preg_match('/charset=(.+)/', $fullvalue, $matches);
                        if( $success ){
                            $charset = $matches[1];
                        } else {
                            // If there is a meta tag, and they don't specify the character set, research says that it's typically ISO-8859-1
                            if ( is_object($debug_object) ) {
                                $debug_object->debugLog(2, 'meta content-type tag couldn\'t be parsed. using iso-8859 default.');
                            }
                            $charset = 'ISO-8859-1';
                        }
                    }
                }
            }

            // If we couldn't find a charset above, then lets try to detect one based on the text we got...
            if ( empty($charset) ){
                // Have php try to detect the encoding from the text given to us.
                $charset = mb_detect_encoding($this->root->plaintext . "ascii", $encoding_list = array( "UTF-8", "CP1252" ) );
                if ( is_object($debug_object) ) {
                    $debug_object->debugLog(2, 'mb_detect found: ' . $charset);
                }

                // and if this doesn't work...  then we need to just wrongheadedly assume it's UTF-8 so that we can move on - cause this will usually give us most of what we need...
                if ( $charset === false ){
                    if ( is_object($debug_object) ) {
                        $debug_object->debugLog(2, 'since mb_detect failed - using default of utf-8');
                    }
                    $charset = 'UTF-8';
                }
            }

            // Since CP1252 is a superset, if we get one of it's subsets, we want it instead.
            if ( ( strtolower($charset) == strtolower('ISO-8859-1') ) || ( strtolower($charset) == strtolower('Latin1') ) || ( strtolower($charset) == strtolower('Latin-1') ) )
            {
                if ( is_object($debug_object) ) {
                    $debug_object->debugLog(2, 'replacing ' . $charset . ' with CP1252 as its a superset');
                }
                $charset = 'CP1252';
            }

            if ( is_object($debug_object) ) {
                $debug_object->debugLog(1, 'EXIT - ' . $charset);
            }
            return $this->_charset = $charset;
        }

        // read tag info
        protected function readTag(){
            if ( $this->char !== '<' ){
                $this->root->_[HDOM_INFO_END] = $this->cursor;
                return false;
            }
            $begin_tag_pos = $this->pos;
            $this->char    = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null;

            // end tag
            if ( $this->char === '/' ){
                $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null;
                $this->skip($this->token_blank);
                $tag = $this->copyUntilChar('>');

                // skip parseAttr in end tag
                if ( ( $pos = strpos($tag, ' ') ) !== false ){
                    $tag = substr($tag, 0, $pos);
                }
                $parent_lower = strtolower($this->parent->tag);
                $tag_lower    = strtolower($tag);

                if ( $parent_lower !== $tag_lower ) {
                    if ( isset($this->optional_closing_tags[$parent_lower]) && isset($this->block_tags[$tag_lower]) )  {
                        $this->parent->_[HDOM_INFO_END] = 0;
                        $org_parent = $this->parent;
                        while ( ( $this->parent->parent ) && strtolower($this->parent->tag) !== $tag_lower ){
                            $this->parent = $this->parent->parent;
                        }
                        if ( strtolower($this->parent->tag)!== $tag_lower ) {
                            $this->parent = $org_parent; // restore origonal parent
                            if ( $this->parent->parent ){
                                $this->parent = $this->parent->parent;
                            }
                            $this->parent->_[HDOM_INFO_END] = $this->cursor;
                            return $this->asTextNode($tag);
                        }
                    }
                    else if ( ( $this->parent->parent ) && isset( $this->block_tags[$tag_lower] ) ){
                        $this->parent->_[HDOM_INFO_END] = 0;
                        $org_parent = $this->parent;

                        while ( ( $this->parent->parent ) && strtolower($this->parent->tag) !== $tag_lower ){
                            $this->parent = $this->parent->parent;
                        }
                        if ( strtolower($this->parent->tag) !== $tag_lower ) {
                            $this->parent = $org_parent; // restore origonal parent
                            $this->parent->_[HDOM_INFO_END] = $this->cursor;
                            return $this->asTextNode($tag);
                        }
                    }
                    else if ( ( $this->parent->parent ) && strtolower($this->parent->parent->tag) === $tag_lower ) {
                        $this->parent->_[HDOM_INFO_END] = 0;
                        $this->parent = $this->parent->parent;
                    }
                    else{
                        return $this->asTextNode($tag);
                    }

                }
                $this->parent->_[HDOM_INFO_END] = $this->cursor;
                if( $this->parent->parent ){
                    $this->parent = $this->parent->parent;
                }

                $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null;
                return true;
            }

            $node = new ParseHtmlDomNode($this);
            $node->_[HDOM_INFO_BEGIN] = $this->cursor;
            ++$this->cursor;
            $tag = $this->copyUntil($this->token_slash);
            $node->tag_start = $begin_tag_pos;

            // doctype, cdata & comments...
            if ( isset($tag[0]) && $tag[0] === '!' ) {
                $node->_[HDOM_INFO_TEXT] = '<' . $tag . $this->copyUntilChar('>');

                if ( isset($tag[2]) && $tag[1] === '-' && $tag[2] === '-' ) {
                    $node->nodetype = HDOM_TYPE_COMMENT;
                    $node->tag = 'comment';
                } else {
                    $node->nodetype = HDOM_TYPE_UNKNOWN;
                    $node->tag = 'unknown';
                }
                if ( $this->char === '>' ) $node->_[HDOM_INFO_TEXT].='>';
                $this->linkNodes($node, true);
                $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null;
                return true;
            }
            if ( $pos = strpos($tag, '<') !== false ) {
                $tag = '<' . substr($tag, 0, -1);
                $node->_[HDOM_INFO_TEXT] = $tag;
                $this->linkNodes($node, false);
                $this->char = $this->doc[--$this->pos];
                return true;
            }
            if ( !preg_match("/^[\w-:]+$/", $tag) ) {
                $node->_[HDOM_INFO_TEXT] = '<' . $tag . $this->copyUntil('<>');
                if ( $this->char === '<' ) {
                    $this->linkNodes($node, false);
                    return true;
                }

                if ( $this->char === '>' ) {
                    $node->_[HDOM_INFO_TEXT].='>';
                }
                $this->linkNodes($node, false);
                $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null;
                return true;
            }

            // begin tag
            $node->nodetype = HDOM_TYPE_ELEMENT;
            $tag_lower = strtolower($tag);
            $node->tag = ( $this->lowercase ) ? $tag_lower : $tag;

            // handle optional closing tags
            if ( isset($this->optional_closing_tags[$tag_lower]) ) {
                while ( isset($this->optional_closing_tags[$tag_lower][strtolower($this->parent->tag)]) ){
                    $this->parent->_[HDOM_INFO_END] = 0;
                    $this->parent = $this->parent->parent;
                }
                $node->parent = $this->parent;
            }

            $guard = 0; // prevent infinity loop
            $space = array($this->copySkip($this->token_blank), '', '');

            // parseAttr
            do{
                if ( $this->char !== null && $space[0] === '' ){
                    break;
                }
                $name = $this->copyUntil($this->token_equal);
                if ( $guard === $this->pos ){
                    $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null;
                    continue;
                }
                $guard = $this->pos;

                // handle endless '<'
                if ( $this->pos >= $this->size-1 && $this->char !== '>' ) {
                    $node->nodetype = HDOM_TYPE_TEXT;
                    $node->_[HDOM_INFO_END] = 0;
                    $node->_[HDOM_INFO_TEXT] = '<'.$tag . $space[0] . $name;
                    $node->tag = 'text';
                    $this->linkNodes($node, false);
                    return true;
                }

                // handle mismatch '<'
                if ( $this->doc[$this->pos-1] == '<' ) {
                    $node->nodetype = HDOM_TYPE_TEXT;
                    $node->tag = 'text';
                    $node->attr = array();
                    $node->_[HDOM_INFO_END] = 0;
                    $node->_[HDOM_INFO_TEXT] = substr($this->doc, $begin_tag_pos, $this->pos-$begin_tag_pos-1);
                    $this->pos -= 2;
                    $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null;
                    $this->linkNodes($node, false);
                    return true;
                }

                if ( $name !== '/' && $name !== '' ) {
                    $space[1] = $this->copySkip($this->token_blank);
                    $name = $this->restoreNoise($name);
                    if ( $this->lowercase ){
                        $name = strtolower($name);
                    }
                    if ( $this->char === '=' ) {
                        $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null;
                        $this->parseAttr($node, $name, $space);
                    }
                    else {
                        //no value attr: nowrap, checked selected...
                        $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_NO;
                        $node->attr[$name] = true;
                        if ( $this->char != '>' ){
                            $this->char = $this->doc[--$this->pos];
                        }
                    }
                    $node->_[HDOM_INFO_SPACE][] = $space;
                    $space = array($this->copySkip($this->token_blank), '', '');
                }
                else{
                    break;
                }
            }
            while ( $this->char !== '>' && $this->char !== '/' );

            $this->linkNodes($node, true);
            $node->_[HDOM_INFO_ENDSPACE] = $space[0];

            // check self closing
            if ($this->copyUntilCharEscape('>')==='/'){
                $node->_[HDOM_INFO_ENDSPACE] .= '/';
                $node->_[HDOM_INFO_END] = 0;
            }
            else{
                // reset parent
                if ( !isset( $this->self_closing_tags[strtolower($node->tag)] ) ) $this->parent = $node;
            }
            $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null; // next

            // If it's a BR tag, we need to set it's text to the default text.
            // This way when we see it in plaintext, we can generate formatting that the user wants.
            // since a br tag never has sub nodes, this works well.
            if ($node->tag == "br"){
                $node->_[HDOM_INFO_INNER] = $this->default_br_text;
            }
            return true;
        }

        // parse parseAttr
        protected function parseAttr($node, $name, &$space){
            // If the attribute is already defined inside a tag, only pay atetntion to the first one as opposed to the last one.
            if ( isset($node->attr[$name]) ){
                return;
            }

            $space[2] = $this->copySkip($this->token_blank);
            switch ( $this->char ) {
                case '"':
                    $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_DOUBLE;
                    $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null; // next
                    $node->attr[$name] = $this->restoreNoise($this->copyUntilCharEscape('"'));
                    $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null; // next
                    break;
                case '\'':
                    $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_SINGLE;
                    $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null; // next
                    $node->attr[$name] = $this->restoreNoise($this->copyUntilCharEscape('\''));
                    $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null; // next
                    break;
                default:
                    $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_NO;
                    $node->attr[$name] = $this->restoreNoise($this->copyUntil($this->token_attr));
            }
            // PaperG: parseAttr should not have \r or \n in them, that counts as html whitespace.
            $node->attr[$name] = str_replace("\r", "", $node->attr[$name]);
            $node->attr[$name] = str_replace("\n", "", $node->attr[$name]);
            // PaperG: If this is a "class" selector, lets get rid of the preceeding and trailing space since some people leave it in the multi class case.
            if ( $name == "class" ) {
                $node->attr[$name] = trim($node->attr[$name]);
            }
        }

        // link node's parent
        protected function linkNodes(&$node, $is_child) {
            $node->parent = $this->parent;
            $this->parent->nodes[] = $node;
            if ( $is_child ){
                $this->parent->children[] = $node;
            }
        }

        // as a text node
        protected function asTextNode($tag){
            $node = new ParseHtmlDomNode($this);
            ++$this->cursor;
            $node->_[HDOM_INFO_TEXT] = '</' . $tag . '>';
            $this->linkNodes($node, false);
            $this->char = ( ++$this->pos < $this->size ) ? $this->doc[$this->pos] : null;
            return true;
        }

        protected function skip($chars) {
            $this->pos += strspn($this->doc, $chars, $this->pos);
            $this->char = ( $this->pos < $this->size ) ? $this->doc[$this->pos] : null; // next
        }

        protected function copySkip($chars){
            $pos = $this->pos;
            $len = strspn($this->doc, $chars, $pos);
            $this->pos += $len;
            $this->char = ( $this->pos < $this->size ) ? $this->doc[$this->pos] : null; // next
            if ( $len === 0 ){
                return '';
            }
            return substr($this->doc, $pos, $len);
        }

        protected function copyUntil($chars){
            $pos = $this->pos;
            $len = strcspn($this->doc, $chars, $pos);
            $this->pos += $len;
            $this->char = ( $this->pos < $this->size ) ? $this->doc[$this->pos] : null;
            return substr($this->doc, $pos, $len);
        }

        protected function copyUntilChar($char){
            if ( $this->char === null ){
                return '';
            }
            if ( ( $pos = strpos($this->doc, $char, $this->pos) ) === false ) {
                $ret = substr($this->doc, $this->pos, $this->size-$this->pos);
                $this->char = null;
                $this->pos = $this->size;
                return $ret;
            }
            if ( $pos === $this->pos ){
                return '';
            }
            $pos_old    = $this->pos;
            $this->char = $this->doc[$pos];
            $this->pos  = $pos;
            return substr($this->doc, $pos_old, $pos-$pos_old);
        }

        protected function copyUntilCharEscape($char){
            if ( $this->char === null ){
                return '';
            }
            $start = $this->pos;
            while (1){
                if ( ( $pos = strpos($this->doc, $char, $start) )=== false ){
                    $ret = substr($this->doc, $this->pos, $this->size-$this->pos);
                    $this->char = null;
                    $this->pos = $this->size;
                    return $ret;
                }
                if ( $pos=== $this->pos ){
                    return '';
                }
                if ( $this->doc[$pos-1] === '\\' ) {
                    $start = $pos + 1;
                    continue;
                }

                $pos_old    = $this->pos;
                $this->char = $this->doc[$pos];
                $this->pos  = $pos;
                return substr($this->doc, $pos_old, $pos-$pos_old);
            }
        }

        // remove noise from html content; save the noise in the $this->noise array.
        protected function removeNoise( $pattern, $remove_tag = false ){
            global $debug_object;
            if ( is_object( $debug_object ) ) {
                $debug_object->debugLogEntry(1);
            }

            $count = preg_match_all($pattern, $this->doc, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
            for ( $i = $count - 1; $i > -1; --$i ){
                $key = '___noise___'.sprintf('% 5d', count( $this->noise ) + 1000 );
                if (is_object($debug_object)) { $debug_object->debugLog(2, 'key is: ' . $key); }
                $idx = ( $remove_tag ) ? 0 : 1;
                $this->noise[$key] = $matches[$i][$idx][0];
                $this->doc = substr_replace($this->doc, $key, $matches[$i][$idx][1], strlen($matches[$i][$idx][0]));
            }

            // reset the length of content
            $this->size = strlen($this->doc);
            if ( $this->size > 0 ){
                $this->char = $this->doc[0];
            }
        }

        // restore noise to html content
        function restoreNoise($text){
            global $debug_object;
            if ( is_object( $debug_object ) ) {
                $debug_object->debugLogEntry(1);
            }
            while ( ( $pos = strpos($text, '___noise___') ) !== false ){
                // Sometimes there is a broken piece of markup, and we don't GET the pos+11 etc... token which indicates a problem outside of us...
                if ( strlen($text) > $pos + 15 ){
                    $key = '___noise___'.$text[$pos+11].$text[$pos+12].$text[$pos+13].$text[$pos+14].$text[$pos+15];
                    if ( is_object($debug_object) ) {
                        $debug_object->debugLog(2, 'located key of: ' . $key);
                    }

                    if ( isset($this->noise[$key]) ){
                        $text = substr($text, 0, $pos).$this->noise[$key].substr($text, $pos+16);
                    }else{
                        // do this to prevent an infinite loop.
                        $text = substr($text, 0, $pos).'UNDEFINED NOISE FOR KEY: '.$key . substr($text, $pos+16);
                    }
                } else{
                    // There is no valid key being given back to us... We must get rid of the ___noise___ or we will have a problem.
                    $text = substr($text, 0, $pos).'NO NUMERIC NOISE KEY' . substr($text, $pos+11);
                }
            }
            return $text;
        }

        // Sometimes we NEED one of the noise elements.
        function searchNoise($text){
            global $debug_object;
            if ( is_object($debug_object) ) {
                $debug_object->debugLogEntry(1);
            }
            foreach( $this->noise as $noise_element ){
                if ( strpos($noise_element, $text) !== false ){
                    return $noise_element;
                }
            }
        }

        function __toString(){
            return $this->root->innertext();
        }

        function __get($name){
            switch ( $name ){
                case 'outertext':
                    return $this->root->innertext();
                case 'innertext':
                    return $this->root->innertext();
                case 'plaintext':
                    return $this->root->text();
                case 'charset':
                    return $this->_charset;
                case 'target_charset':
                    return $this->_target_charset;
            }
        }

        // camel naming conventions
        function childNodes($idx=-1) {
            return $this->root->childNodes($idx);
        }
        function getElementByTagName($name) {
            return $this->find($name, 0);
        }
        function getElementsByTagName($name, $idx=-1) {
            return $this->find($name, $idx);
        }
    }
}