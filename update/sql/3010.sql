UPDATE `settings` SET `value` = '{\"version\":\"30.1.0\", \"code\":\"3010\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table blog_posts modify url varchar(256) not null;

-- SEPARATOR --

alter table pages modify url varchar(256) not null;


