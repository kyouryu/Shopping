<?php
	require_once("include/database.php");
	session_start( );

	/**
	 * d_purchase テーブルに挿入する関数
	 */
	function create_purchase( $mdb2, $customer_code, $total_price )
	{
		// d_purchase テーブルへの挿入
		$sql = " insert into d_purchase( customer_code, purchase_date, total_price) ";
		$sql.= " values( ?, now(), ? ) ";
		$stmt = $mdb2->prepare( $sql );
		// エラー処理
		if ( PEAR::isError( $stmt ) )
		{
			$mdb2->rollback();
			die("エラーが発生しました。管理者までお問い合わせ下さい。");
		}
		$res = $stmt->execute(
			array(
				$customer_code,
				$total_price
			)
		);
		// エラー処理
		if ( PEAR::isError( $res ) )
		{
			$mdb2->rollback();
			die("エラーが発生しました。管理者までお問い合わせ下さい。");
		}
	}

	/**
	 * d_purchase_detail テーブルに挿入する関数
	 */
	function create_purchase_detail( $mdb2, $order_id, $item_code, $price, $num )
	{
		$sql = " insert into d_purchase_detail( order_id, item_code, price, num ) ";
		$sql.= " values( ?, ?, ?, ? ) " ;
		$stmt = $mdb2->prepare( $sql );
		// エラー処理
		if ( PEAR::isError( $stmt ) )
		{
			$mdb2->rollback();
			die("エラーが発生しました。管理者までお問い合わせ下さい。");
		}
		$res = $stmt->execute(
			array(
				$order_id,
				$item_code,
				$price,
				$num
			)
		);
		// エラー処理
		if ( PEAR::isError( $res ) )
		{
			$mdb2->rollback();
			die("エラーが発生しました。管理者までお問い合わせ下さい。");
		}
	}

	//isset 命令はある変数が存在するかどうかを判定するために用いる。
	// if 文の中に !isset と記述する事で、変数が存在しない場合に if 文に入る。
	if( !isset( $_SESSION["cart"] ) )
	{
		// array() 命令で、空の配列を作成する。
		$_SESSION["cart"] = array();
	}

	/**-----------------------------------------------------------
	 *
	 * リクエスト cmd の中身が、「add_cart」であった場合の処理。
	 * 詳細画面で「カートにいれる」ボタンが押された時に処理を行う。
	 *
	 ------------------------------------------------------------*/
	if( $_REQUEST["cmd"] == "add_cart")
	{
		$is_already_exists  = 0;
		for( $i = 0 ; $i < count( $_SESSION["cart"] ); $i++ )
		{
			if( $_SESSION["cart"][$i]["item_code"] == $_REQUEST["code"] )
			{
				// 追加する商品がカートに既に存在するならば、数量を合算。
				$_SESSION["cart"][$i]["num"] = $_SESSION["cart"][$i]["num"] + $_REQUEST["num"];
				$is_already_exists = 1;
			}
		}
		// 追加する商品がカートに存在しない場合、カートに新規登録。
		if( $is_already_exists == 0 )
		{
			$sql = "select * from m_items where item_code = ? ";
			$stmt = $mdb2->prepare( $sql );
			$res = $stmt->execute(
				array( $_REQUEST["code"] )
			);
			if( $record = $res->fetchRow( MDB2_FETCHMODE_ASSOC ) ) 
			{
				$item["item_code"] = $_REQUEST["code"];
				$item["num"] = $_REQUEST["num"];
				$item["image"] = $record["image"];
				$item["item_name"] = $record["item_name"];
				$item["price"] = $record["price"];
				array_push( $_SESSION["cart"], $item );
			}
			$res->free();
		}
	}

	/**-----------------------------------------------------------
	 *
	 * リクエスト cmd の中身が、「del」であった場合の処理。
	 * カート画面で「削除」ボタンが押された時に処理を行う。
	 *
	 ------------------------------------------------------------*/
	if( $_REQUEST["cmd"] == "del")
	{
		for( $i = 0 ; $i < count( $_SESSION["cart"] ); $i++ )
		{
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
	if( $_REQUEST["cmd"] == "commit_order" )
	{
		// トランザクション開始
		$mdb2->beginTransaction();

		// カート内の合計金額を計算する。
		foreach( $_SESSION["cart"] as $cart )
		{
			$total_price += $cart["price"] * $cart["num"];
		}
		// d_purchase テーブルへの挿入
		create_purchase( $mdb2, $_SESSION["customer_code"], $total_price );

		// d_purchase テーブルに挿入した ID を取得。
		$order_id = $mdb2->lastinsertid("d_purchase","order_id");

		// $_SESSION["cart"] をもう一度ループし、ループ内で詳細情報を取得して
		// から d_purchse_detail に insert する。
		foreach( $_SESSION["cart"] as $cart )
		{
			create_purchase_detail( 
				$mdb2, $order_id, $cart["item_code"], $cart["price"], $cart["num"] );
		}
		unset( $_SESSION["cart"] );
		// $is_order_done 変数は、画面上に「注文が完了しました」
		// メッセージを表示するために使用する。
		$is_order_done = 1;

		// トランザクションのコミット
		$mdb2->commit();
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
	// $_SESSION["cart"] をループし、カートの商品を表示する。
	if( isset ( $_SESSION["cart"] ) )
	{
		foreach( $_SESSION["cart"] as $cart )
		{
?>
                <tr>
                  <td class="tc1"><img src="img/thumb2/<?php print( $cart["image"] ); ?>"></td>
                  <td class="tc2"><?php print( $cart["item_name"] ); ?>(<?php print( $cart["num"] ); ?>個)</td>
                  <td class="tc3">&yen;<?php print( $cart["price"] ); ?></td>
                  <td class="tc4"><a href="item_detail.php?code=<?php print( $cart["item_code"] ); ?>">詳細へ</a></td>
                  <td class="tc5"><a href="cart.php?cmd=del&code=<?php print( $cart["item_code"] ); ?>">削除</a></td>
                </tr>
<?php
		}
	}
?>
              </table>
              <br>
<?php
	if( $_SESSION["customer_code"] != "" && count( $_SESSION["cart"] ) > 0 )
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
	require_once("include/left_pane.php");
?>
  </div>
</div>
</body>
</html>
