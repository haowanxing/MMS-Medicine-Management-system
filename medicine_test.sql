# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.19)
# Database: medicine
# Generation Time: 2017-02-10 10:26:27 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table medic_adjust
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_adjust`;

CREATE TABLE `medic_adjust` (
  `adjust_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '调价编号',
  `stock_id` int(10) unsigned NOT NULL COMMENT '库存编号',
  `oldprice` decimal(8,2) NOT NULL COMMENT '原单价',
  `newprice` decimal(8,2) NOT NULL COMMENT '新单价',
  `time` int(11) NOT NULL COMMENT '调价时间',
  `adjust_by` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '调价员',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`adjust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_adjust` WRITE;
/*!40000 ALTER TABLE `medic_adjust` DISABLE KEYS */;

INSERT INTO `medic_adjust` (`adjust_id`, `stock_id`, `oldprice`, `newprice`, `time`, `adjust_by`, `shop_id`)
VALUES
	(1,1,1.00,3.20,0,1,1),
	(2,5,23.00,20.00,1466229846,1,1),
	(3,5,20.00,20.01,1466245348,1,1),
	(4,5,20.01,20.00,1466245533,1,1),
	(5,5,20.00,10.00,1466248881,1,1),
	(6,1,15.00,0.00,1466492058,1,1),
	(7,1,0.00,19.00,1466492064,1,1),
	(8,1,15.00,13.50,1480384210,1,1),
	(9,7,10.00,9.50,1480386427,1,1),
	(10,8,14.80,16.00,1484198874,1,1),
	(11,7,9.50,14.50,1484198888,1,1),
	(12,6,3.20,3.50,1484198902,1,1),
	(13,4,19.20,22.00,1484198917,1,1),
	(14,1,13.50,25.20,1484198928,1,1),
	(15,8,16.00,15.50,1484203076,0,1),
	(16,8,15.50,16.00,1484203473,0,1),
	(17,9,1.00,3.00,1484394339,0,1),
	(18,9,3.00,1.00,1484394422,0,1),
	(19,10,11.00,10.00,1486718340,2,2),
	(20,9,1.00,5.00,1486718730,1,1);

/*!40000 ALTER TABLE `medic_adjust` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_breakage
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_breakage`;

CREATE TABLE `medic_breakage` (
  `break_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '报损编号',
  `stock_id` int(10) unsigned NOT NULL COMMENT '库存编号',
  `break_amount` int(4) NOT NULL COMMENT '数量',
  `time` int(11) NOT NULL COMMENT '报损时间',
  `break_by` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '报损员id',
  `allprice` decimal(8,2) NOT NULL COMMENT '报损总额',
  `reason` varchar(50) NOT NULL DEFAULT '无' COMMENT '报损原因',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`break_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_breakage` WRITE;
/*!40000 ALTER TABLE `medic_breakage` DISABLE KEYS */;

INSERT INTO `medic_breakage` (`break_id`, `stock_id`, `break_amount`, `time`, `break_by`, `allprice`, `reason`, `shop_id`)
VALUES
	(1,1,2,1313131,1,30.00,'无',1),
	(2,3,1,1466268444,1,18.20,'NO',1),
	(3,3,-1,1466268587,1,-18.20,'',1),
	(4,3,1,1476194987,1,18.20,'',1),
	(5,1,3,1476195017,1,57.00,'',1),
	(6,2,10,1484204379,1,100.00,'过期拉',1),
	(7,2,1,1484205370,1,10.00,'',1),
	(8,2,-2,1484406459,1,-20.00,'',1),
	(9,10,1,1486719705,2,10.00,'自己吃了',2);

/*!40000 ALTER TABLE `medic_breakage` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_business
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_business`;

CREATE TABLE `medic_business` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商户人员ID',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商户ID',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `lasttime` int(10) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `admin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '1为管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_business` WRITE;
/*!40000 ALTER TABLE `medic_business` DISABLE KEYS */;

