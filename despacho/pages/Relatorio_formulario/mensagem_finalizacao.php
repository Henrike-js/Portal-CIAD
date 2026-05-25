<?php
$chamada = filter_input(INPUT_GET,'chamada',FILTER_VALIDATE_INT);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Chamada Finalizada</title>

<style>
body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#F4F5F8;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:#fff;
    padding:40px 50px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 4px 20px rgba(0,0,0,.1);
}

.box h1{
    color:#1E7E34;
    margin-bottom:10px;
}

.box p{
    color:#444;
    font-size:16px;
}

.countdown{
    margin-top:15px;
    font-size:18px;
    font-weight:bold;
    color:#16325C;
}
</style>
</head>

<body>

<div class="box">
    <h1>✅ Chamada salva com sucesso</h1>
   <p>Chamada nº <strong><?= $chamada ?></strong> salva com sucesso.</p>
<p>Você será redirecionado automaticamente.</p>
    <div class="countdown">
        Retornando em <span id="segundos">3</span> segundos...
    </div>
</div>

<script>
let segundos = 3;
const span = document.getElementById('segundos');

const timer = setInterval(() => {
    segundos--;
    span.textContent = segundos;

    if (segundos <= 0) {
        clearInterval(timer);
        window.location.href = "relatorio.php";
    }
}, 1000);
</script>

</body>
</html>
