<?php
require 'mysql.php';
//Acompanha os erros de validação



// Processar so quando tenha uma chamada post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeErro = null;
    $senhaErro = null;
    $senha2Erro = null;
    $data_nascimentoErro = null;
    $emailErro = null;
    $sexoErro = null;
    $mysql = MySQL::conectar();
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!empty($_POST)) {
        $validacao = true;
        $novoUsuario = false;
        if (!empty($_POST['nome'])) {
            $nome = $_POST['nome'];
        } else {
            $nomeErro = 'Por favor digite o seu nome!';
            $validacao = false;
        }


        if (!empty($_POST['confirmar_senha'])) {
            $senha = $_POST['confirmar_senha'];
        } else {
            $senha2Erro = 'Por favor digite uma senha(2) válida!';
            $validacao = false;
        }

        if (!empty($_POST['senha'])) {
            $senha = $_POST['senha'];
        } else {
            $senhaErro = 'Por favor digite uma senha válida!';
            $validacao = false;
        }

        if (!empty($_POST['data_nascimento'])) {
            $data_nascimento = $_POST['data_nascimento'];
        } else {
            $nascimentoErro = 'Por favor digite o número do nascimento!';
            $validacao = false;
        }


        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $emailErro = 'Por favor digite um endereço de email válido!';
                $validacao = false;
            }
            $sql = "SELECT * FROM `usuario` WHERE `email` = ?";
    
            $q = $mysql->prepare($sql);
            $q->execute(array($email));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            if (!empty($data)) {
                $emailErro = 'Email já existe!';
                $validacao = false;
            }
        } else {
            $emailErro = 'Por favor digite um endereço de email!';
            $validacao = false;
        }


        if (!empty($_POST['sexo'])) {
            $sexo = $_POST['sexo'];
        } else {
            $sexoErro = 'Por favor selecione um campo!';
            $validacao = false;
        }
    }

    //Inserindo no MySQL:
    if ($validacao) {
        try {
            //inserir pessoa
            $sql = "
            START TRANSACTION;
            INSERT INTO pessoa (nome, sexo, data_nascimento) VALUES(?,?,?);
            SELECT LAST_INSERT_ID() INTO @idAs;
            INSERT INTO usuario (email, senha,idPessoa) VALUES(?,?,@idAs);
            COMMIT;
            ";
            $q = $mysql->prepare($sql);
            $q->execute(array($nome, $sexo, $data_nascimento,$email,md5($senha)));
        } catch (PDOException $Exception) {
            $sexoErro = 'Erro no cadastro 1!';
        }
        //
        MySQL::desconectar();
        echo '<script type="text/JavaScript">
        redirectTime = "1000";
        redirectURL = "index.php";
        function timedRedirect() {
            setTimeout("location.href = redirectURL;",redirectTime);
            alert("Cadastro realizado com sucesso, será redirecionado para tela de login");
        }
        timedRedirect();
        </script>';
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <title>Registrando</title>

    <!--Verificando o meu formulário-->
    <script language="javascript">
      function valida_dados (nomeform)
     {
        if (nomeform.senha.value.length<2 || nomeform.senha.value.length>20)//verifica o comprimento da string
          {
              alert ("A senha deve conter entre 2 a 20 caracteres.");
              return false;
          }
        if (nomeform.senha.value != nomeform.confirmar_senha.value) //verifica se as senhas são iguais
          {
            alert ("Senhas não coincidem!");
            return false;
          }
    return true
        }
    </script>
</head>

<body>
<div class="container">
    <div clas="span10 offset1">
        <div class="card">
            <div class="card-header">
                <h3 class="well"> Registro </h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="create.php" method="post" onSubmit="return valida_dados(this)">

                    <div class="control-group  <?php echo !empty($nomeErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Nome</label>
                        <div class="controls">
                            <input size="50" class="form-control" name="nome" type="text" placeholder="Nome"
                                   value="<?php echo !empty($nome) ? $nome : ''; ?>">
                            <?php if (!empty($nomeErro)): ?>
                                <span class="text-danger"><?php echo $nomeErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php !empty($emailErro) ? '$emailErro ' : ''; ?>">
                        <label class="control-label">Email</label>
                        <div class="controls">
                            <input size="40" class="form-control" name="email" type="text" placeholder="Email"
                                   value="<?php echo !empty($email) ? $email : ''; ?>">
                            <?php if (!empty($emailErro)): ?>
                                <span class="text-danger"><?php echo $emailErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php echo !empty($senhaErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Senha</label>
                        <div class="controls">
                            <input size="20" class="form-control" name="senha" type="password" placeholder="Senha"
                                   value="<?php echo !empty($senha) ? $senha : ''; ?>">
                            <?php if (!empty($senhaErro)): ?>
                                <span class="text-danger"><?php echo $senhaErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="control-group <?php echo !empty($senha2Erro) ? 'error ' : ''; ?>">
                        <label class="control-label">Confirme sua senha</label>
                        <div class="controls">
                            <input size="20" class="form-control" name="confirmar_senha" type="password" placeholder="Senha"
                                   value="<?php echo !empty($senha2) ? $senha2 : ''; ?>">
                            <?php if (!empty($senha2Erro)): ?>
                                <span class="text-danger"><?php echo $senha2Erro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php echo !empty($data_nascimentoErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Data de Nascimento</label>
                        <div class="controls">
                            <input size="10" class="form-control" name="data_nascimento" type="date" placeholder="data_nascimento" 
                                   value="<?php echo !empty($data_nascimento) ? $data_nascimento : ''; ?>">
                            <?php if (!empty($data_nascimentoErro)): ?>
                                <span class="text-danger"><?php echo $data_nascimentoErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php !empty($sexoErro) ? 'echo($sexoErro)' : ''; ?>">
                        <div class="controls">
                            <label class="control-label">Sexo</label>
                            <div class="form-check">
                                <p class="form-check-label">
                                    <input class="form-check-input" type="radio" name="sexo" id="sexo"
                                           value="M" <?php isset($_POST["sexo"]) && $_POST["sexo"] == "M" ? "checked" : null; ?>/>
                                    Masculino</p>
                            </div>
                            <div class="form-check">
                                <p class="form-check-label">
                                    <input class="form-check-input" id="sexo" name="sexo" type="radio"
                                           value="F" <?php isset($_POST["sexo"]) && $_POST["sexo"] == "F" ? "checked" : null; ?>/>
                                    Feminino</p>
                            </div>
                            <?php if (!empty($sexoErro)): ?>
                                <span class="help-inline text-danger"><?php echo $sexoErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <br/>
                        <button type="submit" class="btn btn-success">Registrar-se</button>
                        <a href="index.php" class="btn btn-success">Voltar<a>
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

