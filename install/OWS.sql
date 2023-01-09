SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
CREATE TABLE IF NOT EXISTS `_achievement` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `user_id` int(10) DEFAULT NULL, `team_id` int(10) DEFAULT NULL, `season_id` int(10) DEFAULT NULL, `cup_round_id` int(10) DEFAULT NULL, `rank` tinyint(3) DEFAULT NULL, `date_recorded` int(10) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `user_id` (`user_id`), KEY `team_id` (`team_id`), KEY `season_id` (`season_id`), KEY `cup_round_id` (`cup_round_id`),
			CONSTRAINT `_achievement_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE, CONSTRAINT `_achievement_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_achievement_ibfk_3` FOREIGN KEY (`season_id`) REFERENCES `_saison` (`id`) ON DELETE CASCADE, CONSTRAINT `_achievement_ibfk_4` FOREIGN KEY (`cup_round_id`) REFERENCES `_cup_round` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `achievement` LIKE `_achievement`;
				CREATE TABLE IF NOT EXISTS `ws3_achievement` LIKE `_achievement`;
CREATE TABLE IF NOT EXISTS `_admin` (
	`id` smallint(5) NOT NULL AUTO_INCREMENT, `name` varchar(40) DEFAULT NULL, `passwort` varchar(64) DEFAULT NULL, `passwort_neu` varchar(64) DEFAULT NULL, `passwort_neu_angefordert` int(11) DEFAULT 0, `passwort_salt` varchar(5) DEFAULT NULL,
	`email` varchar(100) DEFAULT NULL, `lang` varchar(2) DEFAULT NULL, `r_admin` enum('1','0') DEFAULT '0', `r_adminuser` enum('1','0') DEFAULT '0', `r_user` enum('1','0') DEFAULT '0', `r_daten` enum('1','0') DEFAULT '0', `r_staerken` enum('1','0') DEFAULT '0',
	`r_spiele` enum('1','0') DEFAULT '0', `r_news` enum('1','0') DEFAULT '0', `r_faq` enum('1','0') DEFAULT '0', `r_umfrage` enum('1','0') DEFAULT '0', `r_kalender` enum('1','0') DEFAULT '0', `r_seiten` enum('1','0') DEFAULT '0', `r_design` enum('1','0') DEFAULT '0',
	`r_demo` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `admin` LIKE `_admin`;
				CREATE TABLE IF NOT EXISTS `ws3_admin` LIKE `_admin`;
CREATE TABLE IF NOT EXISTS `_aufstellung` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `verein_id` int(10) DEFAULT NULL, `datum` int(11) DEFAULT NULL, `offensive` tinyint(3) DEFAULT 50, `spieler1` int(10) DEFAULT NULL, `spieler2` int(10) DEFAULT NULL, `spieler3` int(10) DEFAULT NULL, `spieler4` int(10) DEFAULT NULL,
	`spieler5` int(10) DEFAULT NULL, `spieler6` int(10) DEFAULT NULL, `spieler7` int(10) DEFAULT NULL, `spieler8` int(10) DEFAULT NULL, `spieler9` int(10) DEFAULT NULL, `spieler10` int(10) DEFAULT NULL, `spieler11` int(10) DEFAULT NULL, `ersatz1` int(10) DEFAULT NULL,
	`ersatz2` int(10) DEFAULT NULL, `ersatz3` int(10) DEFAULT NULL, `ersatz4` int(10) DEFAULT NULL, `ersatz5` int(10) DEFAULT NULL, `ersatz6` int(10) DEFAULT NULL, `ersatz7` int(10) DEFAULT NULL, `ersatz8` int(10) DEFAULT NULL, `ersatz9` int(10) DEFAULT NULL,
	`w1_raus` int(10) DEFAULT NULL, `w1_rein` int(10) DEFAULT NULL, `w1_minute` tinyint(2) DEFAULT NULL, `w2_raus` int(10) DEFAULT NULL, `w2_rein` int(10) DEFAULT NULL, `w2_minute` tinyint(2) DEFAULT NULL, `w3_raus` int(10) DEFAULT NULL, `w3_rein` int(10) DEFAULT NULL,
	`w3_minute` tinyint(2) DEFAULT NULL, `w4_raus` int(10) DEFAULT NULL, `w4_rein` int(10) DEFAULT NULL, `w4_minute` tinyint(2) DEFAULT NULL, `w5_raus` int(10) DEFAULT NULL, `w5_rein` int(10) DEFAULT NULL, `w5_minute` tinyint(2) DEFAULT NULL, `w6_raus` int(10) DEFAULT NULL,
	`w6_rein` int(10) DEFAULT NULL, `w6_minute` tinyint(2) DEFAULT NULL, `setup` varchar(16) DEFAULT NULL, `w1_condition` varchar(16) DEFAULT NULL, `w2_condition` varchar(16) DEFAULT NULL, `w3_condition` varchar(16) DEFAULT NULL, `w4_condition` varchar(16) DEFAULT NULL,
	`w5_condition` varchar(16) DEFAULT NULL, `w6_condition` varchar(16) DEFAULT NULL, `longpasses` enum('1','0') DEFAULT '0', `counterattacks` enum('1','0') DEFAULT '0', `freekickplayer` int(10) DEFAULT NULL, `w1_position` varchar(4) DEFAULT NULL,
	`w2_position` varchar(4) DEFAULT NULL, `w3_position` varchar(4) DEFAULT NULL, `w4_position` varchar(4) DEFAULT NULL, `w5_position` varchar(4) DEFAULT NULL, `w6_position` varchar(4) DEFAULT NULL, `spieler1_position` varchar(4) DEFAULT NULL,
	`spieler2_position` varchar(4) DEFAULT NULL, `spieler3_position` varchar(4) DEFAULT NULL, `spieler4_position` varchar(4) DEFAULT NULL, `spieler5_position` varchar(4) DEFAULT NULL, `spieler6_position` varchar(4) DEFAULT NULL, `spieler7_position` varchar(4) DEFAULT NULL,
	`spieler8_position` varchar(4) DEFAULT NULL, `spieler9_position` varchar(4) DEFAULT NULL, `spieler10_position` varchar(4) DEFAULT NULL, `spieler11_position` varchar(4) DEFAULT NULL,
	`match_id` int(10) DEFAULT NULL, `templatename` varchar(24) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `match_id` (`match_id`), KEY `_aufstellung_verein_id_fk` (`verein_id`),
			CONSTRAINT `_aufstellung_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `_spiel` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_aufstellung_verein_id_fk` FOREIGN KEY (`verein_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `aufstellung` LIKE `_aufstellung`;
				CREATE TABLE IF NOT EXISTS `ws3_aufstellung` LIKE `_aufstellung`;
CREATE TABLE IF NOT EXISTS `_badge` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(128) DEFAULT NULL, `description` varchar(255) DEFAULT NULL, `level` enum('bronze','silver','gold') DEFAULT 'bronze', `event` enum('membership_since_x_days','win_with_x_goals_difference',
	'completed_season_at_x','x_trades','cupwinner','stadium_construction_by_x') DEFAULT NULL, `event_benchmark` int(10) DEFAULT 0,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `badge` LIKE `_badge`;
				CREATE TABLE IF NOT EXISTS `ws3_badge` LIKE `_badge`;
