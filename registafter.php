<?php

// 共通変数・ファイルを読み込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　アフター新規登録　」');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// ログイン認証
require('auth.php');

//=========================
// 画面処理
//=========================

// 画面表示用データ取得
//=========================
// GETデータを格納
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
// DBからアフターデータを取得
$dbFormData = (!empty($p_id)) ? getProduct($_SESSION['user_id'], $p_id) : '';



// パラメータ改ざんチェック
// =============================
// GETパラメータはあるが、改ざんされている(URLが違う)場合、正しい商品データを取れないので、トップへ遷移させる
if(!empty($p_id) && empty($dbFormData)){
  debug('GETパラメータの商品IDが違います。トップページへ遷移します。');
  header('Location:index.php');
  // トップページ
}

// POST送信時処理
// =============================
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST:'.print_r($_POST,true));
  debug('FILE情報:'.print_r($_FILES,true));

  // 変数にユーザ情報を代入
  // 項目名
  $name = $_POST['name'];
  // 詳細
  $content = $_POST['content'];
  // 対応内容
  $processing_content = $_POST['processing_content'];
  // 受付日
  $reception_date = $_POST['reception_date'];
  // 対応日
  $processing = $_POST['processing'];

  // 画像をアップロードし、パスを格納
  $pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'],'pic1') : '';
  // 画像がPOSTしてない（登録していない)が既にDBに登録されている場合、DBのパスを入れる(POSTには反映されない)
  $pic1 = (empty($pic1) && !empty($dbFormData['pic1'])) ? $dbFormData['pic1']:$pic1;

  $pic2 = (!empty($_FILES['pic2']['name'])) ? uploadImg($_FILES['pic2'],'pic2') : '';
  $pic2 = (empty($pic2) && !empty($dbFormData['pic2'])) ? uploadImg($_FILES['pic2'],'pic2') : '';

  $pic3 = (!empty($_FILES['pic3']['name'])) ? uploadImg($_FILEs['pic3'],'pic3') : '';
  $pic3 = (empty($pic3) && !empty($dbFormData['pic3'])) ? $dbFormData['pic3'] : $pic3;

  // バリデーションチェック


  if(empty($err_msg)){
    debug('バリデーションOKです。');

    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      // 編集画面の場合UPDATE文、新規登録画面の場合はINSERT文を生成
        debug('DB新規登録です。');
        $sql = 'INSERT INTO after_list (name,content,reception_date,processing,processing_content,pic1,pic2,pic3,user_id ) VALUES (:name, :content,:reception_date,:processing,:processing_content,:pic1,:pic2,:pic3,:u_id)';
        $data = array(':name' => $name,':content' => $content,':reception_date' => $reception_date,':processing_content' => $processing_content,':processing' => $processing, ':pic1' => $pic1, ':pic2' => $pic2,':pic3'=> $pic3,':u_id'=>$_SESSION['user_id']);
      debug('SQL'.$sql);
      debug('流し込みデータ:'.print_r($data,true));
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      // クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC04;
        debug('マイページへ遷移します。');

        header("Location:index.php");
      }
    } catch(Exception $e){
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('画面表示処理終了<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>
<?php 
$siteTitle = 'アフター新規登録';
require('head.php');

?>

<body class="page-profEdit page-1colum page-logined">

  <!--    メインコンテンツ    -->
  <!--  メニュー    -->
  <?php
  require('header.php');
  ?>
  <div class="site-width" id="contents">
    <h1 class="page-title">アフターを新規登録</h1>
    <!--     Main      -->
    <section id="main">
      <div class="form-container">
        <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">

          <!-- 項目名 -->
          <div class="area-msg">
            <?php 
              if(!empty($err_msg['common'])) echo $err_msg['common']; 
            ?> 
          </div>
          <label for="" class="<?php if(!empty($err_msg['name'])) echo 'err';?>">
            アフター項目名<span class="label-require">必須</span>
            <input type="text" name="name" value="<?php echo getFormData('name'); ?>">
          </label>
          <div class="area-msg">
            <?php 
            if(!empty($err_msg['name'])) echo $err_msg['name']; ?>
          </div>

          <!-- 詳細 -->
          <label for="" class="<?php if(!empty($err_msg['content'])) echo 'err'; ?>">
            詳細
            <textarea name="content"  cols="30" rows="10" style="height:150px;"><?php echo getFormData('content'); ?></textarea>
          </label>
          <div class="area-msg">
            <?php 
              if(!empty($err_msg['content'])) echo $err_msg['content']; ?>
          </div>
          <!-- 対応内容 -->
          <label for="" class="<?php if(!empty($err_msg['processing_content'])) echo 'err'; ?>">
            対応内容
            <textarea name="processing_content" cols="30" rows="10" style="height:150px;"><?php echo getFormData('processing_content'); ?></textarea>
          </label>
          <div class="area-msg">
            <?php 
              if(!empty($err_msg['processing_content'])) echo $err_msg['processing_content']; ?>
          </div>

          <!-- 受付日 -->
          <label for="" style="text-align:left;" class="<?php if(!empty($err_msg['reception_date'])) echo 'err';?>">受付日<span class="label-require">必須</span>
          <div class="form-group">
            <input type="date" name="reception_date" style="width:150px;" value="<?php echo getFormData('reception_date');?>">
          </div>
          </label>

          <!-- 処理対応日 -->
          <label for="" style="text-align:left;" class="<?php if(!empty($err_msg['processing'])) echo 'err';?>">処理日<span class="label-require">必須</span>
          <div class="form-group">
            <input type="date" name="processing" style="width:150px;"  value="<?php echo getFormData('processing');?>">
          </div>
          </label>

          <!-- 画像 -->
          <div class="area-msg">
            <?php if(!empty($err_msg['price'])) echo $err_msg['price']; ?>
          </div>
          <div style="overflow:hidden">
            <div class="imgDrop-container">
              画像1
              <label for="" class="area-drop <?php if(!empty($err_msg['pic1'])) echo 'err'; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic1" class="input-file">
                <img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display:none;' ?>">
                ドラッグ＆ドロップ
              </label>
              <div class="area-msg">
                <?php 
                if(!empty($err_msg['pic1'])) echo $err_msg['pic1'];
                ?>
              </div>
            </div>
            <div class="imgDrop-container">
              画像2
              <label for="" class="area-drop <?php if(!empty($err_msg['pic2'])) echo 'err'; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic2" class="input-file">
                <img src="<?php echo getFormData('pic2');?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic2'))) echo 'display:none;' ?>">
                ドラッグ＆ドロップ
              </label>
              <div class="area-msg">
                <?php if(!empty($err_msg['pic2'])) echo $err_msg['pic2']; ?>
              </div>
            </div>
            <div class="imgDrop-container">
              画像3
              <label class="area-drop <?php if(!empty($err_msg['pic3'])) echo 'err'; ?>">
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input type="file" name="pic3" class="input-file">
              <img src="<?php echo getFormData('pic3');?>" alt="" class="prev-img" style="<?php if(empty (getFormData('pic3'))) echo 'display:none;'?>">
              ドラッグ＆ドロップ
              </label>
              <div class="area-msg">
                <?php if(!empty($err_msg['pic3'])) echo $err_msg['pic3']; ?>
              </div>
            </div>
          </div>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="登録する">
          </div>
        </form>
      </div>
    </section>

    <!--      footer      -->
    <?php 
      require('footer.php');
    ?>
  </div>

