<?php

require 'mysql.php';

if(!$_SESSION['logado'])
{
    header("Location: index.php");
    exit;
}

$id = null;
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: painel.php");
}

if (!empty($_POST)) {

    $nomeErro = null;
    $emailErro = null;
    $senhaErro = null;
    $data_nascimentoErro = null;
    $sexoErro = null;

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $data_nascimento = $_POST['data_nascimento'];
    $sexo = $_POST['sexo'];

    //Validação
    $validacao = true;
    if (empty($nome)) {
        $nomeErro = 'Por favor digite o nome!';
        $validacao = false;
    }

    if (empty($email)) {
        $emailErro = 'Por favor digite o email!';
        $validacao = false;


    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErro = 'Por favor digite um email válido!';
        $validacao = false;
    }

    if (empty($senha)) {
        $senhaErro = 'Por favor digite a senha!';
        $validacao = false;
    }

    if (empty($data_nascimento)) {
        $data_nascimentoErro = 'Por favor digite a data de nascimento!';
        $validacao = false;
    }

    if (empty($sexo)) {
        $sexoErro = 'Por favor preenche o campo!';
        $validacao = false;
    }

    // update data
    if ($validacao) {
        $mysql = MySQL::conectar();
        $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
        $sql = "UPDATE pessoa  set nome = ?, data_nascimento = ?, sexo = ? WHERE id = ?";
        $q = $mysql->prepare($sql);
        $q->execute(array($nome, $data_nascimento, $sexo, $id));


        $sql = "UPDATE usuario  set email = ?, senha = ? WHERE id = ?";
        $q = $mysql->prepare($sql);
        $q->execute(array($email, md5($senha), $id));


        MySQL::desconectar();
        header("Location: painel.php");
    }
} else {
    $mysql = MySQL::conectar();
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
    `pessoa`.`id` = `usuario`.`idPessoa` AND `pessoa`.`id` = ?";
    $q = $mysql->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $nome = $data['nome'];
    $email = $data['email'];
    $senha = $data['senha'];
    $data_nascimento = $data['data_nascimento'];
    $sexo = $data['sexo'];
    MySQL::desconectar();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- using new bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Atualizar Contato</title>
</head>

<body>
<div class="container">

    <div class="span10 offset1">
        <div class="card">
            <div class="card-header">
                <h3 class="well"> Atualizar Contato </h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="update.php?id=<?php echo $id ?>" method="post">

                    <div class="control-group <?php echo !empty($nomeErro) ? 'error' : ''; ?>">
                        <label class="control-label">Nome</label>
                        <div class="controls">
                            <input name="nome" class="form-control" size="50" type="text" placeholder="Nome"
                                   value="<?php echo !empty($nome) ? $nome : ''; ?>">
                            <?php if (!empty($nomeErro)): ?>
                                <span class="text-danger"><?php echo $nomeErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php echo !empty($emailErro) ? 'error' : ''; ?>">
                        <label class="control-label">Email</label>
                        <div class="controls">
                            <input name="email" class="form-control" size="40" type="text" placeholder="Email"
                                   value="<?php echo !empty($email) ? $email : ''; ?>">
                            <?php if (!empty($emailErro)): ?>
                                <span class="text-danger"><?php echo $emailErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php echo !empty($senhaErro) ? 'error' : ''; ?>">
                        <label class="control-label">Senha</label>
                        <div class="controls">
                            <input name="senha" class="form-control" size="20" type="password" placeholder="Senha"
                                   value="<?php echo !empty($senha) ? '': ''; ?>">
                            <?php if (!empty($senhaErro)): ?>
                                <span class="text-danger"><?php echo $senhaErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php echo !empty($data_nascimentoErro) ? 'error' : ''; ?>">
                        <label class="control-label">Data de Nascimento</label>
                        <div class="controls">
                            <input name="data_nascimento" class="form-control" size="10" type="date" placeholder="Data de Nascimento"
                                   value="<?php echo !empty($data_nascimento) ? $data_nascimento : ''; ?>">
                            <?php if (!empty($data_nascaimentoErro)): ?>
                                <span class="text-danger"><?php echo $data_nascimentoErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="control-group <?php echo !empty($sexoErro) ? 'error' : ''; ?>">
                        <label class="control-label">Sexo</label>
                        <div class="controls">
                            <div class="form-check">
                                <p class="form-check-label">
                                    <input class="form-check-input" type="radio" name="sexo" id="sexo"
                                           value="M" <?php echo ($sexo == "M") ? "checked" : null; ?>/> Masculino
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sexo" id="sexo"
                                       value="F" <?php echo ($sexo == "F") ? "checked" : null; ?>/> Feminino
                            </div>
                            </p>
                            <?php if (!empty($sexoErro)): ?>
                                <span class="text-danger"><?php echo $sexoErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <br/>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">Atualizar</button>
                        <a href="painel.php" class="btn btn-warning">Voltar<a>
                    </div>
                </form>
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
