<?php 

// 共通変数、関数ファイルを読み込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// ログイン認証
require('auth.php');

//=============================
// 画面処理
//=============================

// 画面表示用データ取得
//=============================
// GETパラメータを取得
//-----------------------------
// カレントページ
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
// デフォルトは1ページ目
if(!is_int((int)$currentPageNum)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header("Location:index.php");
  // トップページ
}
// 表示件数
$listSpan = 20;
// 現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan);
$dbProductData = getProductList($currentMinNum);


// DBからカテゴリーデータを取得

debug('画面表示処理終了<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php 
$siteTitle = 'HOME';
require('head.php');
?>

<body class="page-home page-2colum">

  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>

  <!-- メインコンテンツ -->
  <div id="contents" class="site-width">

    <!-- サイドバー -->
    <!-- Main -->
    <section id="main">
      <div class="panel-list">
        <?php 
        foreach($dbProductData['data'] as $key => $val):
        debug('データ配列'.print_r($dbProductData,true));
        ?>
        <a href="Detail.php<?php echo (!empty (appendGetParam())) ? appendGetParam().'&p_id='.$val['id']: '?p_id='.$val['id']; ?>" class="panel">
            <div class="panel-head">
              <img src="<?php echo sanitize($val['pic1']);?>" alt="<?php echo sanitize($val['name']); ?>">
            </div>
            <div class="panel-body">
              <p class="panel-title"><?php echo sanitize($val['name']); ?><span class="reception_date">¥<?php echo sanitize($val['reception_date']); ?></span></p>
            </div>
        </a>
        <?php 
        endforeach;
        ?>
      </div>

      <?php pagination($currentPageNum, $dbProductData['total_page']); ?>
    </section>
  </div>

  <!-- footer -->
  <?php
  require('footer.php');
  ?>