INSERT INTO `medic_business` (`id`, `shop_id`, `username`, `password`, `realname`, `lasttime`, `admin`)
VALUES
	(1,1,'anthony','e10adc3949ba59abbe56e057f20f883e','Anthony.Liu',1486720731,1),
	(2,2,'anthony_box','e10adc3949ba59abbe56e057f20f883e','Anthony.Ma',1486702029,1),
	(5,1,'rockysu','e10adc3949ba59abbe56e057f20f883e','Rocky.Su',0,0);

/*!40000 ALTER TABLE `medic_business` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_company
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_company`;

CREATE TABLE `medic_company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned DEFAULT '1' COMMENT '店铺ID',
  `c_name` varchar(255) NOT NULL DEFAULT '' COMMENT '公司名称',
  `c_addr` varchar(255) DEFAULT '不祥' COMMENT '公司地址',
  `c_phone` varchar(15) DEFAULT '0' COMMENT '公司电话',
  `c_man1` varchar(32) DEFAULT '无' COMMENT '联系人1',
  `c_manphone1` varchar(15) DEFAULT '0' COMMENT '联系人1电话',
  `c_man2` varchar(32) DEFAULT '无' COMMENT '联系人2',
  `c_manphone2` varchar(15) DEFAULT '0' COMMENT '联系人2电话',
  `c_remark` varchar(255) DEFAULT '无' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_company` WRITE;
/*!40000 ALTER TABLE `medic_company` DISABLE KEYS */;

INSERT INTO `medic_company` (`id`, `shop_id`, `c_name`, `c_addr`, `c_phone`, `c_man1`, `c_manphone1`, `c_man2`, `c_manphone2`, `c_remark`)
VALUES
	(1,1,'云南傣药有限公司','云南省德宏州瑞丽市金滇路东侧','032-2322332','王某某','13333330000','张某某','15533445566','无'),
	(2,1,'安徽丰原药业股份有限公司','安徽省无为县北大街108号','044-23223323','小强','13290009878','小王','18999883323','北极'),
	(3,2,'辅仁药业集团有限公司','河南省鹿邑县产业集聚区同源路1号','012-43432211','Boks.lu','13333994857','Roer.li','16653332213','None2');

/*!40000 ALTER TABLE `medic_company` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_drugs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_drugs`;

CREATE TABLE `medic_drugs` (
  `drug_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '药品编号',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '药品名称',
  `pinyinma` varchar(50) NOT NULL DEFAULT '' COMMENT '拼音码',
  `spec` varchar(50) NOT NULL DEFAULT '' COMMENT '药品规格',
  `unit` varchar(50) NOT NULL DEFAULT '' COMMENT '单位',
  `lowwarning` int(11) NOT NULL COMMENT '库存低限',
  `shop_id` int(10) unsigned NOT NULL COMMENT '商户ID',
  PRIMARY KEY (`drug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_drugs` WRITE;
/*!40000 ALTER TABLE `medic_drugs` DISABLE KEYS */;

INSERT INTO `medic_drugs` (`drug_id`, `name`, `pinyinma`, `spec`, `unit`, `lowwarning`, `shop_id`)
VALUES
	(1,'板蓝根颗粒','BLGKL','10g*10袋','袋',15,1),
	(2,'头孢氨苄缓释胶囊','TBABHSJN','1板/盒','盒',5,1),
	(3,'强力枇杷露','QLPPL','150ml/瓶','瓶',8,1),
	(4,'西地碘含片(华素片)','XDDHP','15片/板，1板/盒','盒',5,1),
	(5,'西地碘含片2(华素片)','XDDHP2','12片/板，2板/盒','盒',10,1),
	(6,'阿莫西林','AMXL','1板/盒','盒',5,1),
	(7,'维生素C片','WSSCP','0.1克X100片','瓶',10,1),
	(8,'滴通鼻炎水','DTBYS','10ml/瓶','瓶',11,1),
	(9,'氨咖黄敏胶囊','AKHMJN','5板/盒','盒',5,2);

