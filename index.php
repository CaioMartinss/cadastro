

<?php
require 'mysql.php';
//Acompanha os erros de validação
 

if(isset($_POST['email']) && isset($_POST['senha']))
{ 
    $mysql = MySQL::conectar();
    $validacao = true;
    $erro = '';
    $erro_senha = '';
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $erro = 'Por favor digite um endereço de email válido!';
            $validacao = false;
        }
    } else {
        $erro = 'Por favor digite um endereço de email!';
        $validacao = false;
    }

   
    if (!empty($_POST['senha'])) {
        $senha = $_POST['senha'];
    } else {
        $erro_senha = 'Digite uma senha válida!';
        $validacao = false;
    }
    
    if($validacao)
    {
        $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT
                            `pessoa`.`id`,
                            `pessoa`.`nome`,
                            `pessoa`.`sexo`,
                            `pessoa`.`data_nascimento`,
                            `usuario`.`email`,
                            `usuario`.`senha`
                        FROM
                            `pessoa`,
                            `usuario`
                        WHERE
                            `pessoa`.`id` = `usuario`.`idPessoa` AND `usuario`.`email` = ?";
    
        $q = $mysql->prepare($sql);
        $q->execute(array($email));
        $data = $q->fetch(PDO::FETCH_ASSOC);

         
        $senha_digitada = md5($senha);
        $senha_do_banco = @$data['senha'];
        if($senha_digitada == $senha_do_banco)
        {
            $_SESSION['logado'] = true;
            $_SESSION['id'] = $data['id'];
            header("Location: painel.php");
            exit();
        }
        else
        {
            $_SESSION['logado'] = false;
            $erro_senha = 'Senha incorreta';
        }
         
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Entrar no sistema</title>
</head>

<body>
<div class="container">
<div class="row">
        <div>
               <h2>Engenharia de Software &#9881;</h2>
            </div>
          </div>
            </br>
    <div clas="span10 offset1">
        <div class="card">
            <div class="card-header">
                <h3 class="well"> Entrar no sistema </h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="" method="post">

             

                    <div class="control-group <?php !empty($erro) ? '$erro ' : ''; ?>">
                        <label class="control-label">Email</label>
                        <div class="controls">
                            <input size="40" class="form-control" name="email" type="text" placeholder="Email"
                                   value="<?php echo !empty($email) ? $email : ''; ?>">
                            <?php if (!empty($erro)): ?>
                                <span class="text-danger"><?php echo $erro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php echo !empty($erro_senha) ? 'error ' : ''; ?>">
                        <label class="control-label">Senha</label>
                        <div class="controls">
                            <input size="20" class="form-control" name="senha" type="password" placeholder="Senha"
                                   value="<?php echo !empty($senha) ? $senha : ''; ?>">
                            <?php if (!empty($erro_senha)): ?>
                                <span class="text-danger"><?php echo $erro_senha; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <br/>
                       <button type="submit" class="btn btn-success">Entrar</button>
                        <a href="create.php" class="btn btn-success">Registrar-se</a>
                        <a href="./documentação/Index.html" target="new" class="btn btn-danger">ACESSAR DOCUMENTAÇÂO</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="assets/js/bootstrap.min.js"></script>
</body>

</html>

