<?php
//商品詳細画面では、商品の拡大画像、詳細説明、価格などの詳しい情報を閲覧できます。

include 'functions.php';
	session_start();
connectDb();
$is_login = '';
$item_name = '';
$cat_kan = '';
$cat_gen = '';
$cat_da = '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>商品詳細｜楽器の通販サイト  oh yeah !!</title>
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
<?php

	// 以下の $sql 変数に適切な SELECT 文を記述し、
	// 一覧画面から画面遷移できるようにして下さい。
//item_list.phpから取得したitem_codeを元にアイテム情報を取得する
	$sql = sprintf("select * from m_items where item_code = '%s' ",
                 re($_REQUEST["code"])
                );
	

	$res = mysql_query( $sql );
        
              
		// 検索結果に一致するアイテムがあったなら
		if( $item = mysql_fetch_array( $res ) ) {
?>
          
<!--          cart.phpへとばす-->
          <form name="detail_form" action="cart.php" method="get">
          <input type="hidden" name="cmd" value="add_cart"/>
          
<!--          商品コードを設定-->
          <input type="hidden" name="code" value="<?php print( h( $item["item_code"] ) ); ?>"/>
          <!-- メイン部分 各ページごとに作成-->
          <div id="mainbox" class="clearfix">
            <h2>商品詳細</h2>
            <div class="list clearfix">
              <h3><?php print( h( $item["item_name"] ) ); ?></h3>
              <p class="photo"><img src="img/<?php print( h( $item["image"] ) ); ?>" width="400" height="400"/></p>
                <p class="text"><?php print( h( $item["detail"] ) ); ?></p>
              <div class="buy">
                <p class="price">価格：<strong>&yen;<?php print( h( $item["price"] ) ); ?></strong></p>
                個数：
                <select name="num">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>
                <input type="submit" value="カートにいれる"/>
                <input type="button" value="前の画面へ戻る" onclick="history.back()"/>
              </div>
            </div>
          </div>
          </form>
          <!-- /メイン部分 各ページごとに作成-->
<?php
	}
	
?>
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