/*!40000 ALTER TABLE `medic_drugs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_order`;

CREATE TABLE `medic_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `orderno` varchar(20) NOT NULL,
  `buyyer` varchar(50) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `sell_by` int(10) unsigned DEFAULT NULL,
  `shop_id` int(10) unsigned NOT NULL COMMENT '商户ID',
  PRIMARY KEY (`order_id`),
  KEY `ono` (`orderno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_order` WRITE;
/*!40000 ALTER TABLE `medic_order` DISABLE KEYS */;

INSERT INTO `medic_order` (`order_id`, `orderno`, `buyyer`, `time`, `total`, `sell_by`, `shop_id`)
VALUES
	(1,'2016101204032725','',1476259407,0.00,1,1),
	(2,'2016101204043426','',1476259474,15.00,1,1),
	(3,'2016101204050940','',1476259509,30.00,1,1),
	(4,'2016101204055028','',1476259550,36.40,1,1),
	(5,'2016101410104418',NULL,1476454244,0.00,1,1),
	(6,'2016101410124663','',1476454366,0.00,1,1),
	(7,'2016101507410123','',1476531661,23.20,1,1),
	(8,'2016112909363167','',1480383391,0.00,1,1),
	(9,'2016112909494053','中南民族大学',1480384180,30.00,1,1),
	(10,'2016122201214324','北京地中海网络研究所',1482384103,31.70,1,1),
	(11,'2016122203444793','',1482392687,10.00,1,1),
	(12,'2016122203463533','',1482392795,10.00,1,1),
	(13,'2016122203480432','',1482392884,66.30,1,1),
	(14,'2016122203484779','',1482392927,66.30,1,1),
	(15,'2016122203492448','',1482392964,9.50,1,1),
	(16,'2016122203511858','北京迈外迪网络科技有限公司',1482393078,36.40,1,1),
	(17,'2016122203551074','北京迈外迪网络科技有限公司',1482393310,134.50,1,1),
	(18,'2017011210072936','',1484186849,13.50,1,1),
	(19,'2017011210080450','',1484186884,13.50,1,1),
	(20,'2017011210084059','',1484186920,13.50,1,1),
	(21,'2017011210152152','',1484187321,95.10,1,1),
	(22,'2017011206362010','北京迈外迪',1484217380,26.00,1,1),
	(23,'2017011407141782','',1484392457,0.00,1,1),
	(24,'2017011407171858','',1484392638,0.00,1,1),
	(25,'2017011407300076','',1484393400,0.00,1,1),
	(26,'2017011407301142','',1484393411,0.00,1,1),
	(27,'2017011407305133','',1484393451,0.00,1,1),
	(28,'2017011407324583','',1484393565,0.00,1,1),
	(29,'2017011407330944','',1484393589,0.00,1,1),
	(30,'2017011407384274','test1',1484393922,25.20,1,1),
	(31,'2017011407393177','',1484393971,53.40,1,1),
	(32,'2017011407413173','',1484394091,30.50,1,1),
	(33,'2017011407425122','北京大学',1484394171,29.00,1,1),
	(34,'2017011410590556','',1484405945,13.00,1,1),
	(35,'2017011411010395','',1484406064,10.00,1,1),
	(36,'2017011411040977','',1484406249,10.00,1,1),
	(37,'2017011411373334','',1484408253,30.00,2,1),
	(38,'2017021004171423','自己',1486714634,11.00,2,2);

/*!40000 ALTER TABLE `medic_order` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_return
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_return`;

CREATE TABLE `medic_return` (
  `ret_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '退货编号',
  `sell_id` int(10) unsigned NOT NULL COMMENT '销售编号',
  `ret_amount` int(4) NOT NULL COMMENT '退货数量',
  `totalprice` decimal(8,2) NOT NULL COMMENT '退货总额',
  `time` int(11) NOT NULL COMMENT '退货时间',
  `return_by` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '操作员id',
  `reason` varchar(50) NOT NULL DEFAULT '' COMMENT '退货原因',
  `shop_id` int(10) unsigned NOT NULL COMMENT '商户ID',
  PRIMARY KEY (`ret_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_return` WRITE;
/*!40000 ALTER TABLE `medic_return` DISABLE KEYS */;

