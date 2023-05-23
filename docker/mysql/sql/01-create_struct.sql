CREATE DATABASE  IF NOT EXISTS `schedule_management` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `schedule_management`;

CREATE TABLE `user` (
  `user_id` int(11) AUTO_INCREMENT NOT NULL COMMENT 'ユーザーID',
  `user_name` varchar(128) DEFAULT NULL COMMENT 'ユーザー名',
  `create_id` varchar(15) DEFAULT NULL COMMENT '作成者ID',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `update_id` varchar(15) DEFAULT NULL COMMENT '更新者ID',
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ユーザー情報';

CREATE TABLE `timetree_login` (
  `timetree_login_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'タイムツリーログインID',
  `user_id` int(11) NOT NULL COMMENT 'ユーザーID',
  `calender_id` varchar(128) NOT NULL COMMENT 'カレンダーID',
  `calender_token` varchar(128) DEFAULT NULL COMMENT 'カレンダートークン',
  `create_id` varchar(15) DEFAULT NULL COMMENT '作成者ID',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `update_id` varchar(15) DEFAULT NULL COMMENT '更新者ID',
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`timetree_login_id`),
  KEY `user_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='タイムツリーユーザー情報';

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'イベントID',
  `event_type` int(10) NOT NULL COMMENT 'イベントタイプ',
  `event_name` varchar(128) NOT NULL COMMENT 'イベント名',
  `event_url` varchar(128) DEFAULT NULL COMMENT 'ヒトサラURL',
  `create_id` varchar(15) DEFAULT NULL COMMENT '作成者ID',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `update_id` varchar(15) DEFAULT NULL COMMENT '更新者ID',
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='イベント情報';

CREATE TABLE `resist_timetree_event` (
  `resist_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '登録したイベントのID',
  `user_id` int(11) NOT NULL COMMENT 'ユーザーID',
  `hitosara_event_id` int(10) NOT NULL COMMENT 'eventTableのID',
  `timetree_event_id` varchar(128) NOT NULL COMMENT 'timetreeのevent_id',
  `title` varchar(128) DEFAULT NULL COMMENT '予定名',
  `start_at` varchar(128) NOT NULL COMMENT '開始時間',
  `end_at` varchar(128) NOT NULL COMMENT '終了時間',
  `location` varchar(128) DEFAULT NULL COMMENT '場所',
  `location_lat` varchar(128) NOT NULL COMMENT '緯度',
  `location_lon` varchar(128) NOT NULL COMMENT '軽度',
  `url` varchar(128) DEFAULT NULL COMMENT '予定URL',
  `tt_updated_at` varchar(128) DEFAULT NULL COMMENT '予定updated_at',
  `tt_created_at` varchar(128) NOT NULL COMMENT '予定created_at',
  `json` varchar(128) NOT NULL COMMENT '返却レスポンス',
  `create_id` varchar(15) DEFAULT NULL COMMENT '作成者ID',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `update_id` varchar(15) DEFAULT NULL COMMENT '更新者ID',
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`resist_id`),
  KEY `user_user_id` (`user_id`),
  KEY `event_event_id` (`hitosara_event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='登録済みのイベント情報';

CREATE TABLE `geo` (
  `geo_id` int(11) AUTO_INCREMENT NOT NULL COMMENT 'GEO ID',
  `prefecture_id` varchar(128) DEFAULT NULL COMMENT '県id',
  `prefecture_name` varchar(128) DEFAULT NULL COMMENT '県名',
  `prefecture_url` varchar(128) DEFAULT NULL COMMENT '県URL',
  `city_id` varchar(128) DEFAULT NULL COMMENT '市id',
  `city_name` varchar(128) DEFAULT NULL COMMENT '市名',
  `city_url` varchar(128) DEFAULT NULL COMMENT '市URL', 
  `create_id` varchar(15) DEFAULT NULL COMMENT '作成者ID',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `update_id` varchar(15) DEFAULT NULL COMMENT '更新者ID',
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`geo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='GEO情報';

CREATE TABLE `main_type` (
  `main_type_id` int(11) AUTO_INCREMENT NOT NULL COMMENT 'メインID',
  `main_id` varchar(128) DEFAULT NULL COMMENT 'メインid',
  `main_name` varchar(128) DEFAULT NULL COMMENT 'メイン名',
  `main_url` varchar(128) DEFAULT NULL COMMENT 'メインURL',
  `create_id` varchar(15) DEFAULT NULL COMMENT '作成者ID',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `update_id` varchar(15) DEFAULT NULL COMMENT '更新者ID',
  `update_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`main_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='メイン情報';