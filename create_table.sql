//会員情報テーブル

CREATE TABLE `m_customers` (
	customer_code                                                varchar(50),            -- 顧客コード(ログインID)
	pass                                                         varchar(50),            -- パスワード
	name                                                         varchar(20),            -- 顧客氏名
	address                                                      varchar(100),           -- 顧客住所
	tel                                                          varchar(20),            -- 顧客電話
	mail                                                         varchar(100),           -- 顧客アドレス
	del_flag                                                     integer,                -- 削除フラグ
	reg_date                                                     date,                   -- 登録日
	Primary Key (customer_code) 
)DEFAULT CHARACTER SET utf8;



//商品情報テーブル
CREATE TABLE `m_items` (
	item_code                                                    integer,                -- 商品コード
	item_name                                                    varchar(50),            -- 商品名
	price                                                        integer,                -- 税別単価
	category                                                     integer,                -- カテゴリ
	image                                                        varchar(200),           -- カテゴリ
	detail                                                       varchar(500),           -- 詳細説明
	del_flag                                                     integer,                -- 削除フラグ(1は表示しない)
	reg_date                                                     date,                   -- 登録日
	Primary Key (item_code) 
)DEFAULT CHARACTER SET utf8;


//購入履歴テーブル(注文ボタンクリックで「誰がいくら買ったか」追加)
CREATE TABLE `d_purchase` (
	order_id                                                     bigint auto_increment,                 -- 購入ID
	customer_code
決済時の住所・氏名なども購入履歴テーブルに保存                                                varchar(50),             -- 会員コード
	purchase_date                                                date,                   -- 購入日
	total_price                                                  integer,                 -- 決済時金額
	Primary Key (order_id) 
)DEFAULT CHARACTER SET utf8;


//購入履歴詳細テーブル(購入情報の内訳)
CREATE TABLE `d_purchase_detail` (
	detail_id                                                    bigint auto_increment,                 -- 購入詳細ID
	order_id                                                     bigint ,                 -- 購入ID

	item_code                                                    integer,                -- 商品コード
必要があれば、商品名を保存(商品名が購入後に変更になる場合もあるから)
	price                                                        integer,                 -- 決済時商品単価(税込み)
	num                                                        integer,                 -- 数量
	Primary Key (detail_id,order_id) 
)DEFAULT CHARACTER SET utf8;