INSERT INTO `medic_return` (`ret_id`, `sell_id`, `ret_amount`, `totalprice`, `time`, `return_by`, `reason`, `shop_id`)
VALUES
	(1,6,1,3.20,1480383811,1,'',1),
	(2,7,1,10.00,1483802368,1,'测试价格',1),
	(3,4,2,30.00,1484201450,1,'测试yxiia',1),
	(4,5,2,6.40,1484201575,1,'',1),
	(5,52,1,11.00,1486717845,2,'',2);

/*!40000 ALTER TABLE `medic_return` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_sell
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_sell`;

CREATE TABLE `medic_sell` (
  `sell_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '销售编号',
  `stock_id` int(11) unsigned NOT NULL COMMENT '库存id',
  `orderno` varchar(20) NOT NULL DEFAULT '' COMMENT 'Order流水号',
  `price` decimal(8,2) NOT NULL COMMENT '销售单价',
  `sell_amount` int(4) NOT NULL COMMENT '销售数量',
  `subtotal` decimal(8,2) NOT NULL COMMENT '销售小计',
  `sell_status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0为正常,1为退货',
  `shop_id` int(10) unsigned NOT NULL COMMENT '商户ID',
  PRIMARY KEY (`sell_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_sell` WRITE;
/*!40000 ALTER TABLE `medic_sell` DISABLE KEYS */;

INSERT INTO `medic_sell` (`sell_id`, `stock_id`, `orderno`, `price`, `sell_amount`, `subtotal`, `sell_status`, `shop_id`)
VALUES
	(1,1,'2016101204032725',15.00,2,30.00,0,1),
	(2,1,'2016101204043426',15.00,1,15.00,0,1),
	(3,1,'2016101204050940',15.00,2,30.00,0,1),
	(4,1,'2016101204055028',15.00,2,30.00,1,1),
	(5,6,'2016101204055028',3.20,2,6.40,1,1),
	(6,6,'2016101507410123',3.20,1,3.20,1,1),
	(7,2,'2016101507410123',10.00,2,20.00,1,1),
	(8,1,'2016112909494053',15.00,2,30.00,0,1),
	(9,2,'2016122212114734',10.00,1,10.00,0,1),
	(10,7,'2016122212122057',9.50,1,9.50,0,1),
	(13,3,'2016122201034241',18.20,1,18.20,0,1),
	(14,3,'2016122201214324',18.20,1,18.20,0,1),
	(15,1,'2016122201214324',13.50,1,13.50,0,1),
	(16,2,'2016122203444793',10.00,1,10.00,0,1),
	(17,2,'2016122203463533',10.00,1,10.00,0,1),
	(18,2,'2016122203480432',10.00,1,10.00,0,1),
	(19,6,'2016122203480432',3.20,2,6.40,0,1),
	(20,1,'2016122203480432',13.50,1,13.50,0,1),
	(21,3,'2016122203480432',18.20,2,36.40,0,1),
	(22,2,'2016122203484779',10.00,1,10.00,0,1),
	(23,6,'2016122203484779',3.20,2,6.40,0,1),
	(24,1,'2016122203484779',13.50,1,13.50,0,1),
	(25,3,'2016122203484779',18.20,2,36.40,0,1),
	(26,7,'2016122203492448',9.50,1,9.50,0,1),
	(27,3,'2016122203511858',18.20,2,36.40,0,1),
	(28,1,'2016122203551074',13.50,3,40.50,0,1),
	(29,6,'2016122203551074',3.20,3,9.60,0,1),
	(30,2,'2016122203551074',10.00,1,10.00,0,1),
	(31,3,'2016122203551074',18.20,2,36.40,0,1),
	(32,7,'2016122203551074',9.50,4,38.00,0,1),
	(33,1,'2017011210072936',13.50,1,13.50,0,1),
	(34,1,'2017011210080450',13.50,1,13.50,0,1),
	(35,1,'2017011210084059',13.50,1,13.50,0,1),
	(36,3,'2017011210152152',18.20,1,18.20,0,1),
	(37,1,'2017011210152152',13.50,3,40.50,0,1),
	(38,3,'2017011210152152',18.20,2,36.40,0,1),
	(39,2,'2017011206362010',10.00,1,10.00,0,1),
	(40,8,'2017011206362010',16.00,1,16.00,0,1),
	(41,1,'2017011407384274',25.20,1,25.20,0,1),
	(42,1,'2017011407393177',25.20,1,25.20,0,1),
	(43,2,'2017011407393177',10.00,1,10.00,0,1),
	(44,3,'2017011407393177',18.20,1,18.20,0,1),
	(45,8,'2017011407413173',16.00,1,16.00,0,1),
	(46,7,'2017011407413173',14.50,1,14.50,0,1),
	(47,7,'2017011407425122',14.50,2,29.00,0,1),
	(48,2,'2017011410590556',10.00,1,13.00,0,1),
	(49,2,'2017011411010395',10.00,1,10.00,0,1),
	(50,2,'2017011411040977',10.00,1,10.00,0,1),
	(51,2,'2017011411373334',10.00,3,30.00,0,1),
	(52,10,'2017021004171423',11.00,1,11.00,1,2);

