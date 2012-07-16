<?php
//カート画面はサイトにログイン前かログイン後かによって、
//表示が変わります。ログイン後の場合は、番号「3´」の画面のように、［注文確定］ボタンが表示されます。

include 'functions.php';
	session_start();
connectDb();
$is_login = '';
$item_name = '';
$cat_kan = '';
$cat_gen = '';
$cat_da = '';
$is_order_done = '';
  $total_price = '';

  
  
  
        
        
	//isset 命令はある変数が存在するかどうかを判定するために用いる。
	// if 文の中に !isset と記述する事で、変数が存在しない場合に if 文に入る。
  //複数の商品を保存するためのセッション
	if( !isset( $_SESSION["cart"] ) )
	{
		// array() 命令で、空の配列を作成する。
		$_SESSION["cart"] = array();
	}
     
         
	/**-----------------------------------------------------------
	 *
	 * getメッソドで取得する。
         * リクエスト cmd の中身が、「add_cart」であった場合の処理。
	 * 詳細画面で「カートにいれる」ボタンが押された時に処理を行う。
	 *
	 ------------------------------------------------------------*/
	if( !empty($_REQUEST["cmd"]) && $_REQUEST["cmd"] == "add_cart")
	{
            
            //追加する商品がない
		$is_already_exists  = 0;
                
                
                //商品数鵜を合計する処理
		for( $i = 0 ; $i < count( $_SESSION["cart"] ); $i++ )
		{
                    //セッション内の商品コードと受け取った商品コードが一致するなら
			if( $_SESSION["cart"][$i]["item_code"] == $_REQUEST["code"] )
			{
				// 追加する商品がカートに既に存在するならば、受け取った数量を合計していく
				$_SESSION["cart"][$i]["num"] = $_SESSION["cart"][$i]["num"] + $_REQUEST["num"];
                                
                                //追加する商品がある
				$is_already_exists = 1;
			}
		}
                
                
		// 追加する商品がカートに存在しない場合、カートページに新規登録。
		if( $is_already_exists == 0 )
		{
                    
                    //商品コードに一致する商品データを取得する
                     $sql = sprintf("select * from m_items where item_code = '%s'", 
            mysql_real_escape_string($_REQUEST["code"])
            );

		$res = mysql_query( $sql );
		
                //もし、そのレコードがあれば
			if( $record = mysql_fetch_array( $res ) ) 
			{
                            //受け取った商品コード
				$item["item_code"] = $_REQUEST["code"];
                                //受け取った数量
				$item["num"] = $_REQUEST["num"];
                                
                                //レコード内のイメージ情報を取得
				$item["image"] = $record["image"];
				$item["item_name"] = $record["item_name"];
                                
                                //レコード内の金額
				$item["price"] = $record["price"];
                                
                                
//                                array_push( 配列名, 配列に追加したい変数名);
//                                $itemの中身を$_SESSION["cart"]に要素を追加
				array_push( $_SESSION["cart"], $item );
			}
			
		}
	}

        
	/**-----------------------------------------------------------
	 *
	 * リクエスト cmd の中身が、「del」であった場合の処理。
	 * カート画面で「削除」ボタンが押された時に処理を行う。
	 *
	 ------------------------------------------------------------*/
	if( !empty($_REQUEST["cmd"]) && $_REQUEST["cmd"] == "del")
            
	{
            //カート内の商品数だけループしながら、削除リンクがクリックされた商品を消していく
		for( $i = 0 ; $i < count( $_SESSION["cart"] ); $i++ )
		{
                    //セッション内の商品コードと受け取った商品コードが一致するなら
			if( $_SESSION["cart"][$i]["item_code"] == $_REQUEST["code"] )
			{
				// unset 命令は、変数を破棄する。
				unset( $_SESSION["cart"][$i] );
			}
		}
		// 削除すると配列の番号が歯抜けになるため、以下の処理で配列の番号を整理し直す。
		$_SESSION["cart"] = array_merge($_SESSION["cart"]);
	}

        
        
	/**-----------------------------------------------------------
	 *
	 * リクエスト cmd の中身が、「commit_order」であった場合の処理。
	 * カート画面で「注文確定」ボタンが押された時に処理を行う。
	 * 注文確定ボタンはログイン済の時のみ、表示される。
	 *
	 ------------------------------------------------------------*/
	if( !empty($_REQUEST["cmd"]) && $_REQUEST["cmd"] == "commit_order" )
          
            
	{
            
            //オートコミットをオフに設定
$sql = "SET AUTOCOMMIT=0" ;
mysql_query($sql) ;

//トランザクション開始
$sql = "BEGIN" ;
mysql_query($sql) ;

		// カート内の合計金額を計算する。
		foreach( $_SESSION["cart"] as $cart )
		{
                    //$total_price = $total_price + $cart["price"] * $cart["num"];
			$total_price += $cart["price"] * $cart["num"];
		}

                // d_purchase テーブルへの挿入
		create_purchase($_SESSION["customer_code"], $total_price );
                
      

		// d_purchase テーブルに挿入した ID を取得し、購入IDにする。
		$order_id = mysql_insert_id() ;

		// $_SESSION["cart"] をもう一度ループし、ループ内で詳細情報を取得して
		// から 購入詳細テーブル に でーたを追加する。
		foreach( $_SESSION["cart"] as $cart )
		{
                    
                    	create_purchase_detail( 
				$order_id, $cart["item_code"], $cart["price"], $cart["num"] );
		}
                 
		
		
                
                //カート内の商品情報を削除する
		unset( $_SESSION["cart"] );
		// $is_order_done 変数は、画面上に「注文が完了しました」
		// メッセージを表示するために使用する。
		$is_order_done = 1;
                
                $sql = "COMMIT" ;
mysql_query($sql) ;
	}
        
        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>カートの中｜楽器の通販サイト  oh yeah !!</title>
