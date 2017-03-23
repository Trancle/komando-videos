<?php


namespace RdPostOrder\App\Controllers\Admin;

if (!class_exists('\\RdPostOrder\\App\\Controllers\\Admin\\PluginMetaAndLinks')) {
    class PluginMetaAndLinks implements \RdPostOrder\App\Controllers\ControllerInterface
    {


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // add filter action links. this will be displayed in actions area of plugin page. for example: xxxActionLinksBefore | Activate | Edit | Delete | xxxActionLinksAfter
            //add_filter('plugin_action_links', [&$this, 'actionLinks'], 10, 5);
            // add filter to row meta. (in plugin page below description) Version xx | By xxx | View details | xxxRowMetaxxx | xxxRowMetaxxx
            add_filter('plugin_row_meta', [$this, 'rowMeta'], 10, 2);
        }// registerHooks


        /**
         * add links to row meta that is in plugin page under plugin description.
         * 
         * @staticvar string $plugin the plugin file name.
         * @param array $links current meta links
         * @param string $file the plugin file name for checking.
         * @return array return modified links.
         */
        public function rowMeta($links, $file)
        {
            static $plugin;
            
            if (!isset($plugin)) {
                $plugin = plugin_basename(RDPOSTORDER_FILE);
            }
            
            if ($plugin === $file) {
                $new_link[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9HQE4GVV4KTZE" target="donate_rundiz">' . __('Donate', 'rd-postorder') . '</a>';
                $links = array_merge($links, $new_link);
                unset($new_link);
            }
            
            return $links;
        }// rowMeta


    }
}