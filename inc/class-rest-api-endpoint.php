<?php

class Livegame_Rest_Api_Endpoint {

    /**
    * The init
    */
    public static function init() {
      add_action('rest_api_init', [__CLASS__, 'rest_api_init'], 0);
    }

    /**
    * init REST API by WP
    * @url http://livegame.pro/wp-json/lgp_api/v1/get_statistic/sport_type=soccer&allTeams=1&team_guest=1&team_host=1&championship=rpl&time1=63&time2=90&host_tally=2&guest_tally=0&total=0.5&handicap=0.5
    */
    public static function rest_api_init() {
      register_rest_route(
        'lgp_api/v1/',
        'get_statistic/',
        [
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => [__CLASS__, 'get_livegame_statistics'],
          //'permission_callback' => 'is_user_logged_in',
        ]
      );
    }

    /**
    *  rest API callback
    */
    public static function get_livegame_statistics(WP_REST_Request $request) {

		  /*if (empty($rest_params)) {
			  return new \WP_Error('500', 'not defined $params');
		  }*/

      // we check request for empty or wrong fields
      /*if ( !empty($_REQUEST['sport_league']) ):
        $sport_league = $_REQUEST['sport_league'];
      endif;
*/
      $params = array();
      if ( !empty($_REQUEST['sport_league']) ):
        $sport_league = $_REQUEST['sport_league'];
        $league = get_term_by('id', $sport_league, 'sports');
        $params['sport_league'] = $league->slug;
        global $wpdb;

        $table_name = $wpdb->prefix.$league->slug;

        $query  = "SELECT * FROM " .$table_name. " WHERE " ;

        $matches_total_b = $query;

        $matches_total_m = $query;

        $param_query = array();

        // +add time to query
        if ( !empty($_REQUEST['time1']) && !empty($_REQUEST['time2']) ):
            $time1 = $_REQUEST['time1'];
            $time2 = $_REQUEST['time2'];
            $params['period'] = 'c ' . $time1 . ' по ' . $time2 . ' минуту матча';
        else:
            $params['period'] = 'без учета времени';
        endif;

        // +add tally and score to query
        //if (!empty($_REQUEST['all_tally'])):
          $all_tally = (int)$_REQUEST['all_tally'];
          
          if (!$all_tally):
            // check host_tally & guest_tally
            if (!empty($host_tally = $_REQUEST['host_tally']) && !empty($guest_tally = $_REQUEST['guest_tally'])):

                $params['host_tally'] = $host_tally;
                $param_query[] = $host_tally;

                $params['guest_tally'] = $guest_tally;
                $param_query[] = $guest_tally;

                $params['tally'] = 'Текущий счет: '.$host_tally.':'.$guest_tally;
            else :
                $host_tally = $guest_tally = false;
                $params['tally'] = 'Без ограничения по счету';
            endif;
          endif;
        //endif;

        // +add score to query
        if (!empty($_REQUEST['host_tally']) && !empty($_REQUEST['guest_tally']) && !empty($_REQUEST['time1']) ):
            $host_tally = $_REQUEST['host_tally'];
            $guest_tally = $_REQUEST['guest_tally'];
            $time1 = $_REQUEST['time1'];

            $format = 'score("'.$table_name.'", game_id, %d) = "%d:%d"';
            $query_score = sprintf($format, $time1, $host_tally, $guest_tally);
            $param_query[] = stripslashes($query_score);
            $params['tally'] = $host_tally.':'.$guest_tally;
        endif;

        // +add market type and value to query
        if ( !empty($market_type = $_REQUEST['market_type']) && !empty($market_value = $_REQUEST['market_value']) ):
          switch ($market_type) {
              case "total":
                //add total to query
                $matches_total_b .= 'goals("'.$table_name.'", game_id, '.$time1.', '.$time2.') > '.$market_value;
                $matches_total_m .= 'goals("'.$table_name.'", game_id, '.$time1.', '.$time2.') < '.$market_value;
                $params['market'] = 'Тотал <strong>'. $market_value.'</strong> на '.$time2. ' минуту матча.';
                $params['market_name'] = 'Тотал';
                break;
              case "handicap":
                //add handicap to query
                if (!empty($market_value) && !empty($time2)):
                  //$query_handikap = 'handicap("'.$table_name.'", game_id, '.$time2.', '.$market_value.')';
                  $matches_total_b .= 'handicap("'.$table_name.'", game_id, '.$time2.', '.$market_value.')';
                  $matches_total_m .= 'handicap2("'.$table_name.'", game_id, '.$time2.', '.$market_value.')';
                  $params['market'] = 'Фора хозяев <strong>'. $market_value.'</strong> на '.$time2. ' минуту матча.';
                  $params['market_name'] = 'Фора';
                endif;
                break;
              case "handicap2":
                //add handicap2 to query
                if (!empty($market_value) && !empty($time2)):
                  //$query_handikap = 'handicap2("'.$table_name.'", game_id, '.$time2.', '.$market_value.')';
                  $matches_total_b .= 'handicap2("'.$table_name.'", game_id, '.$time2.', '.$market_value.')';
                  $matches_total_m .= 'handicap("'.$table_name.'", game_id, '.$time2.', '.$market_value.')';
                  $params['market'] = 'Фора гостей <strong>'. $market_value.'</strong> на '.$time2. ' минуту матча.';
                  $params['market_name'] = 'Фора';
                endif;
                break;
          }
          //$params['market_type'] = $market_type;
          //$params['market_value'] = $market_value;
        endif;

        // if checkbox "all teams" not checked
        // не тестировал
        //if (!empty($_REQUEST["allTeams"])):
          //$allTeams = $_REQUEST['allTeams'];

            // $query_team1
            if (!empty($_REQUEST['host'])):
              //Host team
              $query_team1 = " team1_id = ".$_REQUEST['host']." ";
              $param_query[] = $query_team1;
              $params['host_team'] = $_REQUEST['host'];
            endif;

            // $query_team2
            if (!empty($_REQUEST['quest'])):
              //Quest team
              $query_team2 = " team2_id = ".$_REQUEST['quest']." ";
              $param_query[] = $query_team2;
              $params['quest_team'] = $_REQUEST['quest'];
            endif;
        //endif;
        
        //get last key of array
        end($param_query);
        $last_key = key($param_query);
        reset($param_query);
        // add all parameters to query
        foreach($param_query as $key => $element) {
              $item =  " AND "  . $element;
              $matches_total_b .= $item;
              $matches_total_m .= $item;
        }

        $matches_total_b_game = $matches_total_b . ' GROUP BY game_id';
        $matches_total_m_game = $matches_total_m . ' GROUP BY game_id';
        
        //SELECT id, game_id, team1, tally_goals_time_of_tally, tally_name FROM wp_rpl WHERE score(game_id, 80) = "1:1" AND goals(game_id, 80, 90) > 0.5 AND score(game_id, 80) = "1:1""
        $matches_total_b_r = $wpdb->get_results($matches_total_b);
        $total_b = count($matches_total_b_r);
        $total_games_b = $wpdb->get_results($matches_total_b_game);
        $total_games_b_c = count($total_games_b);

        //SELECT id, game_id, team1, tally_goals_time_of_tally, tally_name FROM wp_rpl WHERE score(game_id, 80) = "1:1" AND goals(game_id, 80, 90) > 0.5 AND score(game_id, 80) = "1:1""
        $matches_total_m_r = $wpdb->get_results($matches_total_m);
        $total_m = count($matches_total_m_r);
        $total_games_m = $wpdb->get_results($matches_total_m_game);
        $total_games_m_c = count($total_games_m);

        $term = get_term_by('slug', $sport_league, 'sports');
        $params['sports_name'] = $league->name;
        $params['sports_url'] = get_term_link($league->term_id, 'sports');

        $min_kf =1+(1/10*$total_games_m_c+$total_games_m_c)/($total_games_b_c+1);
        $total = $total_games_b_c + $total_games_m_c;
        
        //$total_b_prcnt = $total_games_b_c/$total*100;
        //$total_b_prcnt = round($total_b_prcnt, 0, PHP_ROUND_HALF_UP);

        //$total_m_prcnt = $total_games_m_c/$total*100;
        //$total_m_prcnt = round($total_m_prcnt, 0, PHP_ROUND_HALF_UP);

        $response = [
          'result'                => 'success',
          'params'                => $params,
          'min_kf'                => round($min_kf, 2, PHP_ROUND_HALF_UP),
          'total_b_count'         => count($matches_total_b_r),
          'total_b'               => $matches_total_b_r,
          'totab_b_query'         => $matches_total_b,
          'total_b_prcnt'         => round($total_b_prcnt, 0, PHP_ROUND_HALF_UP).'%',
          'total_game_b'          => $total_games_b_c,
          'matches_total_b_game'  => $matches_total_b_game,
          'total_m_count'         => count($matches_total_m_r),
          'total_m_prcnt'         => round($total_m_prcnt, 0, PHP_ROUND_HALF_UP).'%',
          'total_game_m'          => $total_games_m_c,
          'matches_total_m_game'  => $matches_total_m_game,
          'total_m'               => $matches_total_m_r,
          'totab_m_query'         => $matches_total_m,
          'message'               => 'Данные загружены',
          'redirect'              => '',
        ];

        //$response = new WP_REST_Response($response, 200);
        // Set headers.
        //$response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);

        //wp_send_json($response);
        return new \WP_REST_Response($response, 200);

      else:

        return new \WP_Error('500', 'something wrong!');

      endif;
    }
}

Livegame_Rest_Api_Endpoint::init();