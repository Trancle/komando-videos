<?php


namespace RdPostOrder\App\Controllers\Admin;

if (!class_exists('\\RdPostOrder\\App\\Controllers\\Admin\\Activate')) {
    /**
     * The controller that will be working on activate the plugin.
     */
    class Activate implements \RdPostOrder\App\Controllers\ControllerInterface
    {


        use \RdPostOrder\App\AppTrait;


        /**
         * Activate the plugin.
         * 
         * @global \wpdb $wpdb WordPress db class.
         */
        public function activateAction()
        {
            global $wpdb;

            \RdPostOrder\App\Libraries\Debug::writeLog('RundizPostOrder activateAction() method was called.');

            // start by add order number into tables while activate the plugin.
            // newest post order is the latest number, oldest post order is always at 1st. 
            // this is the best for server performance (referrer: https://wordpress.org/support/topic/new-post-order-new-number-order-to-fix-slowly-add-new-post/ )

            // add order number in `table_relationships` table.
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
                    ' ORDER BY `' . $wpdb->posts . '`.`post_date` ASC',// get the oldest for number 1st to display at last page.
                OBJECT
            );
            if (is_array($results)) {
                $i_count = [];
                foreach ($results as $row) {
                    if (isset($i_count[$row->term_taxonomy_id])) {
                        $i_count[$row->term_taxonomy_id] ++;
                    } else {
                        $i_count[$row->term_taxonomy_id] = 1;
                    }
                    $wpdb->update(
                        $wpdb->term_relationships, 
                        ['term_order' => $i_count[$row->term_taxonomy_id]], 
                        ['object_id' => $row->object_id, 'term_taxonomy_id' => $row->term_taxonomy_id],
                        ['%d'],
                        ['%d', '%d']
                    );
                }// endforeach;
                unset($i_count, $row);
            }
            unset($results);*/ // this is not working on single post page due to it cannot find the referred category page. So, I cannot know what is that single post come from (from category or from home).
            // in the single post page there will be next/previous post and those posts must be related on where it come from which is required from a category page but there is no category referred.
            // so, i cannot working on this re-order posts in each category.

            // next is add order number into `posts` table ---for display in home page--- (changed, to be ALL page).
            $results = $wpdb->get_results(
                'SELECT ' . 
                    '`ID`, ' . 
                    '`post_date`, ' . 
                    '`post_name`, ' . 
                    '`post_status`, ' .
                    '`menu_order`, ' .
                    '`post_type`' . 
                    ' FROM `' . $wpdb->posts . '`' . 
                    ' WHERE `' . $wpdb->posts . '`.`post_type` = \'post\'' . 
                    ' AND `' . $wpdb->posts . '`.`post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')' . 
                    ' ORDER BY `' . $wpdb->posts . '`.`post_date` ASC',// get the oldest for number 1st to display at last page.
                OBJECT
            );
            if (is_array($results)) {
                $i_count = 1;
                foreach ($results as $row) {
                    if ($row->menu_order == '0') {
                        $wpdb->update(
                            $wpdb->posts,
                            ['menu_order' => $i_count],
                            ['ID' => $row->ID],
                            ['%d'],
                            ['%d']
                        );
                    }
                    $i_count++;
                }// endforeach;
                unset($i_count, $row);
            }
            unset($results);
            // finished activate the plugin.
        }// activateAction


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // register activate hook
            register_activation_hook(RDPOSTORDER_FILE, [&$this, 'activateAction']);
        }// registerHooks


    }
}