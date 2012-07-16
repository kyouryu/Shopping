<?php
//商品を一覧で検索する画面

include 'functions.php';
	session_start();
connectDb();
$is_login = '';
$item_name = '';
$cat_kan = '';
$cat_gen = '';
$cat_da = '';



	/**-----------------------------------------------------------
	 *
	 * 【ログイン処理(1)】
	 * 画面左側の「ログイン」ボタンが押された時にこの if 文に入ります。
	 *
	 ------------------------------------------------------------*/
	if( !empty($_POST["cmd"]) && $_POST["cmd"] == "do_login" )
	{
		// ログインidとパスワードに一致するレコードがあれば
            $sql = sprintf("select * from m_customers  WHERE customer_code='%s' AND pass='%s'", 
            mysql_real_escape_string($_POST["login_id"]),mysql_real_escape_string($_POST["login_pass"])
            );

		$res = mysql_query( $sql );
                //ログインでないなら０
		$is_login = 0;
                
		// 検索結果が取れた場合(つまり、ログインに成功した場合)以下の if 文に入る。
		if( $row = mysql_fetch_array( $res ) ) 
		{
                    //ログインidと名前のセッションを作成
			$_SESSION["customer_code"] = $_POST["login_id"];
			$_SESSION["name"] = $row["name"];
                        //ログイン中ですなら１
			$is_login = 1;
		}
		mysql_free_result($res);
	}


       
	/**-----------------------------------------------------------
	 *
	 * 【ログイン処理(2)】
	 * ログイン後に、画面左側の「ログアウト」ボタンが押された時に
	 * この if 文に入ります。unset 命令は変数の中身を破棄する命令です。
	 *
	 ------------------------------------------------------------*/

    //ログアウトぼたんが押されたら
	if(!empty($_POST["cmd"]) && $_POST["cmd"] == "do_logout" )
	{
            //セッションを削除
            $_SESSION = array();
		if ( isset( $_COOKIE[ session_name( ) ] ) ) 
		{
		    setcookie( session_name(), "", time( ) - 42000, "/");
		}
		@session_destroy();
	}


        
//商品を取得するsql文
$sql = "select * from m_items where del_flag = '0'";




//検索フィールドの商品名が空でないなら
if( !empty($_POST["item_name"]))
{
    
$item_name = $_POST["item_name"];

    //商品名で検索されたときにsql文を足す
$sql = $sql . " AND item_name LIKE '%" . re($_POST["item_name"]) . "%' ";
}


// もしも「管楽器」「弦楽器」「打楽器」のいずれかのチェックボックスに
	// チェックが入っていた場合、以下の if 文に入ります。
if(isset($_POST['cat_kan']) || isset($_POST['cat_gen']) || isset($_POST['cat_da'])) {

    
    
		$in = "";
                //チェック(１)が入っていたら
		if( isset($_POST['cat_kan']) && $_POST['cat_kan'] == "1" )
		{
                    //１を代入
                    $cat_kan = $_POST['cat_kan'];
                    //または
			$in = "1,";
		}
                
		if( isset($_POST['cat_gen']) && $_POST['cat_gen'] == "1" )
		{
                    $cat_gen = $_POST['cat_gen'];
			$in = $in . "2,";
		}
                
		if(  isset($_POST['cat_da']) && $_POST['cat_da'] == "1" )
		{
                    $cat_da = $_POST['cat_da'];
			$in = $in . "3,";
		}
               
//                $inの文字列の最後にある「,」を削除する
		$in = preg_replace( "/,$/", "", $in );
                
                //sql文を足していく
		$sql = $sql . " AND category IN ( $in ) ";
	
}

//商品名、カテゴリの両方で絞込みを行う場合
//SELECT * FROM m_items WHERE del_flag = '0' AND item_name LIKE '%YAMAHA%' AND category IN ('1', '3');
        $res = mysql_query($sql);

$items = array();

//レコードを一件ずつ入れる
while($item = mysql_fetch_array($res)) {
    $items[] = $item; 
}

//結果保持用メモリを開放する
mysql_free_result($res);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>商品一覧｜楽器の通販サイト  oh yeah !!</title>
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
            <h2>商品一覧</h2>
            <!-- 商品リスト -->
            <div class="list clearfix">
                <?php 
                
                //アイテムを一件ずつひょうじしていく
                foreach ($items as $item) :
                    ?>

               <dl class="products">
                   
                     <dt>
<!--                         詳細画面へ飛ぶ際にitem_codeを付与する-->
                         <a href="item_detail.php?code=<?php print( h( $item["item_code"] ) ); ?>">
                             <img src="img/thumb/<?php print( h( $item["image"]) ); ?>" alt="" /><br />
                <?php print( h( $item["item_name"] ) ); ?></a></dt>
                <dd>&yen;<?php print( h( $item["price"] ) ); ?></dd>
              </dl>
                <?php
                   endforeach;
                   ?>
            </div>
            <!-- /商品リスト -->
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
	// database.php の読み込み
	require_once("./left_pane.php");
?>
  </div>
</div>
</body>
</html>
