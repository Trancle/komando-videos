<?php
/**
 * Description of Productivity Metrics
 * @author Lisdanay Dominguez
 */

    /**
     * #REVIEW: #IMPORTANT We need to ensure that ANY MySQL queries are all sanitized
     */

class ProductivityMetricsController
{
    /**
     * #REVIEW: why is this being done? If we want to convert seconds to hours we can just divide $seconds by 3600
     * if $seconds = 3600
     * then $seconds/3600 = 1 hour
     */
    const  HOURS_IN_SECONDS = 0.000277778;
	public static function do_process($parameters)
	{
		if(isset($parameters['action']) && !empty($parameters['action'])){
			switch($parameters['action']) {
				case 'get_authors': $data = self::get_list_authors($parameters);
					break;
				case 'get_config': $data = self::get_authors_config($parameters);
					break;
				case 'update_config': $data = self::set_authors_config($parameters);
					break;
				case 'get_list_content_authored': $data = self::get_list_content_authored($parameters);
					break;
				case 'get_list_content_edited': $data = self::get_list_content_edited($parameters);
					break;
				case 'get_list_content_involved': $data = self::get_list_content_involved($parameters);
					break;
				case 'update_author_log': $data = self::insert_author_log($parameters);
					break;
				case 'get_total_hours': $data = self::get_authors_params($parameters);
					break;
				case 'get_long_takes_to_write': $data = self::get_long_takes_to_write($parameters);
					break;
				case 'get_many_articles_day': $data = self::get_many_articles_day($parameters);
					break;
                case 'get_start_info': $data = self::get_start_info($parameters);
					break;
			}
		}
		else{
			$data = array(
				'success' => false,
				'message' => 'Bad Request'
			);
		}
		return $data;
	}

    public static function get_start_info($parameters) {
        $days_in_graph         = 28;
        $results_in_7          = self::get_total_articles_by_date($parameters , 7);
        $results_in_publish_7  = self::get_total_articles_by_date($parameters , 7, "publish");
        $results_in_14         = self::get_total_articles_by_date($parameters , 14);
        $results_in_publish_14 = self::get_total_articles_by_date($parameters , 14, "publish");
        $results_in_28         = self::get_total_articles_by_date($parameters , $days_in_graph);
        $results_in_publish_28 = self::get_total_articles_by_date($parameters , $days_in_graph, "publish");

        $results_list_in_28_days = self::get_total_price_in_date( $days_in_graph );
        $graph     = [];
        $graph_aux = [];

        /**
         * #REVIEW: maybe use mktime() instead of strtotime() for this entire section?
         */
        foreach ( $results_list_in_28_days as   $value )
        {
            $created_at = strtotime($value->created_at. ' midnight');
            if(!in_array($created_at, $graph_aux)){
                $articles_list_aux[$created_at] =  0 ;
            }
            $graph_aux[$created_at] += (!is_null($value->price)) ? floatval($value->price):0;
        }

        for( $i = 0; $i <= $days_in_graph; $i++ ) {
            $newDate    =  strtotime ( '-'.($days_in_graph - $i).' day' , strtotime ( date('m/d/Y')  ) ) ;
            $price_value = ((isset($graph_aux[$newDate]) && !empty($graph_aux[$newDate]) )) ? $graph_aux[$newDate] :  0.0;
            $graph[]    = [ strtotime(date ( 'm/d/Y' , $newDate ) . ' midnight') , number_format($price_value,2) ];
        }

        $articles_list_in_28_days = self::get_all_published_articles( $days_in_graph);
        $articles_list     = [];
        $articles_list_aux = [];
        foreach ( $articles_list_in_28_days as   $value )
        {
            $created_at = strtotime($value->created_at. ' midnight');
            if(!in_array($created_at, $articles_list_aux)){
                $articles_list_aux[$created_at] =  0 ;
            }
            $articles_list_aux[$created_at] +=  (int)$value->total;
        }
        for( $i = 0; $i <= $days_in_graph; $i++ ) {
            $newDate    =  strtotime ( '-'.($days_in_graph - $i).' day' , strtotime ( date('m/d/Y')  ) ) ;
            $total = ((isset($articles_list_aux[$newDate]) && !empty($articles_list_aux[$newDate]) )) ? $articles_list_aux[$newDate] :  0;
            $articles_list[]    = [ strtotime(date ( 'm/d/Y' , $newDate ) . ' midnight') , $total ];
        }


        // Get the results
        return [
            'results' => [
                'over_last_7'          => (int)$results_in_7->total,
                'over_last_publish_7'  => (int)$results_in_publish_7->total,
                'over_last_14'         => (int)$results_in_14->total,
                'over_last_publish_14' => (int)$results_in_publish_14->total,
                'over_last_28'         => (int)$results_in_28->total,
                'over_last_publish_28' => (int)$results_in_publish_28->total,
                'graph'                => $graph,
                'articles_list'        => $articles_list
            ],
            'args'  => $results_list_in_28_days
        ];
    }

