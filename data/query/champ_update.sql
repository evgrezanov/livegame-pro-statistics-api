ALTER TABLE `wp_apl`
ADD COLUMN `min_goal` INTEGER UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE `wp_apl` ADD INDEX `min_goal`(`min_goal`);
ALTER TABLE `wp_apl` ADD COLUMN `game_id` INTEGER UNSIGNED NOT NULL DEFAULT 0, ADD INDEX `game_id`(`game_id`);

UPDATE wp_apl c1 SET `min_goal` = IF (c1.tally_goals_time_of_tally LIKE "%+%", SUBSTRING_INDEX(c1.tally_goals_time_of_tally, '+', 1) + SUBSTRING_INDEX(c1.tally_goals_time_of_tally, '+', -1), c1.tally_goals_time_of_tally) WHERE c1.tally_goals_time_of_tally != "";
UPDATE wp_apl SET game_id=SUBSTRING_INDEX(SUBSTRING_INDEX(tally_url, '/', -2),'/',1);


DELIMITER $$

DROP FUNCTION IF EXISTS `goals`$$
CREATE  FUNCTION  `goals`(curr_game_id INT, from_min INT, to_min INT) RETURNS int(11)
BEGIN

  RETURN (SELECT COUNT(game_id) FROM wp_apl WHERE `game_id`=curr_game_id AND `min_goal` >= from_min AND `min_goal`<=to_min);

END;

 $$

DELIMITER ;

DELIMITER $$

DROP FUNCTION IF EXISTS `goals_diff`$$
CREATE  FUNCTION  `goals_diff`(curr_game_id INT, to_min INT) RETURNS int(11)
BEGIN

  DECLARE last_score VARCHAR(8);
  DECLARE count_events INT;

  SELECT IF(tally_goals_tally_by_man="","0:0",tally_goals_tally_by_man) INTO last_score FROM wp_apl WHERE `game_id`=curr_game_id AND `min_goal`<=to_min ORDER BY `min_goal` DESC LIMIT 1;
  SELECT COUNT(id) INTO count_events FROM wp_apl WHERE `game_id`=curr_game_id AND `min_goal`<=to_min;

  RETURN IF(count_events=0, 0, SUBSTRING_INDEX(last_score, ':', 1)*1 - SUBSTRING_INDEX(last_score, ':', -1)*1 );

END;

 $$

DELIMITER ;

DELIMITER $$

DROP FUNCTION IF EXISTS `handicap`$$
CREATE  FUNCTION  `handicap`(curr_game_id INT, to_min INT, hand_value FLOAT) RETURNS int(11)
BEGIN


  DECLARE last_score VARCHAR(8);
  DECLARE count_events INT;
  DECLARE goals_diff INT;

  SELECT IF(tally_goals_tally_by_man="","0:0",tally_goals_tally_by_man) INTO last_score FROM wp_apl WHERE `game_id`=curr_game_id AND `min_goal`<=to_min ORDER BY `min_goal` DESC LIMIT 1;
  SELECT COUNT(id) INTO count_events FROM wp_apl WHERE `game_id`=curr_game_id AND `min_goal`<=to_min;

  SELECT IF(count_events=0, 0, SUBSTRING_INDEX(last_score, ':', 1)*1 - SUBSTRING_INDEX(last_score, ':', -1)*1 ) INTO goals_diff;

  RETURN IF (`goals_diff` + hand_value >= 0, true, false);

END;

 $$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `score`$$
CREATE  FUNCTION  `score`(curr_game_id INT, to_min INT) RETURNS varchar(8) CHARSET utf8
BEGIN

  DECLARE last_score VARCHAR(8);
  DECLARE count_events INT;

  SELECT IF(tally_goals_tally_by_man="","0:0",tally_goals_tally_by_man) INTO last_score FROM wp_apl WHERE `game_id`=curr_game_id AND `min_goal`<=to_min ORDER BY `min_goal` DESC LIMIT 1;
  SELECT COUNT(id) INTO count_events FROM wp_apl WHERE `game_id`=curr_game_id AND `min_goal`<=to_min;

  RETURN IF(count_events=0,"0:0",last_score);

END;

 $$

DELIMITER ;