CREATE TABLE IF NOT EXISTS `_badge_user` (
	`user_id` int(10) NOT NULL, `badge_id` int(10) NOT NULL, `date_rewarded` int(10) DEFAULT NULL,
		PRIMARY KEY (`user_id`,`badge_id`), KEY `badge_id` (`badge_id`),
			CONSTRAINT `_badge_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_badge_user_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `_badge` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `badge_user` LIKE `_badge_user`;
				CREATE TABLE IF NOT EXISTS `ws3_badge_user` LIKE `_badge_user`;
CREATE TABLE IF NOT EXISTS `_briefe` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `empfaenger_id` int(10) DEFAULT NULL, `absender_id` int(10) DEFAULT NULL, `absender_name` varchar(50) DEFAULT NULL, `datum` int(10) DEFAULT NULL, `betreff` varchar(50) DEFAULT NULL, `nachricht` text DEFAULT NULL,
	`gelesen` enum('1','0') DEFAULT '0', `typ` enum('eingang','ausgang') DEFAULT 'eingang',
		PRIMARY KEY (`id`), KEY `_briefe_user_id_fk` (`absender_id`),
			CONSTRAINT `_briefe_user_id_fk` FOREIGN KEY (`absender_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `briefe` LIKE `_briefe`;
				CREATE TABLE IF NOT EXISTS `ws3_briefe` LIKE `_briefe`;
CREATE TABLE IF NOT EXISTS `_buildings_of_team` (
	`building_id` int(10) NOT NULL, `team_id` int(10) NOT NULL, `construction_deadline` int(11) DEFAULT NULL,
		PRIMARY KEY (`building_id`,`team_id`), KEY `team_id` (`team_id`),
			CONSTRAINT `_buildings_of_team_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `_stadiumbuilding` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_buildings_of_team_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `buildings_of_team` LIKE `_buildings_of_team`;
				CREATE TABLE IF NOT EXISTS `ws3_buildings_of_team` LIKE `_buildings_of_team`;
CREATE TABLE IF NOT EXISTS `_cup` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(64) DEFAULT NULL, `winner_id` int(10) DEFAULT NULL, `logo` varchar(128) DEFAULT NULL, `winner_award` int(10) DEFAULT 0, `second_award` int(10) DEFAULT 0, `perround_award` int(10) DEFAULT 0,
	`archived` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`),
		UNIQUE KEY `name` (`name`), KEY `_cup_winner_id_fk` (`winner_id`),
			CONSTRAINT `_cup_winner_id_fk` FOREIGN KEY (`winner_id`) REFERENCES `_verein` (`id`) ON DELETE SET NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `cup` LIKE `_cup`;
				CREATE TABLE IF NOT EXISTS `ws3_cup` LIKE `_cup`;
CREATE TABLE IF NOT EXISTS `_cup_round` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `cup_id` int(10) DEFAULT NULL, `name` varchar(64) DEFAULT NULL, `from_winners_round_id` int(10) DEFAULT NULL, `from_loosers_round_id` int(10) DEFAULT NULL, `firstround_date` int(11) DEFAULT NULL,
	`secondround_date` int(11) DEFAULT NULL, `finalround` enum('1','0') DEFAULT '0', `groupmatches` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_cupround_cup_id_fk` (`cup_id`), KEY `_cupround_fromwinners_id_fk` (`from_winners_round_id`), KEY `_cupround_fromloosers_id_fk` (`from_loosers_round_id`),
			CONSTRAINT `_cupround_cup_id_fk` FOREIGN KEY (`cup_id`) REFERENCES `_cup` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_cupround_fromloosers_id_fk` FOREIGN KEY (`from_loosers_round_id`) REFERENCES `_cup_round` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_cupround_fromwinners_id_fk` FOREIGN KEY (`from_winners_round_id`) REFERENCES `_cup_round` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `cup_round` LIKE `_cup_round`;
				CREATE TABLE IF NOT EXISTS `ws3_cup_round` LIKE `_cup_round`;
CREATE TABLE IF NOT EXISTS `_cup_round_group` (
	`cup_round_id` int(10) NOT NULL, `team_id` int(10) NOT NULL, `name` varchar(64) DEFAULT NULL, `tab_points` int(4) DEFAULT 0, `tab_goals` int(4) DEFAULT 0, `tab_goalsreceived` int(4) DEFAULT 0, `tab_wins` int(4) DEFAULT 0, `tab_draws` int(4) DEFAULT 0,
	`tab_losses` int(4) DEFAULT 0,
		PRIMARY KEY (`cup_round_id`,`team_id`), KEY `_cupgroup_team_id_fk` (`team_id`),
			CONSTRAINT `_cupgroup_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `cup_round_group` LIKE `_cup_round_group`;
				CREATE TABLE IF NOT EXISTS `ws3_cup_round_group` LIKE `_cup_round_group`;
CREATE TABLE IF NOT EXISTS `_cup_round_group_next` (
	`cup_round_id` int(10) NOT NULL, `groupname` varchar(64) NOT NULL, `rank` int(4) NOT NULL DEFAULT 0, `target_cup_round_id` int(10) DEFAULT NULL,
		PRIMARY KEY (`cup_round_id`,`groupname`,`rank`), KEY `_groupnext_tagetround_fk` (`target_cup_round_id`),
			CONSTRAINT `_groupnext_round_fk` FOREIGN KEY (`cup_round_id`) REFERENCES `_cup_round` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_groupnext_tagetround_fk` FOREIGN KEY (`target_cup_round_id`) REFERENCES `_cup_round` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `cup_round_group_next` LIKE `_cup_round_group_next`;
				CREATE TABLE IF NOT EXISTS `ws3_cup_round_group_next` LIKE `_cup_round_group_next`;
CREATE TABLE IF NOT EXISTS `_cup_round_pending` (
	`team_id` int(10) NOT NULL, `cup_round_id` int(10) NOT NULL,
		PRIMARY KEY (`team_id`,`cup_round_id`), KEY `_cuproundpending_round_fk` (`cup_round_id`),
			CONSTRAINT `_cuproundpending_round_fk` FOREIGN KEY (`cup_round_id`) REFERENCES `_cup_round` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_cuproundpending_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `cup_round_pending` LIKE `_cup_round_pending`;
				CREATE TABLE IF NOT EXISTS `ws3_cup_round_pending` LIKE `_cup_round_pending`;
CREATE TABLE IF NOT EXISTS `_konto` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `verein_id` int(10) DEFAULT NULL, `absender` varchar(150) DEFAULT NULL, `betrag` int(10) DEFAULT NULL, `datum` int(11) DEFAULT NULL, `verwendung` varchar(200) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `_konto_verein_id_fk` (`verein_id`),
			CONSTRAINT `_konto_verein_id_fk` FOREIGN KEY (`verein_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `konto` LIKE `_konto`;
				CREATE TABLE IF NOT EXISTS `ws3_konto` LIKE `_konto`;
CREATE TABLE IF NOT EXISTS `_leaguehistory` (
	`team_id` int(10) NOT NULL, `season_id` int(10) NOT NULL, `user_id` int(10) DEFAULT NULL, `matchday` tinyint(3) NOT NULL, `rank` tinyint(3) DEFAULT NULL,
		PRIMARY KEY (`team_id`,`season_id`,`matchday`), KEY `season_id` (`season_id`), KEY `user_id` (`user_id`),
			CONSTRAINT `_leaguehistory_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_leaguehistory_ibfk_2` FOREIGN KEY (`season_id`) REFERENCES `_saison` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_leaguehistory_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE SET NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `leaguehistory` LIKE `_leaguehistory`;
				CREATE TABLE IF NOT EXISTS `ws3_leaguehistory` LIKE `_leaguehistory`;
CREATE TABLE IF NOT EXISTS `_liga` (
	`id` smallint(5) NOT NULL AUTO_INCREMENT, `name` varchar(50) DEFAULT NULL, `kurz` varchar(5) DEFAULT NULL, `land` varchar(25) DEFAULT NULL, `p_steh` tinyint(3) DEFAULT NULL, `p_sitz` tinyint(3) DEFAULT NULL, `p_haupt_steh` tinyint(3) DEFAULT NULL,
	`p_haupt_sitz` tinyint(3) DEFAULT NULL, `p_vip` tinyint(3) DEFAULT NULL, `preis_steh` smallint(5) DEFAULT NULL, `preis_sitz` smallint(5) DEFAULT NULL, `preis_vip` smallint(5) DEFAULT NULL, `admin_id` smallint(5) DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `liga` LIKE `_liga`;
				CREATE TABLE IF NOT EXISTS `ws3_liga` LIKE `_liga`;
CREATE TABLE IF NOT EXISTS `_matchreport` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `match_id` int(10) DEFAULT NULL, `message_id` int(10) DEFAULT NULL, `minute` tinyint(3) DEFAULT NULL, `goals` varchar(8) DEFAULT NULL, `playernames` varchar(128) DEFAULT NULL, `active_home` tinyint(1) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `_matchreport_spiel_id_fk` (`match_id`), KEY `_matchreport_message_id_fk` (`message_id`),
			CONSTRAINT `_matchreport_message_id_fk` FOREIGN KEY (`message_id`) REFERENCES `_spiel_text` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_matchreport_spiel_id_fk` FOREIGN KEY (`match_id`) REFERENCES `_spiel` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `matchreport` LIKE `_matchreport`;
				CREATE TABLE IF NOT EXISTS `ws3_matchreport` LIKE `_matchreport`;
CREATE TABLE IF NOT EXISTS `_nationalplayer` (
	`team_id` int(10) NOT NULL, `player_id` int(10) NOT NULL,
		PRIMARY KEY (`team_id`,`player_id`), KEY `_nationalp_player_id_fk` (`player_id`),
			CONSTRAINT `_nationalp_player_id_fk` FOREIGN KEY (`player_id`) REFERENCES `_spieler` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_nationalp_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `nationalplayer` LIKE `_nationalplayer`;
				CREATE TABLE IF NOT EXISTS `ws3_nationalplayer` LIKE `_nationalplayer`;
CREATE TABLE IF NOT EXISTS `_news` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `datum` int(10) DEFAULT NULL, `autor_id` smallint(5) DEFAULT NULL, `bild_id` int(10) DEFAULT NULL, `titel` varchar(100) DEFAULT NULL, `nachricht` text DEFAULT NULL, `linktext1` varchar(100) DEFAULT NULL,
	`linkurl1` varchar(250) DEFAULT NULL, `linktext2` varchar(100) DEFAULT NULL, `linkurl2` varchar(250) DEFAULT NULL, `linktext3` varchar(100) DEFAULT NULL, `linkurl3` varchar(250) DEFAULT NULL, `c_br` enum('1','0') DEFAULT '0', `c_links` enum('1','0') DEFAULT '0',
	`c_smilies` enum('1','0') DEFAULT '0', `status` enum('1','2','0') DEFAULT '0',
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `news` LIKE `_news`;
				CREATE TABLE IF NOT EXISTS `ws3_news` LIKE `_news`;
CREATE TABLE IF NOT EXISTS `_notification` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `user_id` int(10) DEFAULT NULL, `eventdate` int(11) DEFAULT NULL, `eventtype` varchar(128) DEFAULT NULL, `message_key` varchar(255) DEFAULT NULL, `message_data` varchar(255) DEFAULT NULL, `target_pageid` varchar(128) DEFAULT NULL,
	`target_querystr` varchar(255) DEFAULT NULL, `seen` enum('1','0') DEFAULT '0', `team_id` int(10) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `team_id` (`team_id`), KEY `_notification_user_id_fk` (`user_id`),
			CONSTRAINT `_notification_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_notification_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `notification` LIKE `_notification`;
				CREATE TABLE IF NOT EXISTS `ws3_notification` LIKE `_notification`;
CREATE TABLE IF NOT EXISTS `_premiumpayment` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `user_id` int(10) DEFAULT NULL, `amount` int(10) DEFAULT NULL, `created_date` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `_premiumpayment_user_id_fk` (`user_id`),
			CONSTRAINT `_premiumpayment_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `premiumpayment` LIKE `_premiumpayment`;
				CREATE TABLE IF NOT EXISTS `ws3_premiumpayment` LIKE `_premiumpayment`;
CREATE TABLE IF NOT EXISTS `_premiumstatement` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `user_id` int(10) DEFAULT NULL, `action_id` varchar(255) DEFAULT NULL, `amount` int(10) DEFAULT NULL, `created_date` int(11) DEFAULT NULL, `subject_data` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `_premium_user_id_fk` (`user_id`),
			CONSTRAINT `_premium_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `premiumstatement` LIKE `_premiumstatement`;
				CREATE TABLE IF NOT EXISTS `ws3_premiumstatement` LIKE `_premiumstatement`;
CREATE TABLE IF NOT EXISTS `_randomevent` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `message` varchar(255) DEFAULT NULL, `effect` enum('money','player_injured','player_blocked','player_happiness','player_fitness','player_stamina') DEFAULT NULL, `effect_money_amount` int(10) DEFAULT 0,
	`effect_blocked_matches` int(10) DEFAULT 0, `effect_skillchange` tinyint(3) DEFAULT 0, `weight` tinyint(3) DEFAULT 1,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `randomevent` LIKE `_randomevent`;
				CREATE TABLE IF NOT EXISTS `ws3_randomevent` LIKE `_randomevent`;
CREATE TABLE IF NOT EXISTS `_randomevent_occurrence` (
	`user_id` int(10) NOT NULL, `team_id` int(10) NOT NULL, `event_id` int(10) DEFAULT NULL, `occurrence_date` int(10) NOT NULL,
		PRIMARY KEY (`user_id`,`team_id`,`occurrence_date`), KEY `team_id` (`team_id`), KEY `event_id` (`event_id`),
			CONSTRAINT `_randomevent_occurrence_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_randomevent_occurrence_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_randomevent_occurrence_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `_randomevent` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `randomevent_occurrence` LIKE `_randomevent_occurrence`;
				CREATE TABLE IF NOT EXISTS `ws3_randomevent_occurrence` LIKE `_randomevent_occurrence`;
CREATE TABLE IF NOT EXISTS `_saison` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(20) DEFAULT NULL, `liga_id` smallint(5) DEFAULT NULL, `platz_1_id` int(10) DEFAULT NULL, `platz_2_id` int(10) DEFAULT NULL, `platz_3_id` int(10) DEFAULT NULL, `platz_4_id` int(10) DEFAULT NULL,
	`platz_5_id` int(10) DEFAULT NULL, `platz_6_id` int(10) DEFAULT NULL, `platz_7_id` int(10) DEFAULT NULL, `platz_8_id` int(10) DEFAULT NULL, `platz_9_id` int(10) DEFAULT NULL, `platz_10_id` int(10) DEFAULT NULL, `platz_11_id` int(10) DEFAULT NULL,
	`platz_12_id` int(10) DEFAULT NULL, `platz_13_id` int(10) DEFAULT NULL, `platz_14_id` int(10) DEFAULT NULL, `platz_15_id` int(10) DEFAULT NULL, `platz_16_id` int(10) DEFAULT NULL, `platz_17_id` int(10) DEFAULT NULL, `platz_18_id` int(10) DEFAULT NULL,
	`platz_19_id` int(10) DEFAULT NULL, `platz_20_id` int(10) DEFAULT NULL, `beendet` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `saison` LIKE _saison;
				CREATE TABLE IF NOT EXISTS `ws3_saison` LIKE _saison;
CREATE TABLE IF NOT EXISTS `_session` (
	`session_id` char(32) NOT NULL, `session_data` text DEFAULT NULL, `expires` int(11) DEFAULT NULL,
		PRIMARY KEY (`session_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `session` LIKE `_session`;
				CREATE TABLE IF NOT EXISTS `ws3_session` LIKE `_session`;
CREATE TABLE IF NOT EXISTS `_shoutmessage` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `user_id` int(10) DEFAULT NULL, `message` varchar(255) DEFAULT NULL, `created_date` int(11) DEFAULT NULL, `match_id` int(10) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `user_id` (`user_id`), KEY `match_id` (`match_id`),
			CONSTRAINT `_shoutmessage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_shoutmessage_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `_spiel` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `shoutmessage` LIKE `_shoutmessage`;
				CREATE TABLE IF NOT EXISTS `ws3_shoutmessage` LIKE `_shoutmessage`;
CREATE TABLE IF NOT EXISTS `_spiel` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `spieltyp` enum('Ligaspiel','Pokalspiel','Freundschaft') DEFAULT NULL DEFAULT 'Ligaspiel', `elfmeter` enum('1','0') DEFAULT NULL DEFAULT '0', `pokalname` varchar(30) DEFAULT NULL, `pokalrunde` varchar(30) DEFAULT NULL,
	`pokalgruppe` varchar(64) DEFAULT NULL, `liga_id` smallint(5) DEFAULT NULL, `saison_id` int(10) DEFAULT NULL, `spieltag` tinyint(3) DEFAULT NULL, `datum` int(10) DEFAULT NULL, `stadion_id` int(10) DEFAULT NULL, `minutes` tinyint(3) DEFAULT NULL,
	`player_with_ball` int(10) DEFAULT NULL, `prev_player_with_ball` int(10) DEFAULT NULL, `home_verein` int(10) DEFAULT NULL, `home_noformation` enum('1','0') DEFAULT '0', `home_offensive` tinyint(3) DEFAULT NULL, `home_offensive_changed` tinyint(2) DEFAULT NULL DEFAULT 0,
	`home_tore` tinyint(2) DEFAULT NULL, `home_spieler1` int(10) DEFAULT NULL, `home_spieler2` int(10) DEFAULT NULL, `home_spieler3` int(10) DEFAULT NULL, `home_spieler4` int(10) DEFAULT NULL, `home_spieler5` int(10) DEFAULT NULL, `home_spieler6` int(10) DEFAULT NULL,
	`home_spieler7` int(10) DEFAULT NULL, `home_spieler8` int(10) DEFAULT NULL, `home_spieler9` int(10) DEFAULT NULL, `home_spieler10` int(10) DEFAULT NULL, `home_spieler11` int(10) DEFAULT NULL, `home_ersatz1` int(10) DEFAULT NULL, `home_ersatz2` int(10) DEFAULT NULL,
	`home_ersatz3` int(10) DEFAULT NULL, `home_ersatz4` int(10) DEFAULT NULL, `home_ersatz5` int(10) DEFAULT NULL, `home_ersatz6` int(10) DEFAULT NULL, `home_ersatz7` int(10) DEFAULT NULL, `home_ersatz8` int(10) DEFAULT NULL, `home_ersatz9` int(10) DEFAULT NULL,
  	`home_w1_raus` int(10) DEFAULT NULL, `home_w1_rein` int(10) DEFAULT NULL, `home_w1_minute` tinyint(2) DEFAULT NULL, `home_w2_raus` int(10) DEFAULT NULL, `home_w2_rein` int(10) DEFAULT NULL, `home_w2_minute` tinyint(2) DEFAULT NULL, `home_w3_raus` int(10) DEFAULT NULL,
	`home_w3_rein` int(10) DEFAULT NULL, `home_w3_minute` tinyint(2) DEFAULT NULL, `home_w4_raus` int(10) DEFAULT NULL, `home_w4_rein` int(10) DEFAULT NULL, `home_w4_minute` tinyint(2) DEFAULT NULL, `home_w5_raus` int(10) DEFAULT NULL, `home_w5_rein` int(10) DEFAULT NULL,
	`home_w5_minute` tinyint(2) DEFAULT NULL, `home_w6_raus` int(10) DEFAULT NULL, `home_w6_rein` int(10) DEFAULT NULL, `home_w6_minute` tinyint(2) DEFAULT NULL, `gast_verein` int(10) DEFAULT NULL, `gast_tore` tinyint(2) DEFAULT NULL,
	`guest_noformation` enum('1','0') DEFAULT '0', `gast_offensive` tinyint(3) DEFAULT NULL, `gast_offensive_changed` tinyint(2) DEFAULT NULL DEFAULT 0, `gast_spieler1` int(10) DEFAULT NULL, `gast_spieler2` int(10) DEFAULT NULL, `gast_spieler3` int(10) DEFAULT NULL,
	`gast_spieler4` int(10) DEFAULT NULL, `gast_spieler5` int(10) DEFAULT NULL, `gast_spieler6` int(10) DEFAULT NULL, `gast_spieler7` int(10) DEFAULT NULL, `gast_spieler8` int(10) DEFAULT NULL, `gast_spieler9` int(10) DEFAULT NULL, `gast_spieler10` int(10) DEFAULT NULL,
	`gast_spieler11` int(10) DEFAULT NULL, `gast_ersatz1` int(10) DEFAULT NULL, `gast_ersatz2` int(10) DEFAULT NULL, `gast_ersatz3` int(10) DEFAULT NULL, `gast_ersatz4` int(10) DEFAULT NULL, `gast_ersatz5` int(10) DEFAULT NULL, `gast_ersatz6` int(10) DEFAULT NULL,
	`gast_ersatz7` int(10) DEFAULT NULL, `gast_ersatz8` int(10) DEFAULT NULL, `gast_ersatz9` int(10) DEFAULT NULL, `gast_w1_raus` int(10) DEFAULT NULL, `gast_w1_rein` int(10) DEFAULT NULL, `gast_w1_minute` tinyint(2) DEFAULT NULL, `gast_w2_raus` int(10) DEFAULT NULL,
	`gast_w2_rein` int(10) DEFAULT NULL, `gast_w2_minute` tinyint(2) DEFAULT NULL, `gast_w3_raus` int(10) DEFAULT NULL, `gast_w3_rein` int(10) DEFAULT NULL, `gast_w3_minute` tinyint(2) DEFAULT NULL, `gast_w4_raus` int(10) DEFAULT NULL, `gast_w4_rein` int(10) DEFAULT NULL,
	`gast_w4_minute` tinyint(2) DEFAULT NULL, `gast_w5_raus` int(10) DEFAULT NULL, `gast_w5_rein` int(10) DEFAULT NULL, `gast_w5_minute` tinyint(2) DEFAULT NULL, `gast_w6_raus` int(10) DEFAULT NULL, `gast_w6_rein` int(10) DEFAULT NULL,
	`gast_w6_minute` tinyint(2) DEFAULT NULL, `bericht` text DEFAULT NULL, `zuschauer` int(6) DEFAULT NULL, `berechnet` enum('1','0') DEFAULT NULL DEFAULT '0', `soldout` enum('1','0') DEFAULT NULL DEFAULT '0', `home_setup` varchar(16) DEFAULT NULL,
	`home_w1_condition` varchar(16) DEFAULT NULL, `home_w2_condition` varchar(16) DEFAULT NULL, `home_w3_condition` varchar(16) DEFAULT NULL, `home_w4_condition` varchar(16) DEFAULT NULL, `home_w5_condition` varchar(16) DEFAULT NULL,
	`home_w6_condition` varchar(16) DEFAULT NULL, `gast_setup` varchar(16) DEFAULT NULL, `gast_w1_condition` varchar(16) DEFAULT NULL, `gast_w2_condition` varchar(16) DEFAULT NULL, `gast_w3_condition` varchar(16) DEFAULT NULL, `gast_w4_condition` varchar(16) DEFAULT NULL,
	`gast_w5_condition` varchar(16) DEFAULT NULL, `gast_w6_condition` varchar(16) DEFAULT NULL, `home_longpasses` enum('1','0') DEFAULT NULL DEFAULT '0', `home_counterattacks` enum('1','0') DEFAULT NULL DEFAULT '0', `gast_longpasses` enum('1','0') DEFAULT NULL DEFAULT '0',
	`gast_counterattacks` enum('1','0') DEFAULT NULL DEFAULT '0', `home_morale` tinyint(3) DEFAULT NULL DEFAULT 0, `gast_morale` tinyint(3) DEFAULT NULL DEFAULT 0, `home_user_id` int(10) DEFAULT NULL, `gast_user_id` int(10) DEFAULT NULL,
	`home_freekickplayer` int(10) DEFAULT NULL, `home_w1_position` varchar(4) DEFAULT NULL, `home_w2_position` varchar(4) DEFAULT NULL, `home_w3_position` varchar(4) DEFAULT NULL, `home_w4_position` varchar(4) DEFAULT NULL, `home_w5_position` varchar(4) DEFAULT NULL,
	`home_w6_position` varchar(4) DEFAULT NULL, `gast_freekickplayer` int(10) DEFAULT NULL, `gast_w1_position` varchar(4) DEFAULT NULL, `gast_w2_position` varchar(4) DEFAULT NULL, `gast_w3_position` varchar(4) DEFAULT NULL, `gast_w4_position` varchar(4) DEFAULT NULL,
	`gast_w5_position` varchar(4) DEFAULT NULL, `gast_w6_position` varchar(4) DEFAULT NULL, `blocked` enum('1','0') DEFAULT NULL DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_spiel_saison_id_fk` (`saison_id`), KEY `_spiel_home_id_fk` (`home_verein`), KEY `_spiel_gast_id_fk` (`gast_verein`), KEY `_match_home_user_id_fk` (`home_user_id`), KEY `_match_guest_user_id_fk` (`gast_user_id`),
			CONSTRAINT `_match_guest_user_id_fk` FOREIGN KEY (`gast_user_id`) REFERENCES `_user` (`id`) ON DELETE SET NULL,
			CONSTRAINT `_match_home_user_id_fk` FOREIGN KEY (`home_user_id`) REFERENCES `_user` (`id`) ON DELETE SET NULL,
			CONSTRAINT `_spiel_gast_id_fk` FOREIGN KEY (`gast_verein`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_spiel_home_id_fk` FOREIGN KEY (`home_verein`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_spiel_saison_id_fk` FOREIGN KEY (`saison_id`) REFERENCES `_saison` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `spiel` LIKE `_spiel`;
				CREATE TABLE IF NOT EXISTS `ws3_spiel` LIKE `_spiel`;
CREATE TABLE IF NOT EXISTS `_spieler` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `vorname` varchar(30) DEFAULT NULL, `nachname` varchar(30) DEFAULT NULL, `kunstname` varchar(30) DEFAULT NULL, `geburtstag` date DEFAULT NULL, `verein_id` int(10) DEFAULT NULL,
	`position` enum('Torwart','Abwehr','Mittelfeld','Sturm') DEFAULT 'Mittelfeld', `position_main` enum('T','LV','IV','RV','LM','DM','ZM','OM','RM','LS','MS','RS') DEFAULT NULL, `position_second` enum('T','LV','IV','RV','LM','DM','ZM','OM','RM','LS','MS','RS') DEFAULT NULL,
	`nation` varchar(30) DEFAULT NULL, `picture` varchar(128) DEFAULT NULL, `verletzt` tinyint(3) DEFAULT 0, `gesperrt` tinyint(3) DEFAULT 0, `gesperrt_cups` tinyint(3) DEFAULT 0, `gesperrt_nationalteam` tinyint(3) DEFAULT 0, `transfermarkt` enum('1','0') DEFAULT '0',
	`transfer_start` int(11) DEFAULT 0, `transfer_ende` int(11) DEFAULT 0, `transfer_mindestgebot` int(11) DEFAULT 0, `w_staerke` tinyint(3) DEFAULT NULL, `w_technik` tinyint(3) DEFAULT NULL, `w_kondition` tinyint(3) DEFAULT NULL, `w_frische` tinyint(3) DEFAULT NULL,
	`w_zufriedenheit` tinyint(3) DEFAULT NULL, `einzeltraining` enum('1','0') DEFAULT '0', `note_last` double(4,2) DEFAULT 0.00, `note_schnitt` double(4,2) DEFAULT 0.00, `vertrag_gehalt` int(10) DEFAULT NULL, `vertrag_spiele` smallint(5) DEFAULT NULL,
	`vertrag_torpraemie` int(10) DEFAULT NULL, `marktwert` int(10) DEFAULT 0, `st_tore` int(6) DEFAULT 0, `st_assists` int(6) DEFAULT 0, `st_spiele` smallint(5) DEFAULT 0, `st_karten_gelb` smallint(5) DEFAULT 0, `st_karten_gelb_rot` smallint(5) DEFAULT 0,
	`st_karten_rot` smallint(5) DEFAULT 0, `sa_tore` int(6) DEFAULT 0, `sa_assists` int(6) DEFAULT 0, `sa_spiele` smallint(5) DEFAULT 0, `sa_karten_gelb` smallint(5) DEFAULT 0, `sa_karten_gelb_rot` smallint(5) DEFAULT 0, `sa_karten_rot` smallint(5) DEFAULT 0,
	`history` text DEFAULT NULL, `unsellable` enum('1','0') DEFAULT '0', `lending_fee` int(6) DEFAULT 0, `lending_matches` tinyint(4) DEFAULT 0, `lending_owner_id` int(10) DEFAULT NULL, `age` tinyint(3) DEFAULT NULL, `status` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_spieler_verein_id_fk` (`verein_id`),
			CONSTRAINT `_spieler_verein_id_fk` FOREIGN KEY (`verein_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `spieler` LIKE `_spieler`;
				CREATE TABLE IF NOT EXISTS `ws3_spieler` LIKE `_spieler`;
CREATE TABLE IF NOT EXISTS `_playerscout` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(32) DEFAULT NULL, `expertise` tinyint(3) DEFAULT NULL, `fee` int(10) DEFAULT NULL, `speciality` enum('Torwart','Abwehr','Mittelfeld','Sturm') DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `playerscout` LIKE `_playerscout`;
				CREATE TABLE IF NOT EXISTS `ws3_playerscout` LIKE `_playerscout`;
CREATE TABLE IF NOT EXISTS `_spiel_berechnung` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `spiel_id` int(10) DEFAULT NULL, `spieler_id` int(10) DEFAULT NULL, `team_id` int(10) DEFAULT NULL, `position` varchar(20) DEFAULT NULL, `note` double(4,2) DEFAULT NULL, `minuten_gespielt` tinyint(2) DEFAULT NULL DEFAULT 0,
	`karte_gelb` tinyint(1) DEFAULT NULL DEFAULT 0, `karte_rot` tinyint(1) DEFAULT NULL DEFAULT 0, `verletzt` tinyint(2) DEFAULT NULL DEFAULT 0, `gesperrt` tinyint(2) DEFAULT NULL DEFAULT 0, `tore` tinyint(2) DEFAULT NULL DEFAULT 0,
	`feld` enum('1','Ersatzbank','Ausgewechselt') DEFAULT NULL DEFAULT '1', `position_main` varchar(5) DEFAULT NULL, `age` tinyint(2) DEFAULT NULL, `w_staerke` tinyint(3) DEFAULT NULL, `w_technik` tinyint(3) DEFAULT NULL, `w_kondition` tinyint(3) DEFAULT NULL,
	`w_frische` tinyint(3) DEFAULT NULL, `w_zufriedenheit` tinyint(3) DEFAULT NULL, `ballcontacts` tinyint(3) DEFAULT NULL, `wontackles` tinyint(3) DEFAULT NULL, `shoots` tinyint(3) DEFAULT NULL, `passes_successed` tinyint(3) DEFAULT NULL,
	`passes_failed` tinyint(3) DEFAULT NULL, `assists` tinyint(3) DEFAULT NULL, `name` varchar(128) DEFAULT NULL, `losttackles` tinyint(3) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `_berechnung_spiel_id_fk` (`spiel_id`), KEY `_berechnung_spieler_id_fk` (`spieler_id`),
			CONSTRAINT `_berechnung_spiel_id_fk` FOREIGN KEY (`spiel_id`) REFERENCES `_spiel` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_berechnung_spieler_id_fk` FOREIGN KEY (`spieler_id`) REFERENCES `_spieler` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `spiel_berechnung` LIKE `_spiel_berechnung`;
				CREATE TABLE IF NOT EXISTS `ws3_spiel_berechnung` LIKE `_spiel_berechnung`;
CREATE TABLE IF NOT EXISTS `_spiel_text` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `aktion` enum('Tor','Auswechslung','Auswechslung wegen Kopfveletzung','Zweikampf_gewonnen','Zweikampf_verloren','Pass_daneben','Torschuss_daneben','Torschuss_auf_Tor','Karte_gelb','Karte_rot','Karte_gelb_rot','Verletzung',
	'Elfmeter_erfolg','Elfmeter_verschossen', 'Taktikaenderung','Ecke','Freistoss_daneben','Freistoss_treffer','Tor_mit_vorlage','Eigentor') DEFAULT NULL, `nachricht` varchar(250) DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `spiel_text` LIKE `_spiel_text`;
				CREATE TABLE IF NOT EXISTS `ws3_spiel_text` LIKE `_spiel_text`;
CREATE TABLE IF NOT EXISTS `_sponsor` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(30) DEFAULT NULL, `bild` varchar(100) DEFAULT NULL, `liga_id` smallint(5) DEFAULT NULL, `b_spiel` int(10) DEFAULT NULL, `b_heimzuschlag` int(10) DEFAULT NULL, `b_sieg` int(10) DEFAULT NULL,
	`b_meisterschaft` int(10) DEFAULT NULL, `max_teams` smallint(5) DEFAULT NULL, `min_platz` tinyint(3) DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `sponsor` LIKE `_sponsor`;
				CREATE TABLE IF NOT EXISTS `ws3_sponsor` LIKE `_sponsor`;
CREATE TABLE IF NOT EXISTS `_stadion` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(30) DEFAULT NULL, `stadt` varchar(30) DEFAULT NULL, `land` varchar(20) DEFAULT NULL, `p_steh` int(6) DEFAULT NULL, `p_sitz` int(6) DEFAULT NULL, `p_haupt_steh` int(6) DEFAULT NULL, `p_haupt_sitz` int(6) DEFAULT NULL,
	`p_vip` int(6) DEFAULT NULL, `level_pitch` tinyint(2) DEFAULT 3, `level_videowall` tinyint(2) DEFAULT 1, `level_seatsquality` tinyint(2) DEFAULT 5, `level_vipquality` tinyint(2) DEFAULT 5, `maintenance_pitch` tinyint(2) DEFAULT 1,
	`maintenance_videowall` tinyint(2) DEFAULT 1, `maintenance_seatsquality` tinyint(2) DEFAULT 1, `maintenance_vipquality` tinyint(2) DEFAULT 1, `picture` varchar(128) DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `stadion` LIKE `_stadion`;
				CREATE TABLE IF NOT EXISTS `ws3_stadion` LIKE `_stadion`;
CREATE TABLE IF NOT EXISTS `_stadiumbuilding` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(255) DEFAULT NULL, `description` varchar(255) DEFAULT NULL, `picture` varchar(255) DEFAULT NULL, `required_building_id` int(10) DEFAULT NULL, `costs` int(10) DEFAULT NULL, `premiumfee` int(10) DEFAULT 0,
	`construction_time_days` tinyint(3) DEFAULT 0, `effect_training` tinyint(3) DEFAULT 0, `effect_youthscouting` tinyint(3) DEFAULT 0, `effect_tickets` tinyint(3) DEFAULT 0, `effect_fanpopularity` tinyint(3) DEFAULT 0, `effect_injury` tinyint(3) DEFAULT 0,
	`effect_income` int(10) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `required_building_id` (`required_building_id`),
			CONSTRAINT `_stadiumbuilding_ibfk_1` FOREIGN KEY (`required_building_id`) REFERENCES `_stadiumbuilding` (`id`) ON DELETE SET NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `stadiumbuilding` LIKE `_stadiumbuilding`;
				CREATE TABLE IF NOT EXISTS `ws3_stadiumbuilding` LIKE `_stadiumbuilding`;
CREATE TABLE IF NOT EXISTS `_stadium_builder` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(32) DEFAULT NULL, `picture` varchar(128) DEFAULT NULL, `fixedcosts` int(10) DEFAULT 0, `cost_per_seat` int(10) DEFAULT 0, `construction_time_days` tinyint(3) DEFAULT 0,
	`construction_time_days_min` tinyint(3) DEFAULT 0, `min_stadium_size` int(10) DEFAULT 0, `max_stadium_size` int(10) DEFAULT 0, `reliability` tinyint(3) DEFAULT 100, `premiumfee` int(10) DEFAULT 0,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `stadium_builder` LIKE `_stadium_builder`;
				CREATE TABLE IF NOT EXISTS `ws3_stadium_builder` LIKE `_stadium_builder`;
CREATE TABLE IF NOT EXISTS `_stadium_construction` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `team_id` int(10) DEFAULT NULL, `builder_id` int(10) DEFAULT NULL, `started` int(11) DEFAULT NULL, `deadline` int(11) DEFAULT NULL, `p_steh` int(6) DEFAULT 0, `p_sitz` int(6) DEFAULT 0, `p_haupt_steh` int(6) DEFAULT 0,
	`p_haupt_sitz` int(6) DEFAULT 0, `p_vip` int(6) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `_construction_team_id_fk` (`team_id`), KEY `_construction_builder_id_fk` (`builder_id`),
			CONSTRAINT `_construction_builder_id_fk` FOREIGN KEY (`builder_id`) REFERENCES `_stadium_builder` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_construction_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_stadium_construction_ibfk_1` FOREIGN KEY (`builder_id`) REFERENCES `_stadium_builder` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `stadium_construction` LIKE `_stadium_construction`;
				CREATE TABLE IF NOT EXISTS `ws3_stadium_construction` LIKE `_stadium_construction`;
CREATE TABLE IF NOT EXISTS `_tabelle_markierung` (
	`id` smallint(5) NOT NULL AUTO_INCREMENT, `liga_id` smallint(5) DEFAULT NULL, `bezeichnung` varchar(50) DEFAULT NULL, `farbe` varchar(10) DEFAULT NULL, `platz_von` smallint(5) DEFAULT NULL, `platz_bis` smallint(5) DEFAULT NULL, `target_league_id` int(10) DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `tabelle_markierung` LIKE `_tabelle_markierung`;
				CREATE TABLE IF NOT EXISTS `ws3_tabelle_markierung` LIKE `_tabelle_markierung`;
CREATE TABLE IF NOT EXISTS `_teamoftheday` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `season_id` int(10) DEFAULT NULL, `matchday` tinyint(3) DEFAULT NULL, `statistic_id` int(10) DEFAULT NULL, `player_id` int(10) DEFAULT NULL, `position_main` varchar(20) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `_teamofday_season_id_fk` (`season_id`), KEY `_teamofday_player_id_fk` (`player_id`),
			CONSTRAINT `_teamofday_player_id_fk` FOREIGN KEY (`player_id`) REFERENCES `_spieler` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_teamofday_season_id_fk` FOREIGN KEY (`season_id`) REFERENCES `_saison` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `teamoftheday` LIKE `_teamoftheday`;
				CREATE TABLE IF NOT EXISTS `ws3_teamoftheday` LIKE `_teamoftheday`;
CREATE TABLE IF NOT EXISTS `_team_league_statistics` (
	`team_id` int(10) NOT NULL, `season_id` int(10) NOT NULL, `total_points` int(6) DEFAULT 0, `total_goals` int(6) DEFAULT 0, `total_goalsreceived` int(6) DEFAULT 0, `total_goalsdiff` int(6) DEFAULT 0, `total_wins` int(6) DEFAULT 0, `total_draws` int(6) DEFAULT 0,
	`total_losses` int(6) DEFAULT 0, `home_points` int(6) DEFAULT 0, `home_goals` int(6) DEFAULT 0, `home_goalsreceived` int(6) DEFAULT 0, `home_goalsdiff` int(6) DEFAULT 0, `home_wins` int(6) DEFAULT 0, `home_draws` int(6) DEFAULT 0, `home_losses` int(6) DEFAULT 0,
	`guest_points` int(6) DEFAULT 0, `guest_goals` int(6) DEFAULT 0, `guest_goalsreceived` int(6) DEFAULT 0, `guest_goalsdiff` int(6) DEFAULT 0, `guest_wins` int(6) DEFAULT 0, `guest_draws` int(6) DEFAULT 0, `guest_losses` int(6) DEFAULT 0,
		PRIMARY KEY (`team_id`,`season_id`), KEY `_statistics_season_id_fk` (`season_id`),
			CONSTRAINT `_statistics_season_id_fk` FOREIGN KEY (`season_id`) REFERENCES `_saison` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_statistics_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `team_league_statistics` LIKE `_team_league_statistics`;
				CREATE TABLE IF NOT EXISTS `ws3_team_league_statistics` LIKE `_team_league_statistics`;
CREATE TABLE IF NOT EXISTS `_trainer` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(64) DEFAULT NULL, `salary` int(10) DEFAULT NULL, `p_strong` tinyint(3) DEFAULT 0, `p_technique` tinyint(3) DEFAULT 0, `p_stamina` tinyint(3) DEFAULT 0, `p_fresh` tinyint(3) DEFAULT 0,
	`p_satisfaction` tinyint(3) DEFAULT 0, `premiumfee` int(10) DEFAULT 0,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `trainer` LIKE `_trainer`;
				CREATE TABLE IF NOT EXISTS `ws3_trainer` LIKE `_trainer`;
CREATE TABLE IF NOT EXISTS `_training` (
	`id` smallint(5) NOT NULL AUTO_INCREMENT, `name` varchar(30) DEFAULT NULL, `w_staerke` tinyint(3) DEFAULT NULL, `w_technik` tinyint(3) DEFAULT NULL, `w_kondition` tinyint(3) DEFAULT NULL, `w_frische` tinyint(3) DEFAULT NULL, `w_zufriedenheit` tinyint(3) DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `training` LIKE `_training`;
				CREATE TABLE IF NOT EXISTS `ws3_training` LIKE `_training`;
CREATE TABLE IF NOT EXISTS `_trainingslager` (
  	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(100) DEFAULT NULL, `land` varchar(30) DEFAULT NULL, `bild` varchar(100) DEFAULT NULL, `preis_spieler_tag` int(10) DEFAULT NULL, `p_staerke` tinyint(3) DEFAULT NULL, `p_technik` tinyint(3) DEFAULT NULL,
	`p_kondition` tinyint(3) DEFAULT NULL, `p_frische` tinyint(3) DEFAULT NULL, `p_zufriedenheit` tinyint(3) DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `trainingslager` LIKE `_trainingslager`;
				CREATE TABLE IF NOT EXISTS `ws3_trainingslager` LIKE `_trainingslager`;
CREATE TABLE IF NOT EXISTS `_trainingslager_belegung` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `verein_id` int(10) DEFAULT NULL, `lager_id` int(10) DEFAULT NULL, `datum_start` int(10) DEFAULT NULL, `datum_ende` int(10) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `_trainingslager_belegung_fk` (`lager_id`), KEY `_trainingslager_verein_fk` (`verein_id`),
			CONSTRAINT `_trainingslager_belegung_fk` FOREIGN KEY (`lager_id`) REFERENCES `_trainingslager` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_trainingslager_verein_fk` FOREIGN KEY (`verein_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `trainingslager_belegung` LIKE `_trainingslager_belegung`;
				CREATE TABLE IF NOT EXISTS `ws3_trainingslager_belegung` LIKE `_trainingslager_belegung`;
CREATE TABLE IF NOT EXISTS `_training_unit` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `team_id` int(10) DEFAULT NULL, `trainer_id` int(10) DEFAULT NULL, `focus` enum('TE','STA','MOT','FR') DEFAULT 'TE', `intensity` tinyint(3) DEFAULT 50, `date_executed` int(10) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `_training_verein_id_fk` (`team_id`),
			CONSTRAINT `_training_verein_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `training_unit` LIKE `_training_unit`;
				CREATE TABLE IF NOT EXISTS `ws3_training_unit` LIKE `_training_unit`;
CREATE TABLE IF NOT EXISTS `_transfer` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `spieler_id` int(10) DEFAULT NULL, `seller_user_id` int(10) DEFAULT NULL, `seller_club_id` int(10) DEFAULT NULL, `buyer_user_id` int(10) DEFAULT NULL, `buyer_club_id` int(10) DEFAULT NULL, `datum` int(11) DEFAULT NULL,
	`bid_id` int(11) DEFAULT 0, `directtransfer_amount` int(10) DEFAULT NULL, `directtransfer_player1` int(10) DEFAULT 0, `directtransfer_player2` int(10) DEFAULT 0, `directtransfer_player3` int(10) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `_transfer_spieler_id_fk` (`spieler_id`), KEY `_transfer_selleruser_fk` (`seller_user_id`), KEY `_transfer_sellerclub_fk` (`seller_club_id`), KEY `_transfer_buyeruser_fk` (`buyer_user_id`), KEY `_transfer_buyerclub_fk` (`buyer_club_id`),
			CONSTRAINT `_transfer_buyerclub_fk` FOREIGN KEY (`buyer_club_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_transfer_buyeruser_fk` FOREIGN KEY (`buyer_user_id`) REFERENCES `_user` (`id`) ON DELETE SET NULL,
			CONSTRAINT `_transfer_sellerclub_fk` FOREIGN KEY (`seller_club_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_transfer_selleruser_fk` FOREIGN KEY (`seller_user_id`) REFERENCES `_user` (`id`) ON DELETE SET NULL,
			CONSTRAINT `_transfer_spieler_id_fk` FOREIGN KEY (`spieler_id`) REFERENCES `_spieler` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `transfer` LIKE `_transfer`;
				CREATE TABLE IF NOT EXISTS `ws3_transfer` LIKE `_transfer`;
CREATE TABLE IF NOT EXISTS `_transfer_angebot` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `spieler_id` int(10) DEFAULT NULL, `verein_id` int(10) DEFAULT NULL, `user_id` int(10) DEFAULT NULL, `datum` int(11) DEFAULT NULL, `abloese` int(11) DEFAULT NULL, `handgeld` int(11) DEFAULT 0,
	`vertrag_spiele` smallint(5) DEFAULT NULL, `vertrag_gehalt` int(7) DEFAULT NULL, `vertrag_torpraemie` smallint(5) DEFAULT 0, `ishighest` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_transfer_angebot_user_id_fk` (`user_id`),
			CONSTRAINT `_transfer_angebot_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `transfer_angebot` LIKE `_transfer_angebot`;
				CREATE TABLE IF NOT EXISTS `ws3_transfer_angebot` LIKE `_transfer_angebot`;
CREATE TABLE IF NOT EXISTS `_transfer_offer` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `player_id` int(10) DEFAULT NULL, `sender_user_id` int(10) DEFAULT NULL, `sender_club_id` int(10) DEFAULT NULL, `receiver_club_id` int(10) DEFAULT NULL, `submitted_date` int(11) DEFAULT NULL, `offer_amount` int(10) DEFAULT NULL,
	`offer_message` varchar(255) DEFAULT NULL, `offer_player1` int(10) DEFAULT 0, `offer_player2` int(10) DEFAULT 0, `offer_player3` int(10) DEFAULT 0, `rejected_date` int(11) DEFAULT 0, `rejected_message` varchar(255) DEFAULT NULL,
	`rejected_allow_alternative` enum('1','0') DEFAULT '0', `admin_approval_pending` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_toffer_spieler_id_fk` (`player_id`), KEY `_toffer_selleruser_fk` (`sender_user_id`), KEY `_toffer_sellerclub_fk` (`sender_club_id`), KEY `_toffer_buyerclub_fk` (`receiver_club_id`),
			CONSTRAINT `_toffer_buyerclub_fk` FOREIGN KEY (`receiver_club_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_toffer_sellerclub_fk` FOREIGN KEY (`sender_club_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_toffer_selleruser_fk` FOREIGN KEY (`sender_user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_toffer_spieler_id_fk` FOREIGN KEY (`player_id`) REFERENCES `_spieler` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `transfer_offer` LIKE `_transfer_offer`;
				CREATE TABLE IF NOT EXISTS `ws3_transfer_offer` LIKE `_transfer_offer`;
CREATE TABLE IF NOT EXISTS `_user` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `nick` varchar(50) DEFAULT NULL, `passwort` varchar(64) DEFAULT NULL, `passwort_neu` varchar(64) DEFAULT NULL, `passwort_neu_angefordert` int(11) DEFAULT 0, `passwort_salt` varchar(5) DEFAULT NULL,
	`tokenid` varchar(255) DEFAULT NULL, `lang` varchar(2) DEFAULT 'de', `email` varchar(150) DEFAULT NULL, `datum_anmeldung` int(11) DEFAULT 0, `schluessel` varchar(10) DEFAULT NULL, `wunschverein` varchar(250) DEFAULT NULL, `name` varchar(80) DEFAULT NULL,
	`wohnort` varchar(50) DEFAULT NULL, `land` varchar(40) DEFAULT NULL, `geburtstag` date DEFAULT NULL, `beruf` varchar(50) DEFAULT NULL, `interessen` varchar(250) DEFAULT NULL, `lieblingsverein` varchar(100) DEFAULT NULL, `homepage` varchar(250) DEFAULT NULL,
	`icq` varchar(20) DEFAULT NULL, `aim` varchar(30) DEFAULT NULL, `yim` varchar(30) DEFAULT NULL, `msn` varchar(30) DEFAULT NULL, `lastonline` int(11) DEFAULT 0, `lastaction` varchar(150) DEFAULT NULL, `highscore` int(10) DEFAULT 0, `fanbeliebtheit` tinyint(3) DEFAULT 50,
	`c_showemail` enum('1','0') DEFAULT '0', `email_transfers` enum('1','0') DEFAULT '0', `email_pn` enum('1','0') DEFAULT '0', `history` text DEFAULT NULL, `ip` varchar(25) DEFAULT NULL, `ip_time` int(11) DEFAULT 0, `c_hideinonlinelist` enum('1','0') DEFAULT '0',
	`premium_balance` int(6) DEFAULT 0, `picture` varchar(255) DEFAULT NULL, `status` enum('1','2','0') DEFAULT '0',
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `user` LIKE `_user`;
				CREATE TABLE IF NOT EXISTS `ws3_user` LIKE `_user`;
CREATE TABLE IF NOT EXISTS `_userabsence` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `user_id` int(10) DEFAULT NULL, `deputy_id` int(10) DEFAULT NULL, `from_date` int(11) DEFAULT NULL, `to_date` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `user_id` (`user_id`), KEY `deputy_id` (`deputy_id`),
			CONSTRAINT `_userabsence_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_userabsence_ibfk_2` FOREIGN KEY (`deputy_id`) REFERENCES `_user` (`id`) ON DELETE SET NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `userabsence` LIKE `_userabsence`;
				CREATE TABLE IF NOT EXISTS `ws3_userabsence` LIKE `_userabsence`;
CREATE TABLE IF NOT EXISTS `_useractionlog` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `user_id` int(10) DEFAULT NULL, `action_id` varchar(255) DEFAULT NULL, `created_date` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `user_id` (`user_id`),
			CONSTRAINT `_useractionlog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `useractionlog` LIKE `_useractionlog`;
				CREATE TABLE IF NOT EXISTS `ws3_useractionlog` LIKE `_useractionlog`;
CREATE TABLE IF NOT EXISTS `_user_inactivity` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `user_id` int(10) DEFAULT NULL, `login` tinyint(3) DEFAULT 0, `login_last` int(11) DEFAULT NULL, `login_check` int(11) DEFAULT NULL, `aufstellung` tinyint(3) DEFAULT 0, `transfer` tinyint(3) DEFAULT 0,
	`transfer_check` int(11) DEFAULT NULL, `vertragsauslauf` tinyint(3) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `_user_inactivity_user_id_fk` (`user_id`),
  			CONSTRAINT `_user_inactivity_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `user_inactivity` LIKE `_user_inactivity`;
				CREATE TABLE IF NOT EXISTS `ws3_user_inactivity` LIKE `_user_inactivity`;
CREATE TABLE IF NOT EXISTS `_verein` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `spieltyp` ENUM('Profiverein','Amateurverein') DEFAULT NULL DEFAULT 'Profiverein', `name` varchar(50) DEFAULT NULL, `kurz` varchar(10) DEFAULT NULL, `bild` varchar(100) DEFAULT NULL, `liga_id` smallint(5) DEFAULT NULL,
	`amateur_liga_id` SMALLINT(5) DEFAULT NULL, `jugend_liga_id` SMALLINT(5) DEFAULT NULL, `user_id` int(10) DEFAULT NULL, `stadion_id` int(10) DEFAULT NULL, `sponsor_id` int(10) DEFAULT NULL, `training_id` int(5) DEFAULT NULL, `platz` tinyint(2) DEFAULT NULL,
	`sponsor_spiele` smallint(5) DEFAULT 0, `finanz_budget` int(11) DEFAULT NULL, `preis_stehen` smallint(4) DEFAULT NULL, `preis_sitz` smallint(4) DEFAULT NULL, `preis_haupt_stehen` smallint(4) DEFAULT NULL, `preis_haupt_sitze` smallint(4) DEFAULT NULL,
	`preis_vip` smallint(4) DEFAULT NULL, `last_steh` int(6) DEFAULT 0, `last_sitz` int(6) DEFAULT 0, `last_haupt_steh` int(6) DEFAULT 0, `last_haupt_sitz` int(6) DEFAULT 0, `last_vip` int(6) DEFAULT 0, `meisterschaften` smallint(4) DEFAULT 0,
	`st_tore` int(6) DEFAULT 0, `st_gegentore` int(6) DEFAULT 0, `st_spiele` smallint(5) DEFAULT 0, `st_siege` smallint(5) DEFAULT 0, `st_niederlagen` smallint(5) DEFAULT 0, `st_unentschieden` smallint(5) DEFAULT 0, `st_punkte` int(6) DEFAULT 0, `sa_tore` int(6) DEFAULT 0,
	`sa_gegentore` int(6) DEFAULT 0, `sa_spiele` smallint(5) DEFAULT 0, `sa_siege` smallint(5) DEFAULT 0, `sa_niederlagen` smallint(5) DEFAULT 0, `sa_unentschieden` smallint(5) DEFAULT 0, `sa_punkte` int(6) DEFAULT 0, `am_tore` int(6) DEFAULT 0,
	`am_gegentore` int(6) DEFAULT 0, `am_spiele` smallint(5) DEFAULT 0, `am_siege` smallint(5) DEFAULT 0, `am_niederlagen` smallint(5) DEFAULT 0, `am_unentschieden` smallint(5) DEFAULT 0, `am_punkte` int(6) DEFAULT 0, `ju_tore` int(6) DEFAULT 0,
	`ju_gegentore` int(6) DEFAULT 0, `ju_spiele` smallint(5) DEFAULT 0, `ju_siege` smallint(5) DEFAULT 0, `ju_niederlagen` smallint(5) DEFAULT 0, `ju_unentschieden` smallint(5) DEFAULT 0, `ju_punkte` int(6) DEFAULT 0, `min_target_rank` smallint(3) DEFAULT 0,
	`history` text DEFAULT NULL, `scouting_last_execution` int(11) DEFAULT 0, `nationalteam` enum('1','0') DEFAULT '0', `captain_id` int(10) DEFAULT NULL, `strength` tinyint(3) DEFAULT 0, `user_id_actual` int(10) DEFAULT NULL, `interimmanager` enum('1','0') DEFAULT '0',
	`status` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_verein_user_id_fk` (`user_id`), KEY `_verein_stadion_id_fk` (`stadion_id`), KEY `_verein_sponsor_id_fk` (`sponsor_id`), KEY `_verein_liga_id_fk` (`liga_id`), KEY `_verein_original_user_id_fk` (`user_id_actual`),
			CONSTRAINT `_verein_liga_id_fk` FOREIGN KEY (`liga_id`) REFERENCES `_liga` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_verein_original_user_id_fk` FOREIGN KEY (`user_id_actual`) REFERENCES `_user` (`id`) ON DELETE SET NULL,
			CONSTRAINT `_verein_sponsor_id_fk` FOREIGN KEY (`sponsor_id`) REFERENCES `_sponsor` (`id`) ON DELETE SET NULL,
			CONSTRAINT `_verein_stadion_id_fk` FOREIGN KEY (`stadion_id`) REFERENCES `_stadion` (`id`) ON DELETE SET NULL,
			CONSTRAINT `_verein_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `_user` (`id`) ON DELETE SET NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `verein` LIKE `_verein`;
				CREATE TABLE IF NOT EXISTS `ws3_verein` LIKE `_verein`;
CREATE TABLE IF NOT EXISTS `_youthmatch` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `matchdate` int(11) DEFAULT NULL, `home_team_id` int(10) DEFAULT NULL, `home_noformation` enum('1','0') DEFAULT '0', `home_s1_out` int(10) DEFAULT NULL, `home_s1_in` int(10) DEFAULT NULL, `home_s1_minute` tinyint(3) DEFAULT NULL,
	`home_s1_condition` varchar(16) DEFAULT NULL, `home_s1_position` varchar(4) DEFAULT NULL, `home_s2_out` int(10) DEFAULT NULL, `home_s2_in` int(10) DEFAULT NULL, `home_s2_minute` tinyint(3) DEFAULT NULL, `home_s2_condition` varchar(16) DEFAULT NULL,
	`home_s2_position` varchar(4) DEFAULT NULL, `home_s3_out` int(10) DEFAULT NULL, `home_s3_in` int(10) DEFAULT NULL, `home_s3_minute` tinyint(3) DEFAULT NULL, `home_s3_condition` varchar(16) DEFAULT NULL, `home_s3_position` varchar(4) DEFAULT NULL,
	`home_s4_out` int(10) DEFAULT NULL, `home_s4_in` int(10) DEFAULT NULL, `home_s4_minute` tinyint(3) DEFAULT NULL, `home_s4_condition` varchar(16) DEFAULT NULL, `home_s4_position` varchar(4) DEFAULT NULL, `home_s5_out` int(10) DEFAULT NULL,
	`home_s5_in` int(10) DEFAULT NULL, `home_s5_minute` tinyint(3) DEFAULT NULL, `home_s5_condition` varchar(16) DEFAULT NULL, `home_s5_position` varchar(4) DEFAULT NULL, `home_s6_out` int(10) DEFAULT NULL, `home_s6_in` int(10) DEFAULT NULL,
	`home_s6_minute` tinyint(3) DEFAULT NULL, `home_s6_condition` varchar(16) DEFAULT NULL, `home_s6_position` varchar(4) DEFAULT NULL, `guest_team_id` int(10) DEFAULT NULL, `guest_noformation` enum('1','0') DEFAULT '0', `guest_s1_out` int(10) DEFAULT NULL,
	`guest_s1_in` int(10) DEFAULT NULL, `guest_s1_minute` tinyint(3) DEFAULT NULL, `guest_s1_condition` varchar(16) DEFAULT NULL, `guest_s1_position` varchar(4) DEFAULT NULL, `guest_s2_out` int(10) DEFAULT NULL, `guest_s2_in` int(10) DEFAULT NULL,
	`guest_s2_minute` tinyint(3) DEFAULT NULL, `guest_s2_condition` varchar(16) DEFAULT NULL, `guest_s2_position` varchar(4) DEFAULT NULL, `guest_s3_out` int(10) DEFAULT NULL, `guest_s3_in` int(10) DEFAULT NULL, `guest_s3_minute` tinyint(3) DEFAULT NULL,
	`guest_s3_condition` varchar(16) DEFAULT NULL, `guest_s3_position` varchar(4) DEFAULT NULL, `guest_s4_out` int(10) DEFAULT NULL, `guest_s4_in` int(10) DEFAULT NULL, `guest_s4_minute` tinyint(3) DEFAULT NULL, `guest_s4_condition` varchar(16) DEFAULT NULL,
	`guest_s4_position` varchar(4) DEFAULT NULL, `guest_s5_out` int(10) DEFAULT NULL, `guest_s5_in` int(10) DEFAULT NULL, `guest_s5_minute` tinyint(3) DEFAULT NULL, `guest_s5_condition` varchar(16) DEFAULT NULL, `guest_s5_position` varchar(4) DEFAULT NULL,
	`guest_s6_out` int(10) DEFAULT NULL, `guest_s6_in` int(10) DEFAULT NULL, `guest_s6_minute` tinyint(3) DEFAULT NULL, `guest_s6_condition` varchar(16) DEFAULT NULL, `guest_s6_position` varchar(4) DEFAULT NULL, `home_goals` tinyint(2) DEFAULT NULL,
	`guest_goals` tinyint(2) DEFAULT NULL, `simulated` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_youthmatch_home_id_fk` (`home_team_id`), KEY `_youthmatch_guest_id_fk` (`guest_team_id`),
			CONSTRAINT `_youthmatch_guest_id_fk` FOREIGN KEY (`guest_team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_youthmatch_home_id_fk` FOREIGN KEY (`home_team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `youthmatch` LIKE `_youthmatch`;
				CREATE TABLE IF NOT EXISTS `ws3_youthmatch` LIKE `_youthmatch`;
CREATE TABLE IF NOT EXISTS `_youthmatch_player` (
	`match_id` int(10) NOT NULL, `team_id` int(10) DEFAULT NULL, `player_id` int(10) NOT NULL, `playernumber` tinyint(2) DEFAULT NULL, `position` varchar(24) DEFAULT NULL, `position_main` varchar(8) DEFAULT NULL, `grade` double(4,2) DEFAULT 3.00,
	`minutes_played` tinyint(2) DEFAULT 0, `card_yellow` tinyint(1) DEFAULT 0, `card_red` tinyint(1) DEFAULT 0, `goals` tinyint(2) DEFAULT 0, `state` enum('1','Ersatzbank','Ausgewechselt') DEFAULT '1', `strength` tinyint(3) DEFAULT NULL, `ballcontacts` tinyint(3) DEFAULT 0,
	`wontackles` tinyint(3) DEFAULT 0, `shoots` tinyint(3) DEFAULT 0, `passes_successed` tinyint(3) DEFAULT 0, `passes_failed` tinyint(3) DEFAULT 0, `assists` tinyint(3) DEFAULT 0, `name` varchar(128) DEFAULT NULL,
		PRIMARY KEY (`match_id`,`player_id`), KEY `_ymatchplayer_team_id_fk` (`team_id`), KEY `_ymatchplayer_player_id_fk` (`player_id`),
			CONSTRAINT `_ymatchplayer_match_id_fk` FOREIGN KEY (`match_id`) REFERENCES `_youthmatch` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_ymatchplayer_player_id_fk` FOREIGN KEY (`player_id`) REFERENCES `_youthplayer` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_ymatchplayer_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_youthmatch_player_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `_youthmatch` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `youthmatch_player` LIKE `_youthmatch_player`;
				CREATE TABLE IF NOT EXISTS `ws3_youthmatch_player` LIKE `_youthmatch_player`;
CREATE TABLE IF NOT EXISTS `_youthmatch_reportitem` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `match_id` int(10) DEFAULT NULL, `minute` tinyint(3) DEFAULT NULL, `message_key` varchar(32) DEFAULT NULL, `message_data` varchar(255) DEFAULT NULL, `home_on_ball` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_ymatchreport_match_id_fk` (`match_id`),
			CONSTRAINT `_ymatchreport_match_id_fk` FOREIGN KEY (`match_id`) REFERENCES `_youthmatch` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_youthmatch_reportitem_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `_youthmatch` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `youthmatch_reportitem` LIKE `_youthmatch_reportitem`;
				CREATE TABLE IF NOT EXISTS `ws3_youthmatch_reportitem` LIKE `_youthmatch_reportitem`;
CREATE TABLE IF NOT EXISTS `_youthmatch_request` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `team_id` int(10) DEFAULT NULL, `matchdate` int(11) DEFAULT NULL, `reward` int(10) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `_youthrequest_team_id_fk` (`team_id`),
			CONSTRAINT `_youthrequest_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `youthmatch_request` LIKE `_youthmatch_request`;
				CREATE TABLE IF NOT EXISTS `ws3_youthmatch_request` LIKE `_youthmatch_request`;
CREATE TABLE IF NOT EXISTS `_youthplayer` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `team_id` int(10) DEFAULT NULL, `firstname` varchar(32) DEFAULT NULL, `lastname` varchar(32) DEFAULT NULL, `age` tinyint(4) DEFAULT NULL, `position` enum('Torwart','Abwehr','Mittelfeld','Sturm') DEFAULT NULL,
	`nation` varchar(32) DEFAULT NULL, `strength` tinyint(3) DEFAULT NULL, `strength_last_change` tinyint(3) DEFAULT 0, `st_goals` smallint(5) DEFAULT 0, `st_matches` smallint(5) DEFAULT 0, `st_assists` smallint(5) DEFAULT 0, `st_cards_yellow` smallint(5) DEFAULT 0,
	`st_cards_yellow_red` smallint(5) DEFAULT 0, `st_cards_red` smallint(5) DEFAULT 0, `transfer_fee` int(10) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `_youthplayer_team_id_fk` (`team_id`),
			CONSTRAINT `_youthplayer_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `youthplayer` LIKE `_youthplayer`;
				CREATE TABLE IF NOT EXISTS `ws3_youthplayer` LIKE `_youthplayer`;
CREATE TABLE IF NOT EXISTS `_youthscout` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(32) DEFAULT NULL, `expertise` tinyint(3) DEFAULT NULL, `fee` int(10) DEFAULT NULL, `speciality` enum('Torwart','Abwehr','Mittelfeld','Sturm') DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `youthscout` LIKE `_youthscout`;
				CREATE TABLE IF NOT EXISTS `ws3_youthscout` LIKE _youthscout;
CREATE TABLE IF NOT EXISTS `_amateurmatch` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `matchdate` int(11) DEFAULT NULL, `home_team_id` int(10) DEFAULT NULL, `home_noformation` enum('1','0') DEFAULT '0', `home_s1_out` int(10) DEFAULT NULL, `home_s1_in` int(10) DEFAULT NULL, `home_s1_minute` tinyint(3) DEFAULT NULL,
	`home_s1_condition` varchar(16) DEFAULT NULL, `home_s1_position` varchar(4) DEFAULT NULL, `home_s2_out` int(10) DEFAULT NULL, `home_s2_in` int(10) DEFAULT NULL, `home_s2_minute` tinyint(3) DEFAULT NULL, `home_s2_condition` varchar(16) DEFAULT NULL,
	`home_s2_position` varchar(4) DEFAULT NULL, `home_s3_out` int(10) DEFAULT NULL, `home_s3_in` int(10) DEFAULT NULL, `home_s3_minute` tinyint(3) DEFAULT NULL, `home_s3_condition` varchar(16) DEFAULT NULL, `home_s3_position` varchar(4) DEFAULT NULL,
	`home_s4_out` int(10) DEFAULT NULL, `home_s4_in` int(10) DEFAULT NULL, `home_s4_minute` tinyint(3) DEFAULT NULL, `home_s4_condition` varchar(16) DEFAULT NULL, `home_s4_position` varchar(4) DEFAULT NULL, `home_s5_out` int(10) DEFAULT NULL,
	`home_s5_in` int(10) DEFAULT NULL, `home_s5_minute` tinyint(3) DEFAULT NULL, `home_s5_condition` varchar(16) DEFAULT NULL, `home_s5_position` varchar(4) DEFAULT NULL, `home_s6_out` int(10) DEFAULT NULL, `home_s6_in` int(10) DEFAULT NULL,
	`home_s6_minute` tinyint(3) DEFAULT NULL, `home_s6_condition` varchar(16) DEFAULT NULL, `home_s6_position` varchar(4) DEFAULT NULL, `guest_team_id` int(10) DEFAULT NULL, `guest_noformation` enum('1','0') DEFAULT '0', `guest_s1_out` int(10) DEFAULT NULL,
	`guest_s1_in` int(10) DEFAULT NULL, `guest_s1_minute` tinyint(3) DEFAULT NULL, `guest_s1_condition` varchar(16) DEFAULT NULL, `guest_s1_position` varchar(4) DEFAULT NULL, `guest_s2_out` int(10) DEFAULT NULL, `guest_s2_in` int(10) DEFAULT NULL,
	`guest_s2_minute` tinyint(3) DEFAULT NULL, `guest_s2_condition` varchar(16) DEFAULT NULL, `guest_s2_position` varchar(4) DEFAULT NULL, `guest_s3_out` int(10) DEFAULT NULL, `guest_s3_in` int(10) DEFAULT NULL, `guest_s3_minute` tinyint(3) DEFAULT NULL,
	`guest_s3_condition` varchar(16) DEFAULT NULL, `guest_s3_position` varchar(4) DEFAULT NULL, `guest_s4_out` int(10) DEFAULT NULL, `guest_s4_in` int(10) DEFAULT NULL, `guest_s4_minute` tinyint(3) DEFAULT NULL, `guest_s4_condition` varchar(16) DEFAULT NULL,
	`guest_s4_position` varchar(4) DEFAULT NULL, `guest_s5_out` int(10) DEFAULT NULL, `guest_s5_in` int(10) DEFAULT NULL, `guest_s5_minute` tinyint(3) DEFAULT NULL, `guest_s5_condition` varchar(16) DEFAULT NULL, `guest_s5_position` varchar(4) DEFAULT NULL,
	`guest_s6_out` int(10) DEFAULT NULL, `guest_s6_in` int(10) DEFAULT NULL, `guest_s6_minute` tinyint(3) DEFAULT NULL, `guest_s6_condition` varchar(16) DEFAULT NULL, `guest_s6_position` varchar(4) DEFAULT NULL, `home_goals` tinyint(2) DEFAULT NULL,
	`guest_goals` tinyint(2) DEFAULT NULL, `simulated` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_amateurmatch_home_id_fk` (`home_team_id`), KEY `_amateurmatch_guest_id_fk` (`guest_team_id`),
			CONSTRAINT `_amateurmatch_guest_id_fk` FOREIGN KEY (`guest_team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_amateurmatch_home_id_fk` FOREIGN KEY (`home_team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `amateurmatch` LIKE `_amateurmatch`;
				CREATE TABLE IF NOT EXISTS `ws3_amateurmatch` LIKE `_amateurmatch`;
CREATE TABLE IF NOT EXISTS `_amateurmatch_player` (
	`match_id` int(10) NOT NULL, `team_id` int(10) DEFAULT NULL, `player_id` int(10) NOT NULL, `playernumber` tinyint(2) DEFAULT NULL, `position` varchar(24) DEFAULT NULL, `position_main` varchar(8) DEFAULT NULL, `grade` double(4,2) DEFAULT 3.00,
	`minutes_played` tinyint(2) DEFAULT 0, `card_yellow` tinyint(1) DEFAULT 0, `card_red` tinyint(1) DEFAULT 0, `goals` tinyint(2) DEFAULT 0, `state` enum('1','Ersatzbank','Ausgewechselt') DEFAULT '1', `strength` tinyint(3) DEFAULT NULL, `ballcontacts` tinyint(3) DEFAULT 0,
	`wontackles` tinyint(3) DEFAULT 0, `shoots` tinyint(3) DEFAULT 0, `passes_successed` tinyint(3) DEFAULT 0, `passes_failed` tinyint(3) DEFAULT 0, `assists` tinyint(3) DEFAULT 0, `name` varchar(128) DEFAULT NULL,
		PRIMARY KEY (`match_id`,`player_id`), KEY `_matchplayer_team_id_fk` (`team_id`), KEY `_matchplayer_player_id_fk` (`player_id`),
			CONSTRAINT `_matchplayer_match_id_fk` FOREIGN KEY (`match_id`) REFERENCES `_amateurmatch` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_matchplayer_player_id_fk` FOREIGN KEY (`player_id`) REFERENCES `_amateurplayer` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_matchplayer_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_amateurmatch_player_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `_amateurmatch` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `amateurmatch_player` LIKE `_amateurmatch_player`;
				CREATE TABLE IF NOT EXISTS `ws3_amateurmatch_player` LIKE `_amateurmatch_player`;
CREATE TABLE IF NOT EXISTS `_amateurmatch_reportitem` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `match_id` int(10) DEFAULT NULL, `minute` tinyint(3) DEFAULT NULL, `message_key` varchar(32) DEFAULT NULL, `message_data` varchar(255) DEFAULT NULL, `home_on_ball` enum('1','0') DEFAULT '0',
		PRIMARY KEY (`id`), KEY `_ymatchreport_match_id_fk` (`match_id`),
			CONSTRAINT `_matchreport_match_id_fk` FOREIGN KEY (`match_id`) REFERENCES `_amateurmatch` (`id`) ON DELETE CASCADE,
			CONSTRAINT `_amateurmatch_reportitem_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `_amateurmatch` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `amateurmatch_reportitem` LIKE `_amateurmatch_reportitem`;
				CREATE TABLE IF NOT EXISTS `ws3_amateurmatch_reportitem` LIKE `_amateurmatch_reportitem`;
CREATE TABLE IF NOT EXISTS `_amateurmatch_request` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `team_id` int(10) DEFAULT NULL, `matchdate` int(11) DEFAULT NULL, `reward` int(10) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `_amateurrequest_team_id_fk` (`team_id`),
			CONSTRAINT `_amateurrequest_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `amateurmatch_request` LIKE `_amateurmatch_request`;
				CREATE TABLE IF NOT EXISTS `ws3_amateurmatch_request` LIKE `_amateurmatch_request`;
CREATE TABLE IF NOT EXISTS `_amateurplayer` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `team_id` int(10) DEFAULT NULL, `firstname` varchar(32) DEFAULT NULL, `lastname` varchar(32) DEFAULT NULL, `age` tinyint(4) DEFAULT NULL, `position` enum('Torwart','Abwehr','Mittelfeld','Sturm') DEFAULT NULL,
	`nation` varchar(32) DEFAULT NULL, `strength` tinyint(3) DEFAULT NULL, `strength_last_change` tinyint(3) DEFAULT 0, `st_goals` smallint(5) DEFAULT 0, `st_matches` smallint(5) DEFAULT 0, `st_assists` smallint(5) DEFAULT 0, `st_cards_yellow` smallint(5) DEFAULT 0,
	`st_cards_yellow_red` smallint(5) DEFAULT 0, `st_cards_red` smallint(5) DEFAULT 0, `transfer_fee` int(10) DEFAULT 0,
		PRIMARY KEY (`id`), KEY `_amateurplayer_team_id_fk` (`team_id`),
			CONSTRAINT `_amateurplayer_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `_verein` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `amateurplayer` LIKE `_amateurplayer`;
				CREATE TABLE IF NOT EXISTS `ws3_amateurplayer` LIKE `_amateurplayer`;
CREATE TABLE IF NOT EXISTS `_amateurscout` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(32) DEFAULT NULL, `expertise` tinyint(3) DEFAULT NULL, `fee` int(10) DEFAULT NULL, `speciality` enum('Torwart','Abwehr','Mittelfeld','Sturm') DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `amateurscout` LIKE `_amateurscout`;
				CREATE TABLE IF NOT EXISTS `ws3_amateurscout` LIKE _amateurscout;
CREATE TABLE IF NOT EXISTS `_friendly_tmp` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `spieltyp` enum('Freundschaft') DEFAULT 'Freundschaft', `datum` int(10) DEFAULT NULL, `stadion_id` int(10) DEFAULT NULL, `home_verein` int(10) DEFAULT NULL, `gast_verein` int(10) DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `friendly_tmp` LIKE `_friendly_tmp`;
				CREATE TABLE IF NOT EXISTS `ws3_friendly_tmp` LIKE `_friendly_tmp`;
CREATE TABLE IF NOT EXISTS `_jugendliga` (
	`id` smallint(5) NOT NULL AUTO_INCREMENT, `name` varchar(50) DEFAULT NULL, `kurz` varchar(5) DEFAULT NULL, `land` varchar(25) DEFAULT NULL, `picture` varchar(128) DEFAULT NULL, `p_steh` tinyint(3) DEFAULT NULL, `p_sitz` tinyint(3) DEFAULT NULL,
	`p_haupt_steh` tinyint(3) DEFAULT NULL, `p_haupt_sitz` tinyint(3) DEFAULT NULL, `p_vip` tinyint(3) DEFAULT NULL, `preis_steh` smallint(5) DEFAULT NULL, `preis_sitz` smallint(5) DEFAULT NULL, `preis_vip` smallint(5) DEFAULT NULL, `admin_id` smallint(5) DEFAULT NULL,
		PRIMARY KEY (ID)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `jugendliga` LIKE `_jugendliga`;
				CREATE TABLE IF NOT EXISTS `ws3_jugendliga` LIKE `_jugendliga`;
CREATE TABLE IF NOT EXISTS `_jugendsaison` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(20) DEFAULT NULL, `liga_id` smallint(5) DEFAULT NULL, `platz_1_id` int(10) DEFAULT NULL, `platz_2_id` int(10) DEFAULT NULL, `platz_3_id` int(10) DEFAULT NULL, `platz_4_id` int(10) DEFAULT NULL,
	`platz_5_id` int(10) DEFAULT NULL, `platz_6_id` int(10) DEFAULT NULL, `platz_7_id` int(10) DEFAULT NULL, `platz_8_id` int(10) DEFAULT NULL, `platz_9_id` int(10) DEFAULT NULL, `platz_10_id` int(10) DEFAULT NULL, `platz_11_id` int(10) DEFAULT NULL,
	`platz_12_id` int(10) DEFAULT NULL, `platz_13_id` int(10) DEFAULT NULL, `platz_14_id` int(10) DEFAULT NULL, `platz_15_id` int(10) DEFAULT NULL, `platz_16_id` int(10) DEFAULT NULL, `platz_17_id` int(10) DEFAULT NULL, `platz_18_id` int(10) DEFAULT NULL,
	`platz_19_id` int(10) DEFAULT NULL, `platz_20_id` int(10) DEFAULT NULL, `beendet` enum('1','0') DEFAULT NULL DEFAULT '0',
		PRIMARY KEY (ID)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `jugendsaison` LIKE `_jugendsaison`;
				CREATE TABLE IF NOT EXISTS `ws3_jugendsaison` LIKE `_jugendsaison`;
CREATE TABLE IF NOT EXISTS `_amateurliga` (
	`id` smallint(5) NOT NULL AUTO_INCREMENT, `name` varchar(50) DEFAULT NULL, `kurz` varchar(5) DEFAULT NULL, `land` varchar(25) DEFAULT NULL, `picture` varchar(128) DEFAULT NULL, `p_steh` tinyint(3) DEFAULT NULL, `p_sitz` tinyint(3) DEFAULT NULL,
	`p_haupt_steh` tinyint(3) DEFAULT NULL, `p_haupt_sitz` tinyint(3) DEFAULT NULL, `p_vip` tinyint(3) DEFAULT NULL, `preis_steh` smallint(5) DEFAULT NULL, `preis_sitz` smallint(5) DEFAULT NULL, `preis_vip` smallint(5) DEFAULT NULL, `admin_id` smallint(5) DEFAULT NULL,
		PRIMARY KEY (ID)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `amateurliga` LIKE `_amateurliga`;
				CREATE TABLE IF NOT EXISTS `ws3_amateurliga` LIKE `_amateurliga`;
CREATE TABLE IF NOT EXISTS `_amateursaison` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(20) DEFAULT NULL, `liga_id` smallint(5) DEFAULT NULL, `platz_1_id` int(10) DEFAULT NULL, `platz_2_id` int(10) DEFAULT NULL, `platz_3_id` int(10) DEFAULT NULL, `platz_4_id` int(10) DEFAULT NULL,
	`platz_5_id` int(10) DEFAULT NULL, `platz_6_id` int(10) DEFAULT NULL, `platz_7_id` int(10) DEFAULT NULL, `platz_8_id` int(10) DEFAULT NULL, `platz_9_id` int(10) DEFAULT NULL, `platz_10_id` int(10) DEFAULT NULL, `platz_11_id` int(10) DEFAULT NULL,
	`platz_12_id` int(10) DEFAULT NULL, `platz_13_id` int(10) DEFAULT NULL, `platz_14_id` int(10) DEFAULT NULL, `platz_15_id` int(10) DEFAULT NULL, `platz_16_id` int(10) DEFAULT NULL, `platz_17_id` int(10) DEFAULT NULL, `platz_18_id` int(10) DEFAULT NULL,
	`platz_19_id` int(10) DEFAULT NULL, `platz_20_id` int(10) DEFAULT NULL, `beendet` enum('1','0') DEFAULT NULL DEFAULT '0',
		PRIMARY KEY (ID)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `amateursaison` LIKE `_amateursaison`;
				CREATE TABLE IF NOT EXISTS `ws3_amateursaison` LIKE `_amateursaison`;
CREATE TABLE IF NOT EXISTS `_teamnews` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `user_id` int(5) DEFAULT NULL, `verein_id` int(5) DEFAULT NULL, `liga_id` int(5) DEFAULT NULL, `title` varchar(75) DEFAULT NULL, `report` text DEFAULT NULL, `date` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `teamnews` LIKE `_teamnews`;
				CREATE TABLE IF NOT EXISTS `ws3_teamnews` LIKE `_teamnews`;
CREATE TABLE IF NOT EXISTS `_anpassungen` (
	`id` int(10) NOT NULL AUTO_INCREMENT, `spieler_id` int(10) DEFAULT NULL, `position_main` enum('T','LV','IV','RV','LM','DM','ZM','OM','RM','LS','MS','RS') DEFAULT NULL, `position_second` enum('T','LV','IV','RV','LM','DM','ZM','OM','RM','LS','MS','RS') DEFAULT NULL,
	`marktwert_neu` int(10) DEFAULT NULL, `link` varchar(200) DEFAULT NULL, `admin_approval_pending` enum('1','0') DEFAULT NULL DEFAULT '1', `user_id` int(10) DEFAULT NULL,
		PRIMARY KEY (`id`), KEY `_toffer_spieler_id_fk` (`spieler_id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
				CREATE TABLE IF NOT EXISTS `anpassungen` LIKE `_anpassungen`;
				CREATE TABLE IF NOT EXISTS `ws3_anpassungen` LIKE `_anpassungen`;
