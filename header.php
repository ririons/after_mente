<header>
  <div class="site-width">
    <h1><a href="index.php">After Mente</a></h1>
    <nav id="top-nav">
      <ul>
        <?php

        if(empty($_SESSION['user_id'])){

        ?>

          <li><a href="login.php">ログイン</a></li>
          <li><a href="logout.php">ログアウト</a></li>
          <?php
        }else{
        ?>
        <li><a href="logout.php">ログアウト</a></li>
        <li><a href="registafter.php">アフター登録</a></li>
        <li><a href="edit.php">アフター更新</a></li>
        
        <?php
        }
        ?>
      </ul>
    </nav>
  </div>
</header>

