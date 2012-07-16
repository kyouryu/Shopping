-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2012 年 7 月 17 日 07:03
-- サーバのバージョン: 5.5.9
-- PHP のバージョン: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `ec`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `d_purchase`
--

CREATE TABLE `d_purchase` (
  `order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(50) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- テーブルのデータをダンプしています `d_purchase`
--

INSERT INTO `d_purchase` VALUES(1, 'ooie', '2012-07-09', 715000);

-- --------------------------------------------------------

--
-- テーブルの構造 `d_purchase_detail`
--

CREATE TABLE `d_purchase_detail` (
  `detail_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL DEFAULT '0',
  `item_code` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  PRIMARY KEY (`detail_id`,`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- テーブルのデータをダンプしています `d_purchase_detail`
--

INSERT INTO `d_purchase_detail` VALUES(1, 1, 2001, 300000, 1);
INSERT INTO `d_purchase_detail` VALUES(2, 1, 3002, 215000, 1);
INSERT INTO `d_purchase_detail` VALUES(3, 1, 3001, 100000, 2);

-- --------------------------------------------------------

--
-- テーブルの構造 `m_customers`
--

CREATE TABLE `m_customers` (
  `customer_code` varchar(50) NOT NULL DEFAULT '',
  `pass` varchar(50) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `del_flag` int(11) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  PRIMARY KEY (`customer_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `m_customers`
--

INSERT INTO `m_customers` VALUES('ooie', 'ooie', '坂口', '', '', '', 0, '2012-07-09');
INSERT INTO `m_customers` VALUES('ooyamada', 'ooyamada', '大山田徹', '東京都豊島区1-2-3', '03-222-3333', 'ooyamada@example.com', 0, '2008-10-18');

-- --------------------------------------------------------

--
-- テーブルの構造 `m_items`
--

CREATE TABLE `m_items` (
  `item_code` int(11) NOT NULL DEFAULT '0',
  `item_name` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `detail` varchar(500) DEFAULT NULL,
  `del_flag` int(11) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  PRIMARY KEY (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `m_items`
--

INSERT INTO `m_items` VALUES(1001, 'YAMAHAトランペット', 200000, 1, 'EG024.jpg', '音色が明るく、芯のある音がでます。', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(1002, 'SELMERアルトサックス', 150000, 1, 'EG028.jpg', 'なめらかでしっとりした音色を好む方にぴったりです。', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(1003, 'YAMAHAトロンボーン', 132000, 1, 'EG026.jpg', '初心者に優しいトロンボーンです！', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(1004, 'リコーダー', 35000, 1, 'EG040.jpg', '童心にかえってドナドナを吹いてみませんか？', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(2001, 'オリエンテ製コントラバス', 300000, 2, 'EG007.jpg', '日本製ですので、湿気に強くメンテナンスが楽です。', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(2002, '鈴木バイオリン製チェロ', 735000, 2, 'EG001.jpg', '高いだけあっていい音がでますよ！！', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(2003, 'Ibanezベース', 735000, 2, 'EG017.jpg', 'スタジオミュージシャンの中でも愛好家が多いベースです。', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(2004, 'Gibsonレスポール', 350000, 2, 'EG016.jpg', 'この音の太さ！ロック系にはたまりません！', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(2005, 'Ibanezギター', 150000, 2, 'EG013.jpg', '初心者から利用可能な、幅広く使えるギターです。', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(3001, 'TAMAドラムセット', 100000, 3, 'EG048.jpg', '一般的に聴きなじみのあるサウンドです。よく鳴ります。', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(3002, 'キューバ製コンガ', 215000, 3, 'EG050.jpg', 'ラテンの血が騒ぎます！', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(3003, 'タンバリン皮付き', 8000, 3, 'EG053.jpg', 'ポーンとよく飛ぶ音です。', 0, '2008-10-18');
INSERT INTO `m_items` VALUES(3004, 'タンバリン皮無し', 10000, 3, 'EG054.jpg', 'ノスタルジックな音がでます。', 0, '2008-10-18');
