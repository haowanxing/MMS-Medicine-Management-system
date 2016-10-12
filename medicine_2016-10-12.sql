# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.19)
# Database: medicine
# Generation Time: 2016-10-12 08:11:02 +0000
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
  PRIMARY KEY (`adjust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_adjust` WRITE;
/*!40000 ALTER TABLE `medic_adjust` DISABLE KEYS */;

INSERT INTO `medic_adjust` (`adjust_id`, `stock_id`, `oldprice`, `newprice`, `time`, `adjust_by`)
VALUES
	(1,1,1.00,3.20,0,1),
	(2,5,23.00,20.00,1466229846,1),
	(3,5,20.00,20.01,1466245348,1),
	(4,5,20.01,20.00,1466245533,1),
	(5,5,20.00,10.00,1466248881,1),
	(6,1,15.00,0.00,1466492058,1),
	(7,1,0.00,19.00,1466492064,1);

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
  PRIMARY KEY (`break_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_breakage` WRITE;
/*!40000 ALTER TABLE `medic_breakage` DISABLE KEYS */;

INSERT INTO `medic_breakage` (`break_id`, `stock_id`, `break_amount`, `time`, `break_by`, `allprice`, `reason`)
VALUES
	(1,1,2,1313131,1,30.00,'无'),
	(2,3,1,1466268444,1,18.20,'NO'),
	(3,3,-1,1466268587,1,-18.20,''),
	(4,3,1,1476194987,1,18.20,''),
	(5,1,3,1476195017,1,57.00,'');

/*!40000 ALTER TABLE `medic_breakage` ENABLE KEYS */;
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
  PRIMARY KEY (`drug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_drugs` WRITE;
/*!40000 ALTER TABLE `medic_drugs` DISABLE KEYS */;

INSERT INTO `medic_drugs` (`drug_id`, `name`, `pinyinma`, `spec`, `unit`, `lowwarning`)
VALUES
	(1,'板蓝根颗粒','BLGKL','10g*10袋','袋',10),
	(2,'头孢氨苄缓释胶囊','TBABHSJN','1板/盒','盒',5),
	(3,'强力枇杷露','QLPPL','150ml/瓶','瓶',8),
	(4,'西地碘含片(华素片)','XDDHP','15片/板，1板/盒','盒',5),
	(5,'西地碘含片(华素片)','XDDHP2','12片/板，2板/盒','盒',3),
	(6,'阿莫西林','AMXL','1板/盒','盒',5);

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
  PRIMARY KEY (`order_id`),
  KEY `ono` (`orderno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_order` WRITE;
/*!40000 ALTER TABLE `medic_order` DISABLE KEYS */;

INSERT INTO `medic_order` (`order_id`, `orderno`, `buyyer`, `time`, `total`, `sell_by`)
VALUES
	(1,'2016101204032725','',1476259407,0.00,1),
	(2,'2016101204043426','',1476259474,15.00,1),
	(3,'2016101204050940','',1476259509,30.00,1),
	(4,'2016101204055028','',1476259550,36.40,1);

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
  PRIMARY KEY (`ret_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



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
  PRIMARY KEY (`sell_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_sell` WRITE;
/*!40000 ALTER TABLE `medic_sell` DISABLE KEYS */;

INSERT INTO `medic_sell` (`sell_id`, `stock_id`, `orderno`, `price`, `sell_amount`, `subtotal`, `sell_status`)
VALUES
	(1,1,'2016101204032725',15.00,2,30.00,0),
	(2,1,'2016101204043426',15.00,1,15.00,0),
	(3,1,'2016101204050940',15.00,2,30.00,0),
	(4,1,'2016101204055028',15.00,2,30.00,0),
	(5,6,'2016101204055028',3.20,2,6.40,0);

/*!40000 ALTER TABLE `medic_sell` ENABLE KEYS */;
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
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_stock` WRITE;
/*!40000 ALTER TABLE `medic_stock` DISABLE KEYS */;

INSERT INTO `medic_stock` (`stock_id`, `drug_id`, `factory`, `stock_amount`, `pihao`, `pizhunwenhao`, `sellprice`, `in_time`, `producedate`, `usefuldate`)
VALUES
	(1,1,'Harbin',3,'02301','Hong2313',15.00,0,0,0),
	(2,2,'Sharbi',23,'1300','GuangA2',10.00,1010101,10101,20202),
	(3,3,'华润三九（南昌）药业有限公司',8,'1604003J','国药准字Z36021533',18.20,1466092800,1466006400,1529078400),
	(4,3,'华润三九（南昌）药业有限公司',10,'1604003J','国药准字Z36021533',19.20,1466092800,1466092800,1529078400),
	(5,2,'',11,'1601003H','国药准字Z36221533',10.00,1466092800,1466006400,1528732800),
	(6,6,'华润三九（南昌）药业有限公司',1,'8238898','WUS3222',3.20,1466352000,1464710400,1468944000);

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
  PRIMARY KEY (`storage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_storage` WRITE;
/*!40000 ALTER TABLE `medic_storage` DISABLE KEYS */;

INSERT INTO `medic_storage` (`storage_id`, `drug_id`, `pihao`, `pizhunwenhao`, `storage_amount`, `inprice`, `allprice`, `in_time`, `in_from`, `factory`, `producedate`, `usefuldate`, `in_by`, `remark`)
VALUES
	(1,1,'abc','Jiang030',10,40.20,402.00,0,'','',0,0,1,'无'),
	(2,2,'20150111','江0100',100,20.00,2000.00,16,'民大校医','中国',1,30,1,'进货'),
	(3,3,'1604003J','国药准字Z36021533',8,18.20,145.60,1466006400,'校医院来的','华润三九（南昌）药业有限公司',1459872000,1520265600,1,'本人操作'),
	(4,3,'1604003J','国药准字Z36021533',2,18.20,1.00,1466092800,'中南民族大学','华润三九（南昌）药业有限公司',1466006400,1529078400,1,'我'),
	(5,3,'1604003J','国药准字Z36021533',8,18.20,1.00,1466092800,'中南民族大学','华润三九（南昌）药业有限公司',1466006400,1529078400,1,'我'),
	(6,3,'1604003J','国药准字Z36021533',10,18.20,1.00,1466092800,'中南民族大学','华润三九（南昌）药业有限公司',1466092800,1529078400,1,'我'),
	(7,2,'1601003H','国药准字Z36221533',10,23.00,230.00,1466092800,'民大','',1466006400,1528732800,1,''),
	(8,2,'1601003H','国药准字Z36221533',1,23.00,23.00,1466092800,'民大','',1466006400,1528732800,1,''),
	(9,6,'8238898','WUS3222',3,3.20,9.60,1466352000,'民大','华润三九（南昌）药业有限公司',1464710400,1468944000,1,'0'),
	(10,1,'isjak','wijeofiwjfoij',40,5.00,200.00,1476201600,'n','kswia',1475251200,1477843200,1,'None'),
	(11,2,'isjak','wijeofiwjfoij',50,12.00,600.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None'),
	(12,3,'isjak','wijeofiwjfoij',50,14.50,725.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None'),
	(13,4,'isjak','wijeofiwjfoij',30,6.50,195.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None'),
	(14,5,'isjak','wijeofiwjfoij',30,6.50,195.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None'),
	(15,6,'isjak24578','PK2232131',30,7.90,237.00,1476201600,'n','SCUEC',1475251200,1477843200,1,'None');

/*!40000 ALTER TABLE `medic_storage` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medic_store
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medic_store`;

CREATE TABLE `medic_store` (
  `store_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `storename` varchar(50) NOT NULL DEFAULT '' COMMENT '药店名称',
  `telephone` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '手机号码',
  `address` varchar(50) NOT NULL DEFAULT '' COMMENT '药店地址',
  PRIMARY KEY (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medic_store` WRITE;
/*!40000 ALTER TABLE `medic_store` DISABLE KEYS */;

INSERT INTO `medic_store` (`store_id`, `storename`, `telephone`, `mobile`, `address`)
VALUES
	(1,'康和药房','027-67841001','13296687974','湖北省武汉市民族大道182号');

/*!40000 ALTER TABLE `medic_store` ENABLE KEYS */;
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
	(1,'201321092028','e10adc3949ba59abbe56e057f20f883e','刘经济',1476255447,1),
	(2,'201321092032','4297f44b13955235245b2497399d7a93','姚康华',0,0),
	(3,'201321092023','b59c67bf196a4758191e42f76670ceba','张吉龙',0,0);

/*!40000 ALTER TABLE `medic_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
