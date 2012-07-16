<?php

     /*データベースに接続する関数*/
function connectDb() {
mysql_connect('localhost', 'root', '1192911') or die('データベースに接続できません：'.mysql_error());
mysql_select_db('ec') or die('データベースに接続できません：'.mysql_error());
mysql_set_charset('utf8');
}



/*post変数の値を取得する関数*/
    function getpost($name, $default=null) {
        
        //値があるなら
        if (isset($_POST[$name])) {
            //その値を返す
            return $_POST[$name];
    }
        //ないなら「null」を返す
        return $default;
    }
    
    
        /*値をエスケープ処理する関数*/
    function h($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    
    
    //    SQL 文中で用いる文字列の特殊文字をエスケープする
  function re($string) {
return mysql_real_escape_string($string);
  }



/**
	 * d_purchase テーブルに挿入する関数
	 */
	function create_purchase($customer_code, $total_price )
	{
		          //購入テーブルにレコードを追加する
                $sql = sprintf("insert into d_purchase( customer_code, purchase_date, total_price)
                    values( '%s', now(), '%d' ) ",
                        //お客様コード
                 mysql_real_escape_string($customer_code),
                        $total_price
                );
	

	$res = mysql_query( $sql );
	
 if(!$res) {
$sql = "ROLLBACK" ;
mysql_query($sql) ;
echo "エラーが発生しました。管理者までお問い合わせ下さい。" ;

	}
        }  
        
        /**
	 * d_purchase_detail テーブルに挿入する関数
	 */
	function create_purchase_detail($order_id, $item_code, $price, $num )
	{
		    $sql = sprintf("insert into d_purchase_detail( order_id, item_code, price, num ) 
                    values( '%s', '%s','%d','%d' ) ",
                 mysql_real_escape_string($order_id),
                              mysql_real_escape_string($item_code),
                              mysql_real_escape_string($price),
                              mysql_real_escape_string($num)
                            );
	$res = mysql_query( $sql );
	
 if(!$res) {
$sql = "ROLLBACK" ;
mysql_query($sql) ;
echo "エラーが発生しました。管理者までお問い合わせ下さい。" ;

	}
        }
?>
