<?php


namespace RdPostOrder\App\Controllers;

if (!class_exists('\\RdPostOrder\\App\\Controllers\\AlterPosts')) {
    /**
     * This controller will be working on front end to alter list post query.
     */
    class AlterPosts implements \RdPostOrder\App\Controllers\ControllerInterface
    {


        /**
         * Alter list post query.
         * 
         * @param \WP_Query $query
         */
        public function alterListPostAction($query)
        {
            if (is_admin()) {
                if (isset($query->query['post_type']) && $query->query['post_type'] == 'post' && !isset($_GET['orderby']) && !isset($_GET['order'])) {
                    $rd_postorder_admin_is_working = apply_filters('rd_postorder_admin_is_working', true);

                    if (isset($rd_postorder_admin_is_working) && $rd_postorder_admin_is_working === true) {
                        $query->set('orderby', 'menu_order');
                        $query->set('order', 'DESC');
                    }

                    unset($rd_postorder_admin_is_working);
                }
            } else {
                $rd_postorder_is_working = apply_filters('rd_postorder_is_working', true);

                if (isset($rd_postorder_is_working) && $rd_postorder_is_working === true) {
                    $query->set('orderby', 'menu_order');
                    $query->set('order', 'DESC');
                }

                unset($rd_postorder_is_working);
            }
        }// alterListPostAction


        /**
         * Alter next post sort.<br>
         * This is working from single post page.
         * 
         * @see wp-includes/link-template.php at get_{$adjacent}_post_sort
         * @param string $order_by The `ORDER BY` clause in the SQL.
         * @param \WP_Post $post WP_Post object.
         * @return string Return the modified `order by`.
         */
        public function alterNextPostSort($order_by, $post)
        {
            $rd_postorder_is_working = apply_filters('rd_postorder_is_working', true);

            if (isset($rd_postorder_is_working) && $rd_postorder_is_working === true) {
                if (isset($post->post_type) && $post->post_type == 'post') {
                    $orderby = 'ORDER BY p.menu_order ASC LIMIT 1';
                }
            }

            unset($rd_postorder_is_working);
            return $order_by;
        }// alterNextPostSort


        /**
         * Alter next post where.<br>
         * This is working from single post page.
         * 
         * @see wp-includes/link-template.php at get_{$adjacent}_post_where
         * @param string $where The `WHERE` clause in the SQL.
         * @param boolean $in_same_term Whether post should be in a same taxonomy term.
         * @param array $excluded_terms Array of excluded term IDs.
         * @param string $taxonomy Taxonomy. Used to identify the term used when `$in_same_term` is true.
         * @param \WP_Post $post WP_Post object.
         * @return string Return the modified where from default to `menu_order` field.
         */
        public function alterNextPostWhere($where, $in_same_term, $excluded_terms, $taxonomy, $post)
        {
            $rd_postorder_is_working = apply_filters('rd_postorder_is_working', true);

            if (isset($rd_postorder_is_working) && $rd_postorder_is_working === true) {
                if (isset($post->post_type) && $post->post_type == 'post') {
                    $where = str_replace('p.post_date > \''.$post->post_date.'\'', 'p.menu_order > \''.$post->menu_order.'\'', $where);
                }
            }

            unset($rd_postorder_is_working);
            return $where;
        }// alterNextPostWhere


        /**
         * Alter previous post sort.<br>
         * This is working from single post page.
         * 
         * @see wp-includes/link-template.php at get_{$adjacent}_post_sort
         * @param string $order_by The `ORDER BY` clause in the SQL.
         * @param \WP_Post $post WP_Post object.
         * @return string Return the modified `order by`.
         */
        public function alterPreviousPostSort($order_by, $post)
        {
            $rd_postorder_is_working = apply_filters('rd_postorder_is_working', true);

            if (isset($rd_postorder_is_working) && $rd_postorder_is_working === true) {
                if (isset($post->post_type) && $post->post_type == 'post') {
                    $orderby = 'ORDER BY p.menu_order DESC LIMIT 1';
                }
            }

            unset($rd_postorder_is_working);
            return $order_by;
        }// alterPreviousPostSort


        /**
         * Alter previous post where.<br>
         * This is working from single post page.
         * 
         * @see wp-includes/link-template.php at get_{$adjacent}_post_where
         * @param string $where The `WHERE` clause in the SQL.
         * @param boolean $in_same_term Whether post should be in a same taxonomy term.
         * @param array $excluded_terms Array of excluded term IDs.
         * @param string $taxonomy Taxonomy. Used to identify the term used when `$in_same_term` is true.
         * @param \WP_Post $post WP_Post object.
         * @return string Return the modified where from default to `menu_order` field.
         */
        public function alterPreviousPostWhere($where, $in_same_term, $excluded_terms, $taxonomy, $post)
        {
            $rd_postorder_is_working = apply_filters('rd_postorder_is_working', true);

            if (isset($rd_postorder_is_working) && $rd_postorder_is_working === true) {
                if (isset($post->post_type) && $post->post_type == 'post') {
                    $where = str_replace('p.post_date < \''.$post->post_date.'\'', 'p.menu_order < \''.$post->menu_order.'\'', $where);
                }
            }

            unset($rd_postorder_is_working);
            return $where;
        }// alterPreviousPostWhere


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            add_action('pre_get_posts', [$this, 'alterListPostAction']);

            add_filter('get_previous_post_where', [$this, 'alterPreviousPostWhere'], 10, 5);
            add_filter('get_previous_post_sort', [$this, 'alterPreviousPostSort'], 10, 2);
            add_filter('get_next_post_where', [$this, 'alterNextPostWhere'], 10, 5);
            add_filter('get_next_post_sort', [$this, 'alterNextPostSort'], 10, 2);
        }// registerHooks


    }
}