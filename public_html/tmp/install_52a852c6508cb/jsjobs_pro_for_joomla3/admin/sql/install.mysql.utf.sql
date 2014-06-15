DROP TABLE IF EXISTS `#__js_job_config`;
CREATE TABLE  `#__js_job_config` (
  `configname` varchar(50) NOT NULL,
  `configvalue` varchar(255) NOT NULL,
  `configfor` varchar(15) NOT NULL,
  PRIMARY KEY (`configname`)
) ENGINE=MyISAM AUTO_INCREMENT=1;
INSERT INTO `#__js_job_config` VALUES ('versioncode', '1090', 'default'),('vtype','business','');
