<?php
  session_start();
  require_once('conn.php');
  require_once('check_permission.php');
  require_once('utils.php');

  $username = NULL;
  $user = NULL;
  if(!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
  }
  
  $id = $_GET['id'];

  if(empty($id)) {
    header('Location: index.php');
    die();
  }

  $sql = 'select * from small_leaf_blog_categories where id=?';

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $id);
  $result = $stmt->execute();
  if(!$result) {
  die('Error' . $conn->error);
  }
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>部落格</title>
  <link rel="stylesheet" href="index.css" />
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
            <li><a href="admin.php">管理文章</a></li>
            <li><a href="admin_category.php">管理分類</a></li>
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
      <div class="edit-post">
        <form action="handle_edit_category.php" method="POST">
          <div class="edit-post__title">
            編輯分類：
          </div>
          <div class="edit-post__input-wrapper">
            <input name="name" class="edit-post__input" placeholder="請輸入文章類別" value="<?php echo escape($row['name']) ?>"/>
          </div>
          <div class="edit-post__btn-wrapper">
              <?php if(!empty($_GET['errCode'])) {
                $code = $_GET['errCode'];
                $msg = 'Error';
                if($code === '1') {
                  $msg = '錯誤：資料不齊全'; 
                }
                echo '<span class="add__post-error">' . $msg . '</span>';
              } ?>
              <input class="edit-post__btn" type="submit" value="送出"/>
          </div>
          <input type="hidden" name="id" value="<?php echo escape($row['id']) ?>">
          <input type="hidden" name="lastPage" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
        </form>
      </div>
    </div>
  </div>
  <footer>Copyright © 2020 Who's Blog All Rights Reserved.</footer>
</body>
</html>