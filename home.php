<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GradeFlow | Welcome</title>

<style>
/* ====== RESET ====== */
*{margin:0;padding:0;box-sizing:border-box;font-family:Inter,Segoe UI,sans-serif}
body{
  background:#0c0c1d;
  color:white;
  overflow-x:hidden;
}

/* ====== BACKGROUND ANIMATION ====== */
body::before{
  content:"";
  position:fixed;
  inset:0;
  background:
    radial-gradient(circle at 20% 30%, #6c5ce7 0%, transparent 40%),
    radial-gradient(circle at 80% 70%, #fd79a8 0%, transparent 40%),
    radial-gradient(circle at 60% 10%, #00cec9 0%, transparent 35%);
  filter:blur(120px);
  opacity:.5;
  animation: moveBg 12s infinite alternate ease-in-out;
  z-index:-1;
}
@keyframes moveBg{
  from{transform:translateY(-40px) scale(1)}
  to{transform:translateY(40px) scale(1.1)}
}

/* ===== HERO ===== */
.hero{
  min-height:105vh;
  display:flex;
  align-items:center;
  justify-content:center;
  flex-direction:column;
  text-align:center;
  padding:50px;
}
.hero h1{
  font-size:70px;
  line-height:1.1;
  margin-bottom:20px;
}
.hero span{
  background:linear-gradient(90deg,#6c5ce7,#fd79a8);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
}
.hero p{
  max-width:700px;
  opacity:.8;
  margin-bottom:40px;
  font-size:18px;
  font-family: 'Poppins', 'Inter', sans-serif;
}

.hero button{
  padding:18px 50px;
  font-size:18px;
  border-radius:50px;
  border:none;
  background:linear-gradient(90deg,#6c5ce7,#fd79a8);
  box-shadow:0 0 35px #6c5ce799;
  cursor:pointer;
  animation:pulse 4s infinite;
}
@keyframes pulse{
  0%,100%{transform:scale(1)}
  50%{transform:scale(1.08)}
}

</style>
</head>

<body>



<section class="hero">
  <h1>Welcome to <span>GradeFlow</span></h1>
  <p >The most powerful Student Academic & Result Management System. Manage marks, attendance, and results in one beautiful futuristic dashboard.</p>
  <button onclick="goHome()">Start Your Journey</button>

  
</section>


<script>
function goHome(){
  window.location.href='index.php';
}
</script>

</body>
</html>