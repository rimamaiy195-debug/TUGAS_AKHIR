<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register Rafting</title>

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

.form-box {
  width: 320px;
  background: rgba(255, 255, 255, 0.2);
  padding: 20px;
  border-radius: 10px;
  backdrop-filter: blur(6px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.6);
}

.form-box h2 {
  text-align: center;
  margin-bottom: 15px;
}

.form-box input {
  width: 95%;
  padding: 10px;
  margin: 8px 0;
  border: none;
  border-radius: 5px;
  outline: none;
}

.form-box button {
  width: 100%;
  padding: 10px;
  margin-top: 10px;
  background: #c7a17a;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s;
}

.form-box button:hover {
  background: #b08968;
  transform: scale(1.02);
}

.login-link {
  margin-top: 10px;
  text-align: center;
  font-size: 13px;
}

.login-link a {
  color: #ffd7a8;
  font-weight: bold;
  text-decoration: none;
}

.login-link a:hover {
  text-decoration: underline;
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

    <form method="POST" action="daftar.php">
      <div class="form-box">

        <h2>REGISTER</h2>

        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="no_hp" placeholder="No HP" required>
        <input type="text" name="alamat" placeholder="Alamat" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">Daftar</button>

        <div class="login-link">
          Sudah punya akun?
          <a href="index.php">Login di sini</a>
        </div>

      </div>
    </form>

  </div>
</div>

</body>
</html>