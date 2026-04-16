<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Rafting</title>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
}

.hero {
  height: 100vh;
  background: url('images/6.jpg') no-repeat center/cover;
  position: relative;
}

.overlay {
  background: rgba(0, 0, 0, 0.4);
  height: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 50px;
  color: white;
  box-sizing: border-box;
}

.content h1 {
  font-size: 48px;
  margin: 0 0 20px;
  text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
}

.content p {
  max-width: 400px;
  line-height: 1.5;
  text-shadow: 1px 1px 5px rgba(0,0,0,0.7);
}

.login-box {
  width: 300px;
  background: rgba(255, 255, 255, 0.2);
  padding: 20px;
  border-radius: 10px;
  backdrop-filter: blur(6px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.6);
}

.login-box h2 {
  text-align: center;
  margin-bottom: 15px;
}

.login-box input[type="text"],
.login-box input[type="password"] {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border: none;
  border-radius: 5px;
}

.login-box label {
  font-size: 14px;
}

.login-box button {
  width: 100%;
  padding: 10px;
  margin-top: 10px;
  background: #c7a17a;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
}

.login-box button:hover {
  background: #b08968;
}

.login-box a {
  display: block;
  text-align: center;
  margin-top: 10px;
  color: white;
  font-size: 14px;
  text-decoration: none;
}
</style>

</head>
<body>

<div class="hero">
  <div class="overlay">

    <div class="content">
      <h1>Selamat Datang<br>di Rafting Singorojo</h1>
      <p>
        Rasakan keseruan arung jeram di tengah keindahan alam Singorojo. Menawarkan pengalaman petualangan yang aman, seru, dan tak terlupakan untuk semua kalangan. Ayo berpetualang bersama kami!
      </p>
    </div>

    <form method="post" action="login.php">
      <div class="login-box">
        <h2>SIGN IN</h2>
          <label>Email</label>
          <input type="text" class="form-control" name="email" placeholder="Email Addres">
          <label>Password</label>
          <input type="password" class="form-control" name="password" placeholder="Password">        
        <label>
          <input type="checkbox"> Remember Me
        </label>

        <button>Sign in now</button>
        <a href="#">Lost your password?</a>
      </div>
    </form>

  </div>
</div>

</body>
</html>