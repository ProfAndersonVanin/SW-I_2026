<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processa Dados</title>
</head>
<body>
    
    <?php
        $nome =  $_POST['nome'];
        $email = $_POST['email'];
        $idade = $_POST['idade'];

        $ano_atual = date('Y');
        //echo $ano_atual;
        $ano_nasc = $ano_atual - $idade;
    ?>

    <p>O nome digitado é: <?php echo $nome; ?> </p>
    <p>O email digitado é: <?php echo $email; ?> </p>
    <p>A idade digitada é: <?php echo $idade; ?> </p>
    <p>Seu ano de nascimento é: <?php echo $ano_nasc; ?> </p>


</body>
</html>