/*!40000 ALTER TABLE `medic_sell` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_shop
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_shop`;

CREATE TABLE `medic_shop` (
  `shop_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `storename` varchar(50) NOT NULL DEFAULT '' COMMENT '药店名称',
  `telephone` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '手机号码',
  `address` varchar(50) NOT NULL DEFAULT '' COMMENT '药店地址',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_shop` WRITE;
/*!40000 ALTER TABLE `medic_shop` DISABLE KEYS */;

INSERT INTO `medic_shop` (`shop_id`, `storename`, `telephone`, `mobile`, `address`)
VALUES
	(1,'康和药房','027-67841011','13296687974','湖北省武汉市民族大道182号'),
	(2,'万众大药房','027-67841012','17700002222','湖北省武汉市民族大道189号');

/*!40000 ALTER TABLE `medic_shop` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_stock
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_stock`;

CREATE TABLE `medic_stock` (
  `stock_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '库存编号',
  `drug_id` int(10) unsigned NOT NULL COMMENT '药品id',
  `factory` varchar(50) NOT NULL DEFAULT '' COMMENT '生产厂家',
  `stock_amount` int(4) unsigned NOT NULL COMMENT '库存数量',
  `pihao` varchar(50) NOT NULL DEFAULT '' COMMENT '批号',
  `pizhunwenhao` varchar(50) NOT NULL DEFAULT '' COMMENT '批准文号',
  `sellprice` decimal(8,2) NOT NULL COMMENT '销售单价',
  `in_time` int(11) NOT NULL COMMENT '入库时间',
  `producedate` int(11) NOT NULL COMMENT '生产日期',
  `usefuldate` int(11) NOT NULL COMMENT '失效日期',
  `shop_id` int(10) unsigned NOT NULL COMMENT '商户ID',
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_stock` WRITE;
/*!40000 ALTER TABLE `medic_stock` DISABLE KEYS */;