    public static function get_time_in_second_by_date($parameters , $day )
    {
		global $wpdb;
        if( 0 < intval($day) ){
		$newDate = strtotime ( '-'.$day.' day' , strtotime ( date('Y-m-d') ) ) ;
		$newDate = date ( 'Y-m-d' , $newDate ); 
            $sql = $wpdb->prepare("SELECT SUM(k2_author_log.time_in_second) as time_in_second 
                    FROM k2_author_log 
                    WHERE  k2_author_log.author_id = %d 
                    AND  DATE_FORMAT(k2_author_log.created_at,'%s') > %s",$parameters['values'], '%Y-%m-%d',$newDate);
		return $wpdb->get_row($sql,OBJECT);
	}
        return [];
	}

	public static function get_total_price_in_date( $day ) {
		global $wpdb;
        if( 0 < intval($day) ){
		$newDate = strtotime ( '-'.$day.' day' , strtotime ( date('Y-m-d') ) ) ;
		$newDate = date ( 'Y-m-d' , $newDate );

            $sql = $wpdb->prepare(" SELECT 
				SUM( 
					IF( k2_author_log.is_overtime = %d ,
					   (  k2_author_log.time_in_second * %F *	(SELECT wp_usermeta.meta_value from wp_usermeta where wp_usermeta.user_id = k2_author_log.author_id AND  wp_usermeta.meta_key = %s)) 
					  ,(  k2_author_log.time_in_second * %F *	(SELECT wp_usermeta.meta_value from wp_usermeta where wp_usermeta.user_id = k2_author_log.author_id AND  wp_usermeta.meta_key = %s))
					)
				) AS price, 
			 DATE_FORMAT(k2_author_log.created_at, %s) AS created_at
			 FROM k2_author_log 
			 WHERE DATE_FORMAT(k2_author_log.created_at, %s) > %s
			 GROUP BY DATE_FORMAT(k2_author_log.created_at, %s)  "
            ,0
            ,ProductivityMetricsController::HOURS_IN_SECONDS
            ,'hourly_wage'
            ,ProductivityMetricsController::HOURS_IN_SECONDS
            ,'overtime_wage'
            ,'%m/%d/%Y'
            ,'%Y-%m-%d'
            ,$newDate
            ,'%m/%d/%Y');
 		return $wpdb->get_results($sql,OBJECT);
	}
        return [];
	}

	public static function get_all_in_date($parameters , $day ) {
		global $wpdb;
        if(0 < intval($day)){
		$newDate = strtotime ( '-'.$day.' day' , strtotime ( date('Y-m-d') ) ) ;
		$newDate = date ( 'Y-m-d' , $newDate );

            $sql ="  
            SELECT SUM(k2_author_log.time_in_second) AS time_in_second, DATE_FORMAT(k2_author_log.created_at, %s) AS created_at
			 FROM k2_author_log 
			 WHERE DATE_FORMAT(k2_author_log.created_at, %s) > %s ";
            if( isset($parameters['isAuthor']) &&  'true' == $parameters['isAuthor'] ){
                $sql  .= " AND k2_author_log.author_id = %d ";
	}
            $sql .="GROUP BY DATE_FORMAT(k2_author_log.created_at, %s)  ";
        
            if( isset($parameters['isAuthor']) &&  'true' == $parameters['isAuthor'] ){
                $sql_prepare = $wpdb->prepare($sql
                    ,'%m/%d/%Y'
                    ,'%Y-%m-%d'
                    ,$newDate
                    ,$parameters['values']
                    ,'%m/%d/%Y'
                );
            }else{
                $sql_prepare = $wpdb->prepare($sql
                    ,'%m/%d/%Y'
                    ,'%Y-%m-%d'
                    ,$newDate
                    ,'%m/%d/%Y'
                );
            }

            return $wpdb->get_results($sql_prepare,OBJECT);
        }
        return [];
	}
        
	public static function get_all_published_articles( $day ) {
		global $wpdb;
        if( 0 < intval($day)){
        $newDate = strtotime ( '-'.$day.' day' , strtotime ( date('Y-m-d') ) ) ;
        $newDate = date ( 'Y-m-d' , $newDate );
            $sql = $wpdb->prepare(" SELECT COUNT(p1.ID) as total,  DATE_FORMAT(p1.post_date, %s) AS created_at 
                FROM $wpdb->posts AS p1 
                WHERE  p1.post_type <> %s AND p1.post_status = %s AND  DATE_FORMAT(p1.post_date, %s) > %s  GROUP BY DATE_FORMAT(p1.post_date, %s) "
                , '%m/%d/%Y'
                ,'revision'
                ,'publish'
                ,'%Y-%m-%d'
                ,$newDate
                ,'%m/%d/%Y'
            );
        return $wpdb->get_results($sql,OBJECT);
	}
        return [];
	}

	public static function get_total_articles_by_date($parameters   , $day, $status = "") {
		global $wpdb;
		$newDate = date ( 'Y-m-d' , strtotime ( '-'.$day.' day' , strtotime ( date('Y-m-d') ) ) );
        $post_status = ( "publish" == $status ) ? " AND p1.post_status = %s " : " AND p1.post_status <> %s ";
		$sql = "SELECT COUNT(p1.ID) as total FROM $wpdb->posts AS p1 WHERE  p1.post_type <> %s  AND  DATE_FORMAT(p1.post_date, %s) > %s ".$post_status;

        if( isset($parameters['isAuthor']) && 'true' == $parameters['isAuthor'] ){
            $sql .= " AND  p1.post_author = %d ";
            $sql = $wpdb->prepare($sql,
                'revision'
                ,'%Y-%m-%d'
                ,$newDate
                ,'publish'
                ,$parameters['values']
            );
        } else{
            $sql = $wpdb->prepare($sql,
                'revision'
                ,'%Y-%m-%d'
                ,$newDate
                ,'publish'
            );
		}

        return $wpdb->get_row($sql, OBJECT);
	}

	public static function get_long_takes_to_write($parameters) {
	    $days_in_graph = 28;
		$results_in_7  = self::get_time_in_second_by_date($parameters , 7);
		$results_in_14 = self::get_time_in_second_by_date($parameters , 14);
		$results_in_28 = self::get_time_in_second_by_date($parameters , 28);

		$results_list_in_28_days = self::get_all_in_date($parameters , $days_in_graph);

		$graph     = [];
        $graph_aux = [];

		foreach ( $results_list_in_28_days as   $value )
		{
		    $created_at = strtotime($value->created_at. ' midnight');
		    if(!in_array($created_at, $graph_aux)){
                $articles_list_aux[$created_at] =  0 ;
            }
            $graph_aux[$created_at] = floatval($value->time_in_second * ProductivityMetricsController::HOURS_IN_SECONDS) ;
		}

        for( $i = 0; $i <= $days_in_graph; $i++ ) {
            $newDate    =  strtotime ( '-'.($days_in_graph - $i).' day' , strtotime ( date('m/d/Y')  ) ) ;
            $time_value = ((isset($graph_aux[$newDate]) && !empty($graph_aux[$newDate]) )) ? $graph_aux[$newDate] :  0.0;
            $graph[]    = [ strtotime(date ( 'm/d/Y' , $newDate ) . ' midnight'), $time_value ];
        }

		// Get the results
		return [
			'results'    => [
				'over_last_7'  => $results_in_7->time_in_second  * ProductivityMetricsController::HOURS_IN_SECONDS,
				'over_last_14' => $results_in_14->time_in_second * ProductivityMetricsController::HOURS_IN_SECONDS,
				'over_last_28' => $results_in_28->time_in_second * ProductivityMetricsController::HOURS_IN_SECONDS,
				'graph'        => $graph
			],
			'args'       => $parameters
		];
	}

	public static function get_many_articles_day($parameters) {
		$results_in_7          = self::get_total_articles_by_date($parameters , 7);
		$results_in_publish_7  = self::get_total_articles_by_date($parameters , 7, "publish");
		$results_in_14         = self::get_total_articles_by_date($parameters , 14);
		$results_in_publish_14 = self::get_total_articles_by_date($parameters , 14, "publish");
		$results_in_28         = self::get_total_articles_by_date($parameters , 28);
		$results_in_publish_28 = self::get_total_articles_by_date($parameters , 28, "publish");

		// Get the results
		return [
			'results' => [
				'over_last_7'          => $results_in_7->total,
				'over_last_publish_7'  => $results_in_publish_7->total,
				'over_last_14'         => $results_in_14->total,
				'over_last_publish_14' => $results_in_publish_14->total,
				'over_last_28'         => $results_in_28->total,
				'over_last_publish_28' => $results_in_publish_28->total
			],
			'args' => $parameters
		];
	}

	public static function get_list_authors($parameters) {
        global $wpdb;
		$args = [ 
			'role__in1' => 'editor',
			'role__in2' => 'author',
			'orderby'  => (isset($parameters['orderby'])) ? 'wp_users'.$parameters['orderby'] : 'wp_users.display_name',
			'order'    => (isset($parameters['reverse'] ) && 'true' == $parameters['reverse']) ?  'DESC' : 'ASC',
			'offset'   => (isset($parameters['offset']))  ? $parameters['offset']  : 0,
			'number'   => (isset($parameters['number'])) ? $parameters['number'] : 7
		];
        $orderby =  (isset($parameters['orderby'])) ? $parameters['orderby'] : 'display_name';
        $order    = (isset($parameters['reverse'] ) && 'true' == $parameters['reverse']) ?  'DESC' : 'ASC';

        $where_prepare = [];
		$where = '';
        $args_prepare = [
            'wp_capabilities'
            ,"%".$args['role__in1']."%"
            ,'wp_capabilities'
            ,"%".$args['role__in2']."%"
        ];
		if( (isset($parameters['filter'])) )
		{
			$objFilter = json_decode(stripslashes ($parameters['filter']));
			foreach ( $objFilter as $filter )
			{
                if( !empty($filter->filter) ){
                    $where .= " AND wp_users.".$filter->field." like %s ";
                    $where_prepare []= '%'.$filter->filter.'%';
				}
			}
		}

		$sql =" SELECT wp_users.ID, 
			wp_users.user_login, 
			wp_users.user_email, 
			wp_users.user_nicename, 
			wp_users.display_name 
		FROM wp_users INNER JOIN wp_usermeta ON ( wp_users.ID = wp_usermeta.user_id )
		WHERE   
		(   ( wp_usermeta.meta_key = %s AND CAST(wp_usermeta.meta_value AS CHAR) LIKE %s ) 
		    OR ( wp_usermeta.meta_key = %s AND CAST(wp_usermeta.meta_value AS CHAR) LIKE %s) 
		) ";

        if( !empty($where) ){
            $sql .= $where;
            $args_prepare = array_merge($args_prepare,$where_prepare);
        }
        if( 'display_name' == $orderby && 'ASC' == $order){
            $sql .= "  ORDER BY wp_users.display_name ASC ";
        }
        else if( 'display_name' == $orderby && 'DESC' == $order){
            $sql .= "  ORDER BY wp_users.display_name DESC ";
        }  else  if( 'user_nicename' == $orderby && 'ASC' == $order){
            $sql .= "  ORDER BY wp_users.display_name ASC ";
        }
        else if( 'user_nicename' == $orderby && 'DESC' == $order){
            $sql .= "  ORDER BY wp_users.display_name DESC ";
        }else{
            $sql .= "  ORDER BY wp_users.ID DESC ";
        }

        $sql .= "  LIMIT %d, %d ";
       // $args_prepare []= $args['orderby'];
       // $args_prepare []= $args['order'];
        $args_prepare []= $args['offset'];
        $args_prepare []= $args['number'];
        $sql =  $wpdb->prepare( $sql,$args_prepare );

		$results = $wpdb->get_results($sql, OBJECT);
		$sqlTotal =" SELECT COUNT(wp_users.ID) AS total 
		 FROM wp_users INNER JOIN wp_usermeta ON ( wp_users.ID = wp_usermeta.user_id )
		WHERE   
		(   ( wp_usermeta.meta_key = %s AND CAST(wp_usermeta.meta_value AS CHAR) LIKE %s ) 
		    OR ( wp_usermeta.meta_key = %s AND CAST(wp_usermeta.meta_value AS CHAR) LIKE %s) 
		) ";

        if( !empty($where) ){
            $sqlTotal .= $where;
        }

        if( 'display_name' == $orderby && 'ASC' == $order){
            $sqlTotal .= "  ORDER BY wp_users.display_name ASC ";
        }
        else if( 'display_name' == $orderby && 'DESC' == $order){
            $sqlTotal .= "  ORDER BY wp_users.display_name DESC ";
        }  else  if( 'user_nicename' == $orderby && 'ASC' == $order){
            $sqlTotal .= "  ORDER BY wp_users.display_name ASC ";
        }
        else if( 'user_nicename' == $orderby && 'DESC' == $order){
            $sqlTotal .= "  ORDER BY wp_users.display_name DESC ";
        }else{
            $sqlTotal .= "  ORDER BY wp_users.ID DESC  ";
        }

        unset($args_prepare[count($args_prepare)-1]);
        $sqlTotal =  $wpdb->prepare( $sqlTotal,$args_prepare );
		$total = $wpdb->get_results($sqlTotal, OBJECT);

		// Get the results
		return ['rows'   => $results,
                        'howMany' => $total[0]->total,
                        'page'    => $parameters['page'],
                        'reverse' => $parameters['reverse'],
                        'offset'  => $args['offset'],
                        'args'    => $parameters
		];
	}

	public static function get_authors_config($parameters) {
		$awesome_level = 0;
		$user_id       = $parameters['id'];

		$all_meta_for_user = get_user_meta($user_id );
		if(!isset( $all_meta_for_user['hourly_wage'] ) ){
			add_user_meta( $user_id, 'hourly_wage', $awesome_level);
			$all_meta_for_user['hourly_wage'] = 0;
		}
		if(!isset( $all_meta_for_user['overtime_wage'] ) ){
			add_user_meta( $user_id, 'overtime_wage', $awesome_level);
			$all_meta_for_user['overtime_wage'] = 0;
		}
		if(!isset( $all_meta_for_user['total_hours_per_month'] ) ){
			add_user_meta( $user_id, 'total_hours_per_month', $awesome_level);
			$all_meta_for_user['total_hours_per_month'] = 0;
		}

		// Get the results
		return ['hourly_wage' => $all_meta_for_user['hourly_wage'][0],
			'overtime_wage' => $all_meta_for_user['overtime_wage'][0],
			'total_hours_per_month' => $all_meta_for_user['total_hours_per_month'][0]
		];
	}

	public static function set_authors_config($parameters) {
		$params =  json_decode(stripslashes ($parameters['values']));
		$user_id   = $parameters['id'];

		if(isset( $params->hourly_wage ) && '' != $params->hourly_wage){
			$result_hourly_wage = update_user_meta( $user_id, 'hourly_wage', $params->hourly_wage  );
		}
		if(isset( $params->overtime_wage ) && '' != $params->overtime_wage  ){
			$result_overtime_wage = update_user_meta( $user_id, 'overtime_wage', $params->overtime_wage  );
		}
		if(isset( $params->total_hours_per_month ) && '' != $params->total_hours_per_month  ){
			$total_hours_per_month = update_user_meta( $user_id, 'total_hours_per_month', $params->total_hours_per_month  );
		}

		// Get the results
		return ['hourly_wage' => $result_hourly_wage, 'overtime_wage' => $result_overtime_wage , 'total_hours_per_month' => $total_hours_per_month, 'arg' => $params ] ;
	}

	public static function get_total_hours_worked($parameters, $type = ""){

		global $wpdb;

        $offset   = (isset($parameters['offset']))  ? $parameters['offset']  : 0;
		$number   = (isset($parameters['number'])) ? $parameters['number'] : 7;
		$orderby  = (isset($parameters['orderby'])) ? $parameters['orderby'] : 'post_date';
		$order    = (isset($parameters['reverse'] ) && $parameters['reverse'] == 'false') ?   'DESC' : 'ASC';

		$where    = '';
        $args_authored =[0,'hourly_wage','overtime_wage',$parameters['id']];
        $count_args_authored =[$parameters['id']];
        $args_where =[];
		if( (isset($parameters['filter'])) )
		{
			$objFilter = json_decode(stripslashes ($parameters['filter']));
			foreach ( $objFilter as $filter )
			{
				if( !empty( $filter->filter) ){
					$where .=" AND p1.".$filter->field." like %s ";
                    $args_where [] = "%".$filter->filter."%";
				}
			}
		}

        if(!empty($where)){
            $args_authored = array_merge($args_authored,$args_where);
            $count_args_authored = array_merge($count_args_authored,$args_where);
        }
        $args_authored[] = $offset;
        $args_authored[] = $number;
		if("authored" == $type){
            $sql_prepare = " SELECT p1.ID ,p1.post_parent,
					p1.post_title,
					p1.post_date,
					p1.post_modified, 
					p1.post_status, 
					p1.post_type,
                    SUM( k2_author_log.time_in_second ) AS time_in_second,
					SUM( 
					IF( k2_author_log.is_overtime = %d ,
					   (  k2_author_log.time_in_second * (SELECT wp_usermeta.meta_value from wp_usermeta where wp_usermeta.user_id = k2_author_log.author_id AND  wp_usermeta.meta_key = %s)) 
					  ,(  k2_author_log.time_in_second * (SELECT wp_usermeta.meta_value from wp_usermeta where wp_usermeta.user_id = k2_author_log.author_id AND  wp_usermeta.meta_key = %s))
					)
				) AS estimated_cost 
					FROM  k2_author_log 
                    INNER JOIN $wpdb->posts AS p1 ON (p1.post_author = author_id AND p1.id = k2_author_log.post_id) 
					WHERE  k2_author_log.author_id = %d ".$where."    
					GROUP BY p1.ID ";

            $countSql = $wpdb->prepare( "SELECT COUNT(p1.ID) as total
			        FROM  k2_author_log 
                    INNER JOIN $wpdb->posts AS p1 ON (p1.post_author = author_id AND p1.id = k2_author_log.post_id) 
					WHERE  k2_author_log.author_id = %d ".$where." GROUP BY p1.id ",$count_args_authored );
			$total = $wpdb->get_results($countSql, OBJECT);
			$how_many['total'] = count($total);
		}
		else if("edited" == $type){

            $sql_prepare = " SELECT p1.ID ,p1.post_parent,
					p1.post_title,
					p1.post_date,
					p1.post_modified, 
					p1.post_status, 
					p1.post_type,
                  SUM( k2_author_log.time_in_second ) AS time_in_second,
					SUM( 
					IF( k2_author_log.is_overtime = %d ,
					   (  k2_author_log.time_in_second * (SELECT wp_usermeta.meta_value from wp_usermeta where wp_usermeta.user_id = k2_author_log.author_id AND  wp_usermeta.meta_key = %s)) 
					  ,(  k2_author_log.time_in_second * (SELECT wp_usermeta.meta_value from wp_usermeta where wp_usermeta.user_id = k2_author_log.author_id AND  wp_usermeta.meta_key = %s))
					)
				) AS estimated_cost 
					FROM  k2_author_log 
                    INNER JOIN $wpdb->posts AS p1 ON (p1.post_author <> author_id AND p1.id = k2_author_log.post_id) 
					WHERE  k2_author_log.author_id = %d ".$where."    
					GROUP BY p1.ID ";


            $countSql =  $wpdb->prepare("SELECT COUNT(p1.ID) as total
			        FROM  k2_author_log 
                    INNER JOIN $wpdb->posts AS p1 ON (p1.post_author <> author_id AND p1.id = k2_author_log.post_id) 
					WHERE  k2_author_log.author_id = %d ".$where." GROUP BY p1.id",$count_args_authored );

            $total = $wpdb->get_results($countSql, OBJECT);
            $how_many['total'] = count($total);
		}
		else
		{
            $sql_prepare = " SELECT p1.ID ,p1.post_parent,
					p1.post_title,
					p1.post_date,
					p1.post_modified, 
					p1.post_status, 
					p1.post_type,	
                    SUM( k2_author_log.time_in_second ) AS time_in_second,
					SUM( 
					IF( k2_author_log.is_overtime = %d ,
					   (  k2_author_log.time_in_second * (SELECT wp_usermeta.meta_value from wp_usermeta where wp_usermeta.user_id = k2_author_log.author_id AND  wp_usermeta.meta_key = %s)) 
					  ,(  k2_author_log.time_in_second * (SELECT wp_usermeta.meta_value from wp_usermeta where wp_usermeta.user_id = k2_author_log.author_id AND  wp_usermeta.meta_key = %s))
					)
				) AS estimated_cost 
					FROM  k2_author_log 
                    INNER JOIN $wpdb->posts AS p1 ON ( p1.id = k2_author_log.post_id) 
					WHERE  k2_author_log.author_id = %d ".$where."    
					GROUP BY p1.ID ";

                $countSql = $wpdb->prepare("SELECT COUNT(p1.ID) as total
			        FROM  k2_author_log 
                    INNER JOIN $wpdb->posts AS p1 ON ( p1.id = k2_author_log.post_id) 
					WHERE  k2_author_log.author_id = %d ".$where." GROUP BY p1.id",$count_args_authored );

            $total = $wpdb->get_results($countSql, OBJECT);
            $how_many['total'] = count($total);
		}

        if(!empty($sql_prepare)){

            if( 'post_title' == $orderby && 'ASC' == $order){
                $sql_prepare .= "  ORDER BY p1.post_title ASC ";
            }
            else if( 'post_title' == $orderby && 'DESC' == $order){
                $sql_prepare .= "  ORDER BY p1.post_title DESC ";
            } else{
                $sql_prepare .= "  ORDER BY p1.ID DESC ";
            }
            $sql_prepare .= "  LIMIT %d , %d";

            $sql = $wpdb->prepare( $sql_prepare,$args_authored );
        }
		$results = $wpdb->get_results($sql, OBJECT);

		foreach ($results as $key => $val  )
		{
			$post_meta = get_post_meta($val->ID, 'ga_pageviews', true);
			$results[$key]->number_of_page_views = 'N/A';
			if ( ! empty( $post_meta ) ) {
				$results[$key]->number_of_page_views =  $post_meta['views'];
			}
			$results[$key]->link   = ( 'post' != $val->post_type ) ? get_permalink($val->post_parent) : get_permalink($val->ID) ;
			$results[$key]->time_in_second =  $val->time_in_second * ProductivityMetricsController::HOURS_IN_SECONDS;
			$results[$key]->estimated_cost =  ProductivityMetricsController::HOURS_IN_SECONDS * $val->estimated_cost;
		}

		// Get the results
		return [
			'rows'   => $results,
			'howMany' => $how_many['total'],
			'page'    => $parameters['page'],
			'reverse' => $parameters['reverse'],
			'offset'  => $parameters['offset'],
			'args'    => $parameters
		];
	}

	public static function get_list_content_authored($parameters) {
		// Get the results
		return self::get_total_hours_worked($parameters, "authored");
	}

	public static function get_list_content_edited($parameters) {
		// Get the results
		return self::get_total_hours_worked($parameters, "edited");
	}

	public static function get_list_content_involved($parameters) {
		// Get the results
		return self::get_total_hours_worked($parameters);
	}

	public static function get_authors_params($parameters){
		$meta = self::get_authors_config($parameters);
		global $wpdb;
		$querystr = "SELECT SUM(k2_author_log.time_in_second) as time_in_second FROM k2_author_log WHERE  k2_author_log.author_id = %d ";
		$pageposts = $wpdb->get_results($wpdb->prepare($querystr,$parameters['id']), OBJECT);
		// Get the results
		return [
			'total_second'  => $pageposts[0]->time_in_second,
			'hourly_wage'   => $meta['hourly_wage'][0],
			'overtime_wage' => $meta['overtime_wage'][0]
		];
	}

	public static function insert_author_log($parameters){
		$queyResulets = 0;
		if( isset($parameters['time_in_second'])  && 0 < $parameters['time_in_second'] ){
			global $wpdb;
			$day_of_week = date('w', strtotime(date("m/d/Y")));
			$newDate     = strtotime ( '-'.$day_of_week.' day' , strtotime ( date('Y-m-d') ) ) ;
			$newDate     = date ( 'Y-m-d' , $newDate );
			$parameters['is_overtime'] = 0;

			  $sql = $wpdb->prepare(
                " SELECT SUM(k2_author_log.time_in_second) as time_in_second FROM k2_author_log WHERE  k2_author_log.author_id = %d  AND  DATE_FORMAT(k2_author_log.created_at, %s) > %s",
                  $parameters['author_id']
                  ,'%Y-%m-%d'
                  ,$newDate
            );

            $results = $wpdb->get_row($sql, OBJECT);
			$all_meta_for_user = get_user_meta($parameters['author_id'] );

			if( isset($results->time_in_second) && isset($all_meta_for_user['total_hours_per_month'][0]) ) {
				if($all_meta_for_user['total_hours_per_month'][0] < ($results->time_in_second * ProductivityMetricsController::HOURS_IN_SECONDS))
				{
					$parameters['is_overtime'] = 1;
				}
				else if($all_meta_for_user['total_hours_per_month'][0] < ( ($results->time_in_second + $parameters['time_in_second']) * ProductivityMetricsController::HOURS_IN_SECONDS) )
				{
					$rest_second = ( $all_meta_for_user['total_hours_per_month'][0] * 360 ) - $results->time_in_second;
					$rest = $parameters['time_in_second'] - $rest_second;
                    $per = ( 100 * $rest )/$parameters['time_in_second'];
                    $num_changed = number_format((int)($parameters['num_changed'] * $per)/100);
					$wpdb->query( $wpdb->prepare(
						" INSERT INTO `k2_author_log` ( `author_id`, `post_id`, `time_in_second` , `num_changed` , `created_at` , `is_overtime` ) VALUES ( %d, %d, %d , %d , %s, %d) ",
						$parameters['author_id'],
						$parameters['post_id'],
						$rest,
						$num_changed,
						gmdate("Y-m-d\TH:i:s\Z"),
						1
					) );
					$parameters['time_in_second']  = $rest_second;
					$parameters['num_changed']    -= $num_changed;
				}

			}
			$queyResulets = $wpdb->query( $wpdb->prepare(
				" INSERT INTO `k2_author_log` ( `author_id`, `post_id`, `time_in_second` , `num_changed` , `created_at` , `is_overtime` ) VALUES ( %d, %d, %d , %d , %s, %d) ",
				$parameters['author_id'],
				$parameters['post_id'],
				$parameters['time_in_second'],
				$parameters['num_changed'],
				gmdate("Y-m-d\TH:i:s\Z"),
				$parameters['is_overtime']
			) );
		}
		return $queyResulets;
	}

	public static function create_k2_author_log_table( ) {
		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		global $wpdb;
		$db_table_name = 'k2_author_log';

		if( $db_table_name !=  $wpdb->get_var( "SHOW TABLES LIKE '$db_table_name'" ) ) {
			if ( ! empty( $wpdb->charset ) ){
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) ){
				$charset_collate .= " COLLATE $wpdb->collate";
			}

			$sql = "CREATE TABLE IF NOT EXISTS " . $db_table_name . " (
				`ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				`author_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
				`post_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
				`created_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				`time_in_second` FLOAT NOT NULL DEFAULT '0',
				`num_changed` INT(11) NOT NULL DEFAULT '0',
				`is_overtime` INT(11) NOT NULL DEFAULT '0',
				PRIMARY KEY (`ID`)
			) $charset_collate;";

			dbDelta( $sql );
		}
	}

}
