SELECT id, team1, team2, tally_goals_tally_by_man, tally_goals_time_of_tally, tally_name,
       IF (`goals_diff`-0.5 >= 0, true, false) as handicap_minus_0_5,
       IF (`goals_diff`-1.5 >= 0, true, false) as handicap_minus_1_5,
       IF (`goals_diff`-2.5 >= 0, true, false) as handicap_minus_2_5,
       IF (`goals_diff`-3.5 >= 0, true, false) as handicap_minus_3_5,
       IF (`goals_diff`-4.5 >= 0, true, false) as handicap_minus_4_5,
       IF (`goals_diff`-5.5 >= 0, true, false) as handicap_minus_5_5,
       IF (`goals_diff`-6.5 >= 0, true, false) as handicap_minus_6_5,
       IF (`goals_diff`+0.5 >= 0, true, false) as handicap_plus_0_5,
       IF (`goals_diff`+1.5 >= 0, true, false) as handicap_plus_1_5,
       IF (`goals_diff`+2.5 >= 0, true, false) as handicap_plus_2_5,
       IF (`goals_diff`+3.5 >= 0, true, false) as handicap_plus_3_5,
       IF (`goals_diff`+4.5 >= 0, true, false) as handicap_plus_4_5,
       IF (`goals_diff`+5.5 >= 0, true, false) as handicap_plus_5_5,
       IF (`goals_diff`+6.5 >= 0, true, false) as handicap_plus_6_5,
       IF (`goals` >= 1, true, false) as total_more_0_5,
       IF (`goals` >= 2, true, false) as total_more_1_5,
       IF (`goals` >= 3, true, false) as total_more_2_5,
       IF (`goals` >= 4, true, false) as total_more_3_5,
       IF (`goals` >= 5, true, false) as total_more_4_5,
       IF (`goals` >= 6, true, false) as total_more_5_5,
       IF (`goals` >= 7, true, false) as total_more_6_5,
       IF (`goals` >= 8, true, false) as total_more_7_5,
       IF (`goals` >= 9, true, false) as total_more_8_5,
       IF (`goals` >= 10, true, false) as total_more_9_5,
       IF (`goals` < 1, true, false) as total_less_0_5,
       IF (`goals` < 2, true, false) as total_less_1_5,
       IF (`goals` < 3, true, false) as total_less_2_5,
       IF (`goals` < 4, true, false) as total_less_3_5,
       IF (`goals` < 5, true, false) as total_less_4_5,
       IF (`goals` < 6, true, false) as total_less_5_5,
       IF (`goals` < 7, true, false) as total_less_6_5,
       IF (`goals` < 8, true, false) as total_less_7_5,
       IF (`goals` < 9, true, false) as total_less_8_5
FROM (
     SELECT
         *,
         goals(game_id, 60, 99) as goals,
         goals_diff(game_id, 99) as goals_diff
     FROM
         wp_rpl
     WHERE
             score(game_id, 60) = "1:1"
     GROUP BY
         game_id
     ) a

##############################
# тотал больше 0.5 - левый столбец
# для Динамо М - хозяева
# c 1 по 90 минуты
SELECT
	id,
	game_id,
	team1,
	team2,
	tally_goals_tally_by_man,
	tally_goals_time_of_tally,
	tally_name,
  #goals(game_id, 1, 90)>0.5 as total
FROM
	wp_rpl
WHERE
	score(game_id, 1) = "0:0"
AND
	goals(game_id, 1, 90)>0.5
AND
	team1 = "Динамо М"
group by
	game_id

###############################
# тотал меньше 0.5 - правый столбец
# для Динамо М - хозяева
# c 1 по 90 минуты
SELECT
	id,
	game_id,
	team1,
	team2,
	tally_goals_tally_by_man,
	tally_goals_time_of_tally,
	tally_name,
  #goals(game_id, 1, 90)<0.5 as total
FROM
	wp_rpl
WHERE
	score(game_id, 1) = "0:0"
AND
	goals(game_id, 1, 90)<0.5
AND
	team1 = "Динамо М"
group by
	game_id
