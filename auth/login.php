<?php
session_start();
require '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email))    { $error = "Invalid email or password."; }
    if(empty($password)) { $error = "Invalid email or password."; }

    if(empty($error)){
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user   = mysqli_fetch_assoc($result);

        if($user){
            if(password_verify($password, $user['password'])){
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                header('Location: ../pages/home.php');
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign In – CinemAura</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/css/login.css"/>
</head>
<body>
<div class="page">

  <!-- LEFT -->
  <div class="left">
    <div class="left-bg"></div>

    <div class="film-strip">
      <?php for($i = 0; $i < 20; $i++): ?>
        <div class="strip-seg">
          <div class="hole"></div>
          <div class="hole"></div>
        </div>
      <?php endfor; ?>
    </div>

    <a href="../index.php" class="left-logo">Cinem<span>Aura</span></a>

    <div class="left-middle">
      <div class="left-eyebrow">Welcome Back</div>
      <h2 class="left-title">LIGHTS. <span>CAMERA.</span> ACTION.</h2>
      <p class="left-desc">Sign back in and pick up where you left off — your next movie experience is waiting.</p>
      <div class="left-perks">
        <div class="perk"><div class="perk-icon">🎬</div> Browse movies & watch trailers</div>
        <div class="perk"><div class="perk-icon">🪑</div> Book Gold, Platinum or Box seats</div>
        <div class="perk"><div class="perk-icon">🎟️</div> View your booking history</div>
        <div class="perk"><div class="perk-icon">⭐</div> Read & write movie reviews</div>
      </div>
    </div>

    <div class="left-bottom">© <?= date('Y') ?> CinemAura</div>
  </div>

  <!-- RIGHT -->
  <div class="right">
    <div class="form-wrap">
      <h1 class="form-heading">Sign In</h1>
      <p class="form-sub">Don't have an account? <a href="register.php">Register →</a></p>
      <p class="form-sub"><a href="../pages/home.php">← Back to Home</a></p>

      <?php if($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" action="">

        <div class="field">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email"
                 placeholder="you@example.com"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                 required/>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="password-wrap">
            <input type="password" id="password" name="password"
                   placeholder="Your password"
                   required/>
            <button type="button" class="toggle-pw" onclick="togglePw()">👁</button>
          </div>
          <a href="#" class="forgot">Forgot password?</a>
        </div>

        <hr class="divider"/>

        <button type="submit" class="btn-submit">Sign In</button>

      </form>
    </div>
  </div>

</div>

<script>
function togglePw() {
  const input = document.getElementById('password');
  const btn   = document.querySelector('.toggle-pw');
  input.type  = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
</script>
</body>
</html>