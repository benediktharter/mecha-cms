<?php

define('ROOT', rtrim(__DIR__, '\\/'));
define('DS', DIRECTORY_SEPARATOR);

session_save_path(ROOT . DS . 'system' . DS . 'log' . DS . 'sessions');
session_start();

$errors = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if( ! isset($_SESSION['token']) || ! isset($_POST['token'])) {
        $errors[] = '<p>Invalid token.</p>';
    } else {
        if((string) $_SESSION['token'] !== (string) $_POST['token']) {
            $errors[] = '<p>Invalid token.</p>';
        }
    }

    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;

    if(trim($_POST['name']) === "") $errors[] = '<p><i class="fa fa-exclamation-triangle"></i> What&rsquo;s your name?</p>';
    if(trim($_POST['email']) === "") {
        $errors[] = '<p><i class="fa fa-exclamation-triangle"></i> What&rsquo;s your email? Mecha need that.</p>';
    } else {
        if( ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '<p><i class="fa fa-exclamation-triangle"></i> Invalid email address.</p>';
        }
    }
    if(trim($_POST['username']) === "") $errors[] = '<p><i class="fa fa-exclamation-triangle"></i> What&rsquo;s your username? Mecha need that.</p>';
    if(trim($_POST['password']) === "") $errors[] = '<p><i class="fa fa-exclamation-triangle"></i> What&rsquo;s your password? Mecha need that' . (trim($_POST['username']) === "" ? ' too' : "") . '.</p>';
    if(trim($_POST['username']) !== "" && preg_match('#[^a-z0-9\-\_]#i', $_POST['username'])) $errors[] = '<p><i class="fa fa-exclamation-triangle"></i> Username can only contain letters, numbers, <code>-</code> and <code>_</code>.</p>';
    if(trim($_POST['password']) !== "" && preg_match('#[^a-z0-9\-\_]#i', $_POST['password'])) $errors[] = '<p><i class="fa fa-exclamation-triangle"></i> Password can only contain letters, numbers, <code>-</code> and <code>_</code>.</p>';

    $_SESSION['meet_mecha'] = $_POST;

    if(count($errors) === 0) {
        $user_file = ROOT . DS . 'system' . DS . 'log' . DS . 'users.txt';
        $data  = $_POST['username'] . ': ';
        $data .= $_POST['password'] . ' (';
        $data .= $_POST['name'] . ':pilot) ';
        $data .= $_POST['email'];
        if( ! file_exists($user_file)) file_put_contents($user_file, $data);
        $_SESSION['message'] = '<div class="messages"><p class="message message-success cf"><i class="fa fa-thumbs-up"></i> Okay. Now you can login with these details&hellip;</p><p class="message message-info cf code"><strong>Username:</strong> ' . $_POST['username'] . '<br><strong>Password:</strong> ' . $_POST['password'] . '</p></div>';
        chmod(ROOT . DS . 'cabinet' . DS . 'assets', 0777);
        chmod(ROOT . DS . 'cabinet' . DS . 'plugins', 0777);
        chmod(ROOT . DS . 'cabinet' . DS . 'articles', 0766);
        chmod(ROOT . DS . 'cabinet' . DS . 'pages', 0766);
        chmod(ROOT . DS . 'cabinet' . DS . 'custom', 0766);
        chmod(ROOT . DS . 'cabinet' . DS . 'responses', 0766);
        chmod(ROOT . DS . 'cabinet' . DS . 'scraps', 0766);
        chmod(ROOT . DS . 'cabinet' . DS . 'states', 0766);
        chmod(ROOT . DS . 'system' . DS . 'log' . DS . 'users.txt', 0600);
        unlink(ROOT . DS . 'cabinet' . DS . 'articles' . DS . '.empty');
        unlink(ROOT . DS . 'cabinet' . DS . 'custom' . DS . '.empty');
        unlink(ROOT . DS . 'cabinet' . DS . 'pages' . DS . '.empty');
        unlink(ROOT . DS . 'cabinet' . DS . 'responses' . DS . '.empty');
        unlink(ROOT . DS . 'cabinet' . DS . 'scraps' . DS . '.empty');
        unlink(ROOT . DS . 'cabinet' . DS . 'states' . DS . '.empty');
        unlink(ROOT . DS . 'system' . DS . 'log' . DS . 'sessions' . DS . '.empty');
        unlink(ROOT . DS . 'install.php');
        unset($_SESSION['meet_mecha']);
        unset($_SESSION['token']);
        $base = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '\\/');
        header('Location: http://' . $_SERVER['HTTP_HOST'] . ( ! empty($base) ? '/' . $base . '/' : '/') . 'manager/login');
        exit;
    }

} else {

    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;

}

