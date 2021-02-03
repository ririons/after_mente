<?php
// 共通変数・関数ファイルを読み込み
  require('function.php');

  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debug('「ログインページ」');
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debugLogStart();

  //ログイン認証
  require('auth.php');
  //=================================
  // ログイン画面処理
  //=================================
  //post送信されていた場合
    if(!empty($_POST)){
      debug('POST送信があります。');

      //変数にユーザ情報を代入
      $email = $_POST['email'];
      $pass_save = (!empty($_POST['pass_save'])) ? true : false;
      // ショートハンド（略記法）という書き方

      //emailの形式チェック
      validEmail($email, 'email');
      //未入力チェック
      validRequired($email, 'email');
      

      if(empty($err_msg)){

          debug('バリデーションOKです。');

          //例外処理
          try{
            // DB接続
            $dbh = dbConnect();
            // SQL文作成
            $sql = 'SELECT email,id FROM users WHERE email = :email';
            $data = array(':email' => $email);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            // クエリの結果の値を取得
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            debug('クエリ結果の中身:'.print_r($result,true));

            //ログイン有効期限（デフォルトを1時間とする）
            $sesLimit = 60*60;
            // 最終ログイン日時を現在日時に
            $_SESSION['login_date'] = time();
            
            //ログイン保持にチェックがある場合
            if($pass_save){
              debug('ログイン保持にチェックがあります。');
              // ログイン有効期限を30日にしてセット
              $_SESSION['login_limit'] = $sesLimit * 24 * 30;
            }else {
              debug('ログイン保持にチェックはありません');
              //次回からログイン保持しないので、ログイン有効期限を1時間後にセット
              $_SESSION['login_limit'] = $sesLimit;
            }

            //ユーザーIDを格納
            $_SESSION['user_id'] = $result['id'];

            debug('セッション変数の中身:'.print_r($_SESSION,true));
            debug('トップページへ遷移します。');
            debug("Location:index.php");
            // トップページへ

          }catch (Exception  $e){
            error_log('エラー発生'. $e->getMessage());
            $err_msg['common'] = MSG07;
          }
        }
    }

debug('画面表示処理終了<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
  ');
  ?>
<?php
$siteTitle = 'ログイン';
require('head.php');
?>

<body class="page-login page-1colum">

<!--    ヘッダー    -->
<?php 
  require('header.php');
?>


<!--   メインコンテンツ     -->
<div id="contents" class="site-width">
  <!--   Main     -->
    <section id="main">
      <div class="form-container">
        <form action="" method="post" class="form">
          <h2 class="title">ログイン</h2>
          <div class="area-msg">
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>

          </div>
          <label class="<?php if(!empty($err_msg['email']))echo 'err';?>">
            メールアドレス
            <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>">
          </label>
          <div class="area-msg">
            <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
          </div>
          <label for="">
            <input type="checkbox" name="pass_save">次回ログインを省略する
          </label>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="ログイン">
          </div>
        </form>
      </div>
    </section>
</div>

<!--   footer     -->
<?php 

    require('footer.php');
?>
