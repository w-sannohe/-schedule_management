INSERT INTO `user` (`user_name`, `create_id`, `update_id`)
VALUES ('san', 1, 1);
INSERT INTO `event` (`event_id`, `event_type`, `event_name`, `event_url`, `create_id`, `create_date`, `update_id`, `update_date`) VALUES (NULL, '1001', '飲み会', 'https://hitosara.com/TC18/', 1, CURRENT_TIMESTAMP, NULL, NULL), (NULL, '2001', '誕生日', 'https://hitosara.com/scene/birthday/FC26/', 1, CURRENT_TIMESTAMP, NULL, NULL),(NULL, '1002', '女子会', 'https://hitosara.com/TC7/', 1, CURRENT_TIMESTAMP, NULL, NULL), (NULL, '1003', '歓送迎会', 'https://hitosara.com/contents/sushi/', 1, CURRENT_TIMESTAMP, NULL, NULL),(NULL, '3001', 'デート', 'https://hitosara.com/scene/date/', 1, CURRENT_TIMESTAMP, NULL, NULL),(NULL, '2002', 'ランチ', 'https://hitosara.com/lunch/', 1, CURRENT_TIMESTAMP, NULL, NULL);