INSERT INTO `medic_stock` (`stock_id`, `drug_id`, `factory`, `stock_amount`, `pihao`, `pizhunwenhao`, `sellprice`, `in_time`, `producedate`, `usefuldate`, `shop_id`)
VALUES
	(1,1,'Harbin',21,'02301','Hong2313',25.20,0,0,0,1),
	(2,2,'Sharbi',29,'1300','GuangA2',10.00,1010101,10101,20202,1),
	(3,3,'华润三九（南昌）药业有限公司',44,'1604003J','国药准字Z36021533',18.20,1466092800,1466006400,1529078400,1),
	(4,3,'华润三九（南昌）药业有限公司',10,'1604003J','国药准字Z36021533',22.00,1466092800,1466092800,1529078400,1),
	(5,2,'',11,'1601003H','国药准字Z36221533',10.00,1466092800,1466006400,1528732800,1),
	(6,6,'华润三九（南昌）药业有限公司',21,'8238898','WUS3222',3.50,1466352000,1464710400,1468944000,1),
	(7,7,'华中药业股份有限公司',93,'20160426','国药准字H42020614',14.50,2016,2016,2017,1),
	(8,4,'华中药业股份有限公司',18,'1601003H','国药准字Z36221533',16.00,2017,2017,2018,1),
	(9,8,'',3,'','',5.00,1484323200,0,0,1),
	(10,9,'辅仁药业集团有限公司',2,'20120302','国药准字H20073348',10.00,1486656000,1485878400,1551283200,2);

/*!40000 ALTER TABLE `medic_stock` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_storage
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_storage`;

CREATE TABLE `medic_storage` (
  `storage_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '入库编号',
  `drug_id` int(10) unsigned NOT NULL COMMENT '药品id',
  `pihao` varchar(50) NOT NULL DEFAULT '' COMMENT '批号',
  `pizhunwenhao` varchar(50) NOT NULL DEFAULT '' COMMENT '批准文号',
  `storage_amount` int(4) NOT NULL COMMENT '进货数量',
  `inprice` decimal(8,2) NOT NULL COMMENT '进货单价',
  `allprice` decimal(8,2) NOT NULL COMMENT '进货总额',
  `in_time` int(11) NOT NULL COMMENT '进货时间',
  `in_from` varchar(50) NOT NULL DEFAULT '' COMMENT '进货单位',
  `factory` varchar(50) NOT NULL DEFAULT '' COMMENT '生产厂家',
  `producedate` int(11) NOT NULL COMMENT '生产日期',
  `usefuldate` int(11) NOT NULL COMMENT '失效日期',
  `in_by` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '操作员id',
  `remark` varchar(50) NOT NULL DEFAULT '无' COMMENT '备注',
  `shop_id` int(10) unsigned NOT NULL COMMENT '商户ID',
  PRIMARY KEY (`storage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_storage` WRITE;
/*!40000 ALTER TABLE `medic_storage` DISABLE KEYS */;

