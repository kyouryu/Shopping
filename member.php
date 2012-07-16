<?php
//会員登録画面には、各画面の左メニューの［会員登録］ボタンから遷移します。
include 'functions.php';
	session_start();
connectDb();

$is_login = '';
$item_name = '';
$cat_kan = '';
$cat_gen = '';
$cat_da = '';
$is_success = '';

	/**-----------------------------------------------------------
	 *
	 * 会員登録画面で「更新」ボタンがクリックされた時の処理。
	 * ログイン状態に応じて、UPDATE または INSERT を実行する。
	 *
	 ------------------------------------------------------------*/
	if( !empty($_POST["cmd"]) && $_POST["cmd"] == "regist_member" )
	{
            //セッション情報があれば
		if( isset($_SESSION["customer_code"]))
		{
                    
                    //レコードを更新する
                       $sql = sprintf("UPDATE m_customers SET customer_code = '%s',
                           pass = '%s',
                           name = '%s', 
                           address = '%s',
                           tel = '%s',
                           mail = '%s'
                           WHERE customer_code = '%s'", 
            mysql_real_escape_string($_POST["customer_code"]),
                               mysql_real_escape_string($_POST["pass"]),
                                   mysql_real_escape_string($_POST["name"]),
                                   mysql_real_escape_string($_POST["address"]),
                                   mysql_real_escape_string($_POST["tel"]),
                                   mysql_real_escape_string($_POST["mail"]),
                                 mysql_real_escape_string($_SESSION["customer_code"])
                               
            );
                     
$res = mysql_query( $sql );

//登録成功
$is_success = 1;

		}
                //ログインしていないなら
		else
		{
                    
                    //レコードを追加する
                    $sql = sprintf("insert into m_customers( customer_code, pass, name, address, tel, mail, del_flag, reg_date ) 
                        values ('%s','%s','%s','%s','%s','%s','%s',now())", 
            mysql_real_escape_string($_POST["customer_code"]),
                               mysql_real_escape_string($_POST["pass"]),
                                   mysql_real_escape_string($_POST["name"]),
                                   mysql_real_escape_string($_POST["address"]),
                                   mysql_real_escape_string($_POST["tel"]),
                                   mysql_real_escape_string($_POST["mail"]),
                                 0
                        
            );
     
        $res = mysql_query( $sql );
		}
	}

        
	// ログイン済であれば、お客様の情報をデータベースより取得。
	if(isset($_SESSION["customer_code"]))
	{
		$sql = " select * from m_customers ";
		$sql.= " where customer_code= '" . $_SESSION["customer_code"] . "'";
		$res = mysql_query( $sql );
		$count = 0;
		$info = mysql_fetch_array( $res ) ;
               
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>登録情報｜楽器の通販サイト  oh yeah !!</title>
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
            <h2>登録情報</h2>

<?php
//ログインが成功したら
	if( $is_success == 1 )
	{
?>
			<br><p align="center">正常に処理が完了しました。</p>
<?php
	}
?>

            <form name="member_form" action="member.php" method="post">
            <input type="hidden" name="cmd" value="regist_member"/>
            <div class="info clearfix">
            <dl>
            <dt>ID：</dt>
            <dd><input type="text" name="customer_code" <?php if( !empty($info["customer_code"]) ){ print( "readonly" ); } ?> 
                       value="<?php  
//                       会員情報が存在するなら、代入していく
                       if(!empty($info["customer_code"])) {
                print( h( $info["customer_code"], ENT_QUOTES ) );
            }?>"/></dd>
            
            <dt>パスワード：</dt>
            <dd>
                <input type="password" name="pass" value="<?php 
            if(!empty($info["pass"])) {
            print( h( $info["pass"] , ENT_QUOTES) ); 
            }
            ?>"/></dd>
            
            <dt>氏名：</dt>
            <dd><input type="text" name="name" value="<?php 
           if(!empty($info["name"])){
                print( h( $info["name"], ENT_QUOTES ) ); 
            } 
            ?>"/></dd>
            
            <dt>住所：</dt>
            <dd><input type="text" name="address" value="<?php 
           if(!empty($info["address"])){
                print( h( $info["address"], ENT_QUOTES ) ); 
            } 
            ?>"/></dd>
            
            <dt>電話：</dt>
            <dd><input type="text" name="tel" value="<?php 
         if(!empty($info["tel"])){
                print( h( $info["tel"], ENT_QUOTES ) ); 
            } 
            ?>"/></dd>
            
            <dt>アドレス：</dt>
            <dd><input type="text" name="mail" value="<?php 
          if(!empty($info["mail"])){
                print( h( $info["mail"], ENT_QUOTES ) ); 
            } 
            ?>" size="30"/>
            </dd>
            
            </dl>
            <input type="submit" class="update" value="登録"/>
            </div>
            </form>
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
<?php
	// left_pane.php の読み込み
	require_once("./left_pane.php");
?>
  </div>
</div>
</body>
</html>
