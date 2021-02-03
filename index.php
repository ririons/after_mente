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
// カテゴリー
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
// ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
// パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header("Location:index.php");
  // トップページ
}
// 表示件数
$listSpan = 20;
// 現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan);
// DBからアフターデータを取得
getProductOne($p_id);
$dbProductData = getProductList($currentMinNum,$category,$sort);
// DBからカテゴリーデータを取得
// $dbCategoryData = getCategory();
// debug('カテゴリデータ'.print_r($dbCategoryData,true));

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
    <section id="sidebar">
      <form action="" name="" method="get">
        <h1 class="title">カテゴリー</h1>
        <div class="selectbox">
          <span class="icn_select"></span>
          <select name="c_id" id="">
            <option value="0" <?php if(getFormData('c_id',true) == 0){echo 'selected';}?>>選択してください
          </select>
        </div>
      </form>
    </section>

    <!-- Main -->
    <section id="main">
      <div class="panel-list">
        <?php 
        foreach($dbProductData['data'] as $key => $val):
        debug('データ配列'.print_r($dbProductData,true));
        ?>
        <a href="Detail.php<?php echo (!empty (appendGetParam())) ? appendGetParam().'&p_id='.$val['id']: '?p_id='.$val['id']; ?>" class="panel">
            <div class="panel-head">
              <img src="<?php echo sanitize($val['pic']); ?>" alt="<?php echo sanitize($val['name']); ?>">
            </div>
            <div class="panel-body">
              <p class="panel-title"><?php echo sanitize($val['name']); ?><span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span></p>
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

