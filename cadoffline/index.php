<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f2f5;
            margin: 0;
        }
        .container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .menu-opcoes {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .botao {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s;
            min-width: 120px;
        }
        /* Cor para o botão Atendimento (Azul) */
        .btn-atendimento {
            background-color: #007bff;
        }
        .btn-atendimento:hover {
            background-color: #0056b3;
        }
        /* Cor para o botão Despacho (Verde ou outra cor de destaque) */
        .btn-despacho {
            background-color: #28a745;
        }
        .btn-despacho:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Selecione o Módulo</h1>
        
        <div class="menu-opcoes">
            <a href="pages/atendimento/menuatd.php" class="botao btn-atendimento">Atendimento</a>

            <a href="pages/despacho/menudes.php" class="botao btn-despacho">Despacho</a>
        </div>
    </div>

</body>
</html>