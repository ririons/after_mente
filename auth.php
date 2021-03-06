<?php

// ====================
// ログイン認証・自動ログアウト
// ====================
// ログインしている場合
if(!empty($_SESSION['login_date'])){
  debug('ログイン済みユーザーです');

  // 現在日時が最終ログイン日時＋有効期限を超えていた場合
  if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()) {
    debug('ログイン有効期限オーバーです。');

    // セッションを削除（ログアウトする）
    session_destroy();
    // ログインページへ
    header("Location:login.php");
  }else{
    debug('ログイン有効期限内です。');
    // 最終ログイン日時を現在日時に更新
    $_SESSION['login_date'] = time();

    // 現在実行中のスクリプトファイル名がlogin.phpの場合
    // basename関数を使い、ファイル名を取る
    if(basename($_SERVER['PHP_SELF']) === 'login.php'){
      debug('トップページへ遷移します。');
      header("Location:index.php"); //トップページ
    }
  }
}else{
  debug('未ログインユーザーです。');
  if(basename($_SERVER['PHP_SELF'])!=='login.php'){
    header('Location:login.php');
    // ログインページへ
  }
}