?>
<!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Hi!</title>
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
* {
  margin:0;
  padding:0;
}
html,
body {overflow:auto}
html {
  font:normal normal 13px/1.3846153846153846em Helmet,FreeSans,Sans-Serif;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
  -webkit-text-size-adjust:100%;
  -moz-text-size-adjust:100%;
  -ms-text-size-adjust:100%;
  text-size-adjust:100%;
}
body {
  background-color:white;
  color:#333;
  padding:5% 0;
}
form {
  display:block;
  max-width:25em;
  margin:0 auto;
  padding:1.3846153846153846em;
}
h3 {
  font-size:160%;
  font-weight:normal;
  margin:0;
}
input,
button {
  -webkit-box-sizing:border-box;
  -moz-box-sizing:border-box;
  box-sizing:border-box;
  display:block;
  width:100%;
  vertical-align:middle;
  padding:.7692307692307693em;
  margin:0;
  font:inherit;
  line-height:normal;
  border:none;
  background-color:#333;
  color:white;
  text-align:left;
}
button {
  width:auto;
  text-align:center;
  cursor:pointer;
  background-color:#365D98;
  text-align:center;
  text-decoration:none;
  color:#FFA;
  padding-left:.9230769230769231em;
  padding-right:.9230769230769231em;
  -webkit-border-radius:2px;
  -moz-border-radius:2px;
  border-radius:2px;
  -webkit-user-select:none;
  -moz-user-select:none;
  -ms-user-select:none;
  user-select:none;
}
button:focus,
button:hover {background-color:#3A64A4}
button:active {background-color:#32568C}
button::-moz-focus-inner {
  marign:0;
  padding:0;
  border:none;
  outline:none;
}
label,
label + div {
  display:block;
  margin:1.3846153846153846em 0 0;
  overflow:hidden;
}
label span,
label + div span {display:block}
label span:first-child,
label + div span:first-child {
  padding:0 0 .7692307692307693em;
  line-height:normal;
}
h3 + div {margin-top:1.3846153846153846em}
h3 + div p {
  margin:0;
  padding:.7692307692307693em .9230769230769231em;
  background-color:#CF3030;
  color:white;
  overflow:hidden;
}
h3 + div p + p {margin-top:2px}
h3 + div p code {opacity:.7}
    </style>
  </head>
  <body>
    <form method="post">
      <input name="token" type="hidden" value="<?php echo isset($token) ? $token : ""; ?>">
      <h3>First Meet</h3>
      <?php $cache = isset($_SESSION['meet_mecha']) ? $_SESSION['meet_mecha'] : array('name' => "", 'username' => "", 'password' => ""); echo ! empty($errors) ? '<div>' . implode("", $errors) . '</div>' : ""; ?>
      <label>
        <span>Name</span>
        <span><input name="name" type="text" value="<?php echo isset($cache['name']) ? $cache['name'] : ""; ?>" autofocus></span>
      </label>
      <label>
        <span>Email</span>
        <span><input name="email" type="email" value="<?php echo isset($cache['email']) ? $cache['email'] : ""; ?>"></span>
      </label>
      <label>
        <span>Username</span>
        <span><input name="username" type="text" value="<?php echo isset($cache['username']) ? $cache['username'] : ""; ?>"></span>
      </label>
      <label>
        <span>Password</span>
        <span><input name="password" type="password" value="<?php echo isset($cache['password']) ? $cache['password'] : ""; ?>"></span>
      </label>
      <div>
        <span></span>
        <span><button type="submit"><i class="fa fa-user"></i> Install</button></span>
      </div>
    </form>
  </body>
</html>