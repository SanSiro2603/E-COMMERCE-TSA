<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>403 | Akses Ditolak</title>

<link href="https://fonts.googleapis.com/css?family=Dosis:300,400,500" rel="stylesheet">

<style>
/* ===== ANIMATIONS ===== */
@keyframes rocket-movement { 100% { transform: translate(1200px,-600px);} }
@keyframes spin-earth { 100% { transform: rotate(-360deg);} }
@keyframes move-astronaut{ 100% { transform:translate(-160px, -160px); } }
@keyframes rotate-astronaut{ 100% { transform:rotate(-720deg); } }
@keyframes glow-star{
  40% { opacity: 0.3; }
  90%,100% { opacity: 1; transform: scale(1.2); }
}

/* ===== BASE ===== */
html, body{
  margin: 0;
  width: 100%;
  height: 100%;
  font-family: 'Dosis', sans-serif;
  background: #3f51b5;
  user-select: none;
}

.bg-purple{
  background: url('https://salehriaz.com/404Page/img/bg_purple.png') repeat-x;
  background-size: cover;
  height: 100%;
  overflow: hidden;
}

.central-body{
  padding: 18% 5% 10%;
  text-align: center;
  color: white;
}

.central-body h1{
  font-size: 120px;
  margin: 0;
}

.central-body p{
  font-size: 18px;
  letter-spacing: 1px;
  margin-bottom: 30px;
}

.btn-go-home{
  display: inline-block;
  padding: 12px 28px;
  border: 1px solid #FFCB39;
  border-radius: 50px;
  color: white;
  text-decoration: none;
  font-size: 12px;
  letter-spacing: 2px;
  transition: .3s;
}

.btn-go-home:hover{
  background: #FFCB39;
  color: #000;
  transform: scale(1.05);
}

/* ===== OBJECTS ===== */
.object_rocket{
  position: absolute;
  top: 75%;
  left: -100px;
  width: 40px;
  animation: rocket-movement 200s linear infinite;
}

.object_earth{
  position: absolute;
  top: 20%;
  left: 15%;
  width: 100px;
  animation: spin-earth 200s infinite linear;
}

.object_moon{
  position: absolute;
  top: 12%;
  left: 25%;
  width: 80px;
}

.box_astronaut{
  position: absolute;
  top: 60%;
  right: 20%;
  animation: move-astronaut 50s infinite linear alternate;
}

.object_astronaut{
  width: 140px;
  animation: rotate-astronaut 200s infinite linear;
}

/* ===== STARS ===== */
.glowing_stars .star{
  position: absolute;
  width: 3px;
  height: 3px;
  background: white;
  opacity: 0.3;
  border-radius: 50%;
}

.glowing_stars .star:nth-child(1){ top: 80%; left: 25%; animation: glow-star 2s infinite alternate;}
.glowing_stars .star:nth-child(2){ top: 20%; left: 40%; animation: glow-star 2s infinite alternate 1s;}
.glowing_stars .star:nth-child(3){ top: 25%; left: 25%; animation: glow-star 2s infinite alternate 2s;}
.glowing_stars .star:nth-child(4){ top: 75%; left: 80%; animation: glow-star 2s infinite alternate 3s;}
.glowing_stars .star:nth-child(5){ top: 90%; left: 50%; animation: glow-star 2s infinite alternate 4s;}
</style>
</head>

<body class="bg-purple">

  <div class="central-body">
      <h1>403</h1>
      <p>MAAF, ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI PERGI GAK!!!</p>
      <a href="{{ route('landing') }}" class="btn-go-home">KEMBALI KE BERANDA</a>
  </div>

  <img class="object_rocket" src="https://salehriaz.com/404Page/img/rocket.svg">
  <img class="object_earth" src="https://salehriaz.com/404Page/img/earth.svg">
  <img class="object_moon" src="https://salehriaz.com/404Page/img/moon.svg">

  <div class="box_astronaut">
      <img class="object_astronaut" src="https://salehriaz.com/404Page/img/astronaut.svg">
  </div>

  <div class="glowing_stars">
      <div class="star"></div>
      <div class="star"></div>
      <div class="star"></div>
      <div class="star"></div>
      <div class="star"></div>
  </div>

</body>
</html>
