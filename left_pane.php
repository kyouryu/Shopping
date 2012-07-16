 <!-- 左メニュー -->
    <div id="leftbox">
      <h1><img src="common/img/title.gif" alt="oh yeah!!" /></h1>
      <div id="menu">

<?php
	// ログインしていない時は、以下の if 文に入ります。
	if( !isset($_SESSION["customer_code"])):
	
?>
          
    <!--        item_list.phpへとばす。ログイン処理-->
        <form name="login_form" action="item_list.php" method="post">
        <input type="hidden" name="cmd" value="do_login"/>
        <div class="box">
          <div class="top"><img src="common/img/t1.gif" alt="ログイン" /></div>
          <dl class="clearfix">
              
<?php
		// ログインに失敗した時のエラー表示。
if(!empty($_POST["cmd"])) {
    
    //$is_loginが０であり、ログインボタンが押されたら
		if( $is_login == 0 and $_POST["cmd"] == "do_login" )
		{
			print("ログインに失敗しました。");
		}
}
?>
              
            <dt><img src="common/img/t4.gif" alt="ID" /></dt>
            <dd>
              <input name="login_id" type="text" class="text" />
            </dd>
            <dt><img src="common/img/t5.gif" alt="PASS" /></dt>
            <dd>
              <input name="login_pass" type="password" class="text" />
            </dd>
          </dl>
          <div class="bottom">
            <input name="id3" type="submit" value="ログイン" />
          </div>
        </div>
        </form>
<?php 
	// ログイン済の時は、ログアウトボタンを設置
	else:
?>
        
<!--        item_list.phpへとばす。ログアウト処理-->
        <form name="login_form" action="item_list.php" method="post">
        <input type="hidden" name="cmd" value="do_logout"/>

        <!-- /ログインフォーム -->
        <!-- ウェルカム（ログイン時） -->
        <div class="box">
          <div class="top">ようこそ<span class="person"><?php print($_SESSION["name"])?></span>さん！</div>
          <div class="bottom">
            <input name="id3" type="submit" value="ログアウト" />
          </div>
        </div>
        </form>
<?php
	endif;
?>
        
        <!-- /ウェルカム -->
        <!-- 商品検索 --> 
         <form name="login_form" action="item_list.php" method="post">
        <input type="hidden" name="cmd" value="do_search"/>

        <div class="box" id="search">
          <div class="top"><img src="common/img/t2.gif" alt="商品検索" /></div>
          <dl class="clearfix">
            <dt><img src="common/img/t6.gif" alt="商品名" width="32" height="18" /></dt>
            <dd>
              <input type="text" name="item_name" class="text" value="<?php print($item_name )?>"/>
            </dd>
          </dl>
          <dl class="clearfix cat">
            <dt><img src="common/img/t7.gif" alt="カテゴリ" /></dt>
            <dd>
<!--                値が1ならチェック状態を維持する-->
              <input type="checkbox" name="cat_kan" value="1" <?php if( $cat_kan == "1" ){ print("checked"); } ?>/>
              管楽器<br />
              <input type="checkbox" name="cat_gen" value="1" <?php if( $cat_gen == "1" ){ print("checked"); } ?>/>
              弦楽器<br />
              <input type="checkbox" name="cat_da" value="1" <?php if( $cat_da == "1" ){ print("checked"); } ?>/>
              打楽器 </dd>
          </dl>
          <div class="bottom">
            <input name="id3" type="submit" value="検索" />
          </div>
        </div>
        </form>

        <!-- 商品検索 -->
        
          <!-- 共通メニュー -->
        <ul class="menu">
          <li><a href="item_list.php"><img src="common/img/bt1.gif" alt="商品一覧" name="Image1" width="172" height="38" id="Image1" onmouseover="MM_swapImage('Image1','','common/img/bt1_f2.gif',1)" onmouseout="MM_swapImgRestore()" /></a></li>
          <li><a href="cart.php"><img src="common/img/bt2.gif" alt="カートの中" name="Image2" width="172" height="38" id="Image2" onmouseover="MM_swapImage('Image2','','common/img/bt2_f2.gif',1)" onmouseout="MM_swapImgRestore()" /></a></li>
<?php
	// ログイン未の場合
	if( !isset($_SESSION["customer_code"])):
	
?>
<!--          会員登録ボタンを表示-->
          <li><a href="member.php"><img src="common/img/bt3_2.gif" alt="会員登録" name="Image4" width="172" height="38" id="Image4" onmouseover="MM_swapImage('Image4','','common/img/bt3_2_f2.gif',1)" onmouseout="MM_swapImgRestore()" /></a></li>
<?php
	// そうでないなら会員情報ボタンを表示
	else:
?>
          <li><a href="member.php"><img src="common/img/bt3.gif" alt="登録情報" name="Image3" width="172" height="38" id="Image3" onmouseover="MM_swapImage('Image3','','common/img/bt3_f2.gif',1)" onmouseout="MM_swapImgRestore()" /></a></li>
<?php
	endif;
?>
        </ul>
        <!-- /共通メニュー -->
      </div>
    </div>