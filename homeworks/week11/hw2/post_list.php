<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  $username = null;
  if(!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
  }
  
  $sql = 'select * from small_leaf_blog_posts where is_deleted is null order by id desc';

  $stmt = $conn->prepare($sql);
  $result = $stmt->execute();
  if(!$result) {
  die('Error' . $conn->error);
  }
  $result = $stmt->get_result();
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>部落格</title>
  <link rel="stylesheet" href="./index.css">
  <link rel="icon" type="image/png" href="LOGO0.png"/>
</head>
<body>
  <nav class="navbar">
    <div class="wrapper navbar__wrapper">
      <div class="navbar__site-name">
        <a href='index.php'>Who's Blog</a>
      </div>
      <ul class="navbar__list">
        <div>
          <li><a href="post_list.php">文章列表</a></li>
          <li><a href="category_list.php">分類專區</a></li>
          <li><a href="about_me.php">關於我</a></li>
        </div>
        <div>
          <?php if(!empty($username)) { ?>
            <li><a href="add_category.php">新增分類</a></li>
            <li><a href="admin.php">管理文章</a></li>
            <li><a href="logout.php">登出</a></li>
          <?php } else { ?>
            <li><a href="login.php">登入</a></li>
          <?php } ?>
        </div>
      </ul>
    </div>
  </nav>
  <section class="banner">
    <div class="banner__wrapper">
      <h1>存放技術之地</h1>
      <div>Welcome to my blog</div>
    </div>
  </section>
  <div class="container-wrapper">
    <div class="container">
      <div class="admin-posts">
        <?php
          while($row = $result->fetch_assoc()) { 
        ?>
          <div class="admin-post">
            <div class="admin-post__title">
              <a href="post.php?id=<?php echo escape($row['id']) ?>"><?php echo escape($row['title']) ?></a>
            </div>
            <div class="admin-post__info">
              <div class="post-created_at">
                <?php echo escape($row['created_at']) ?>
              </div>
              <?php if(!empty($username)) { ?>
                <a class="admin-post__btn" href="edit.php?id=<?php echo escape($row['id']) ?>">
                  編輯
                </a>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
    <div class="show-error">
      <?php if(!empty($_GET['errCode'])) {
        $code = $_GET['errCode'];
        $msg = 'Error';
        if($code === '1') {
          $msg = '錯誤：資料不齊全';
        } else if($code === '2') {
          $msg = '錯誤：該分類並無文章';
        }
        echo '<span>' . $msg . '</span>';
      } ?>
    </div>
  <footer>Copyright © 2020 Who's Blog All Rights Reserved.</footer>
</body>
</html>