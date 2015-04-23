CREATE OR REPLACE
  VIEW `v_scraper_ad`
AS
  SELECT
    `a`.`id`      AS `id`,
    `a`.`link`    AS `link`,
    `a`.`parsed`  AS `parsed`,
    `a`.`created` AS `created`,
    `a`.`status`  AS `status`,
    `p1`.`value`  AS `price`,
    `p2`.`value`  AS `currency`,
    `p3`.`value`  AS `name`,
    `p4`.`value`  AS `category`,
    `p5`.`value`  AS `type`,
    `p6`.`value`  AS `images`,
    `p7`.`value`  AS `text`,
    COALESCE(`p8`.`value`, `p8`.`value_serialized`)  AS `image_urls`

  FROM `scraper_ad` `a`
    JOIN `scraper_ad_property` `p1`
      ON (`a`.`id` = `p1`.`scraper_ad_id`) AND (`p1`.`name` = 'price')
    JOIN `scraper_ad_property` `p2`
      ON (`a`.`id` = `p2`.`scraper_ad_id`) AND (`p2`.`name` = 'currency')
    JOIN `scraper_ad_property` `p3`
      ON (`a`.`id` = `p3`.`scraper_ad_id`) AND (`p3`.`name` = 'name')
    JOIN `scraper_ad_property` `p4`
      ON (`a`.`id` = `p4`.`scraper_ad_id`) AND (`p4`.`name` = 'category')
    JOIN `scraper_ad_property` `p5`
      ON (`a`.`id` = `p5`.`scraper_ad_id`) AND (`p5`.`name` = 'type')
    JOIN `scraper_ad_property` `p6`
      ON (`a`.`id` = `p6`.`scraper_ad_id`) AND (`p6`.`name` = 'images')
    LEFT JOIN `scraper_ad_property` `p7`
      ON (`a`.`id` = `p7`.`scraper_ad_id`) AND (`p7`.`name` = 'text')
    LEFT JOIN `scraper_ad_property` `p8`
      ON (`a`.`id` = `p8`.`scraper_ad_id`) AND (`p8`.`name` = 'imageUrls');