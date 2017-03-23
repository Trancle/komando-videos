<?php


namespace RdPostOrder\App\Controllers\Admin;

if (!class_exists('\\RdPostOrder\\App\\Controllers\\Admin\\Uninstall')) {
    /**
     * The controller that will be working on uninstall (delete) the plugin.
     */
    class Uninstall implements \RdPostOrder\App\Controllers\ControllerInterface
    {


        use \RdPostOrder\App\AppTrait;


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // register uninstall hook
            register_uninstall_hook(RDPOSTORDER_FILE, ['\\RdPostOrder\\App\\Controllers\\Admin\\Uninstall', 'uninstallAction']);
        }// registerHooks


        /**
         * Do the uninstallation action (reset all values to its default).
         * 
         * @global \wpdb $wpdb
         */
        private function doUninstallAction()
        {
            global $wpdb;

            // reset order number in `term_relationships` table.
            /*$results = $wpdb->get_results(
                'SELECT ' . 
                    '`' . $wpdb->term_relationships . '`.`object_id`, ' . 
                    '`' . $wpdb->term_relationships . '`.`term_taxonomy_id`, ' . 
                    '`' . $wpdb->term_taxonomy . '`.`term_taxonomy_id`, ' . 
                    '`' . $wpdb->term_taxonomy . '`.`term_id`, ' . 
                    '`' . $wpdb->term_taxonomy . '`.`taxonomy`, ' . 
                    '`' . $wpdb->posts . '`.`ID`, ' . 
                    '`' . $wpdb->posts . '`.`post_date`, ' . 
                    '`' . $wpdb->posts . '`.`post_name`, ' . 
                    '`' . $wpdb->posts . '`.`post_status`' . 
                    ' FROM `' . $wpdb->term_relationships . '`' . 
                    ' LEFT JOIN `' . $wpdb->term_taxonomy . '` ON `' . $wpdb->term_relationships . '`.`term_taxonomy_id` = `' . $wpdb->term_taxonomy . '`.`term_taxonomy_id`' . 
                    ' LEFT JOIN `' . $wpdb->posts . '` ON `' . $wpdb->term_relationships . '`.`object_id` = `' . $wpdb->posts . '`.`ID`' . 
                    ' WHERE `' . $wpdb->term_taxonomy . '`.`taxonomy` = \'category\'' . 
                    ' AND `' . $wpdb->posts . '`.`post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')' . 
                    ' ORDER BY `' . $wpdb->posts . '`.`post_date` ASC',
                OBJECT
            );
            if (is_array($results)) {
                foreach ($results as $row) {
                    $wpdb->update(
                        $wpdb->term_relationships, 
                        ['term_order' => 0], 
                        ['object_id' => $row->object_id, 'term_taxonomy_id' => $row->term_taxonomy_id],
                        ['%d'],
                        ['%d', '%d']
                    );
                }// endforeach;
                unset($row);
            }
            unset($results);*/ // please read the comment in /Activate controller, it's the same reason.

            // reset order number in `posts` table.
            $results = $wpdb->get_results(
                'SELECT ' . 
                    '`ID`, ' . 
                    '`post_date`, ' . 
                    '`post_name`, ' . 
                    '`post_status`, ' . 
                    '`post_type`' . 
                    ' FROM `' . $wpdb->posts . '`' . 
                    ' WHERE `' . $wpdb->posts . '`.`post_type` = \'post\'' . 
                    ' AND `' . $wpdb->posts . '`.`post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')' . 
                    ' ORDER BY `' . $wpdb->posts . '`.`post_date` ASC',
                OBJECT
            );
            if (is_array($results)) {
                foreach ($results as $row) {
                    $wpdb->update(
                        $wpdb->posts,
                        ['menu_order' => 0],
                        ['ID' => $row->ID],
                        ['%d'],
                        ['%d']
                    );
                }// endforeach;
                unset($row);
            }
            unset($results);
        }// doUninstallAction


        /**
         * Uninstall the plugin.<br>
         * Do the same way as activate the plugin but set the order number to 0 which is its default value.
         * 
         * @global \wpdb $wpdb
         */
        public static function uninstallAction()
        {
            global $wpdb;
            $ThisClass = new self;

            \RdPostOrder\App\Libraries\Debug::writeLog('RundizPostOrder uninstallAction() method was called.');

            if (is_multisite()) {
                $blog_ids = $wpdb->get_col('SELECT blog_id FROM '.$wpdb->blogs);
                $original_blog_id = get_current_blog_id();

                if (is_array($blog_ids)) {
                    // loop thru each sites to do uninstall action (reset data to its default value).
                    foreach ($blog_ids as $blog_id) {
                        switch_to_blog($blog_id);
                        $ThisClass->doUninstallAction();
                    }
                }

                // switch back to current site.
                switch_to_blog($original_blog_id);
                unset($blog_id, $blog_ids, $original_blog_id);
            } else {
                $ThisClass->doUninstallAction();
            }
        }// uninstallAction


    }
}