<link href="common/css/base.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="common/js/base.js"></script>
</head>
<body onload="MM_preloadImages('common/img/bt1_f2.gif','common/img/bt2_f2.gif','common/img/bt3_f2.gif','common/img/bt3_2_f2.gif','common/img/bt_login_f2.gif')">
<div id="wrap">
  <div id="contents">
    <!-- 右コンテンツ -->
    <div id="rightbox">
      <div id="main">
        <div id="main2">
          <!-- ↑↑タイトル以外共通部分↑↑ -->

          <!-- メイン部分 各ページごとに作成-->
          <div id="mainbox" class="clearfix">
            <h2>カートの中</h2>
<?php
//商品情報をテーブルに追加したなら
	if( $is_order_done == 1 )
	{
?>
            <br>
　　注文が完了しました。<a href="item_list.php">商品一覧へ戻る</a>
            <br>
<?php
	}
?>
            <div class="list clearfix">
              <table class="cartlist" cellpadding="0" cellspacing="0">
<?php


	// $_SESSION["cart"] をループし、カートの商品を表示していく。
	if( isset ( $_SESSION["cart"] ) )
	{
            
            //カート内の商品を表示していく
		foreach( $_SESSION["cart"] as $cart )
		{
?>
                <tr>
                  <td class="tc1"><img src="img/thumb2/<?php print( $cart["image"] ); ?>"></td>
                  <td class="tc2"><?php print( $cart["item_name"] ); ?>(<?php print( $cart["num"] ); ?>個)</td>
                  <td class="tc3">&yen;<?php print( $cart["price"] ); ?></td>
                  <td class="tc4"><a href="item_detail.php?code=<?php print( $cart["item_code"] ); ?>">詳細へ</a></td>
<!--                  削除コード-->
                  <td class="tc5"><a href="cart.php?cmd=del&code=<?php print( $cart["item_code"] ); ?>">削除</a></td>
                </tr>
<?php
		}
	}
?>
              </table>
              <br>
<?php
//お客様コードとカート情報とカート内にデータがあれば「注文確定」を設置
	if( isset($_SESSION["customer_code"]) && isset($_SESSION["cart"]) && count( $_SESSION["cart"] ) > 0 )
	{
?>
              <form name="cart_form" action="cart.php" method="post">
              <input type="hidden" name="cmd" value="commit_order"/>
              <input type="submit" class="fix" value="注文確定"/>
              </form>
<?php
	}
?>
            </div>
          </div>
          <!-- /メイン部分 各ページごとに作成-->

          <!-- ↓↓共通部分↓↓ -->
          <!-- フッター -->
          <div id="footer">
            <p class="copy">Copyright &copy; 2008 oh yeah !! All Rights Reserved.</p>
          </div>
          <!-- /フッター -->
        </div>
        <!-- /メイン部分 -->
      </div>
    </div>
    <!-- 右コンテンツ -->
<?php
	require_once("./left_pane.php");
?>
  </div>
</div>
</body>
</html>