INSERT INTO `medic_storage` (`storage_id`, `drug_id`, `pihao`, `pizhunwenhao`, `storage_amount`, `inprice`, `allprice`, `in_time`, `in_from`, `factory`, `producedate`, `usefuldate`, `in_by`, `remark`, `shop_id`)
VALUES
	(1,1,'abc','Jiang030',10,40.20,402.00,0,'','',0,0,1,'无',1),
	(2,2,'20150111','江0100',100,20.00,2000.00,16,'民大校医','中国',1,30,1,'进货',1),
	(3,3,'1604003J','国药准字Z36021533',8,18.20,145.60,1466006400,'校医院来的','华润三九（南昌）药业有限公司',1459872000,1520265600,1,'本人操作',1),
	(4,3,'1604003J','国药准字Z36021533',2,18.20,1.00,1466092800,'中南民族大学','华润三九（南昌）药业有限公司',1466006400,1529078400,1,'我',1),
	(5,3,'1604003J','国药准字Z36021533',8,18.20,1.00,1466092800,'中南民族大学','华润三九（南昌）药业有限公司',1466006400,1529078400,1,'我',1),
	(6,3,'1604003J','国药准字Z36021533',10,18.20,1.00,1466092800,'中南民族大学','华润三九（南昌）药业有限公司',1466092800,1529078400,1,'我',1),
	(7,2,'1601003H','国药准字Z36221533',10,23.00,230.00,1466092800,'民大','',1466006400,1528732800,1,'',1),
	(8,2,'1601003H','国药准字Z36221533',1,23.00,23.00,1466092800,'民大','',1466006400,1528732800,1,'',1),
	(9,6,'8238898','WUS3222',3,3.20,9.60,1466352000,'民大','华润三九（南昌）药业有限公司',1464710400,1468944000,1,'0',1),
	(10,1,'isjak','wijeofiwjfoij',40,5.00,200.00,1476201600,'n','kswia',1475251200,1477843200,1,'None',1),
	(11,2,'isjak','wijeofiwjfoij',50,12.00,600.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None',1),
	(12,3,'isjak','wijeofiwjfoij',50,14.50,725.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None',1),
	(13,4,'isjak','wijeofiwjfoij',30,6.50,195.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None',1),
	(14,5,'isjak','wijeofiwjfoij',30,6.50,195.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None',1),
	(15,6,'isjak24578','PK2232131',30,7.90,237.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None',1),
	(16,7,'20160426','国药准字H42020614',2,10.00,20.00,1480348800,'中南民族大学校医院','华中药业股份有限公司',1460736000,1504195200,1,'测试',1),
	(17,7,'20160426','国药准字H42020614',2,10.00,20.00,1480348800,'中南民族大学校医院','华中药业股份有限公司',1460736000,1504195200,1,'测试',1),
	(18,7,'20160426','国药准字H42020614',2,10.00,20.00,1480348800,'中南民族大学校医院','华中药业股份有限公司',1460736000,1504195200,1,'测试',1),
	(19,7,'20160426','国药准字H42020614',2,10.00,20.00,1480348800,'中南民族大学校医院','华中药业股份有限公司',1460736000,1504195200,1,'测试',1),
	(20,7,'20160426','国药准字H42020614',2,10.00,20.00,1480348800,'中南民族大学校医院','华中药业股份有限公司',1460736000,1504195200,1,'测试',1),
	(21,7,'20160426','国药准字H42020614',2,10.00,20.00,1480348800,'中南民族大学校医院','华中药业股份有限公司',1460736000,1504195200,1,'测试',1),
	(22,7,'20160426','国药准字H42020614',2,10.00,20.00,1480348800,'中南民族大学校医院','华中药业股份有限公司',1460736000,1504195200,1,'测试',1),
	(23,7,'20160426','国药准字H42020614',2,10.00,20.00,1480348800,'中南民族大学校医院','华中药业股份有限公司',1460736000,1504195200,1,'测试',1),
	(24,7,'20160426','国药准字H42020614',2,10.00,20.00,1480348800,'中南民族大学校医院','华中药业股份有限公司',1460736000,1504195200,1,'测试',1),
	(25,4,'1601003H','国药准字Z36221533',10,14.80,148.00,2017,'民大','华中药业股份有限公司',2017,2018,1,'没有',1),
	(27,4,'1601003H','国药准字Z36221533',10,5.00,50.00,1484150400,'','Harbin',1483200000,1517932800,1,'',1),
	(28,8,'','',0,1.00,1.00,1484323200,'','',0,0,1,'',1),
	(29,8,'','',3,1.00,1.00,1484323200,'','',0,0,1,'',1),
	(30,2,'1601003H','国药准字H42020614',30,20.00,600.00,1484323200,'Aj66','Harbin',1483545600,1548777600,1,'',1),
	(31,9,'20120302','国药准字H20073348',3,11.00,33.00,1486656000,'自己','辅仁药业集团有限公司',1485878400,1551283200,2,'',2);

/*!40000 ALTER TABLE `medic_storage` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_users`;

CREATE TABLE `medic_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `admin` int(11) NOT NULL DEFAULT '0' COMMENT '0为非管理员,1为管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_users` WRITE;
/*!40000 ALTER TABLE `medic_users` DISABLE KEYS */;

INSERT INTO `medic_users` (`id`, `username`, `password`, `realname`, `lasttime`, `admin`)
VALUES
	(1,'201321092028','e10adc3949ba59abbe56e057f20f883e','刘经济',1486618341,1),
	(2,'201321092032','e10adc3949ba59abbe56e057f20f883e','姚康华',1484408164,0),
	(3,'201321092023','b59c67bf196a4758191e42f76670ceba','张吉龙',0,0);

/*!40000 ALTER TABLE `medic_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
