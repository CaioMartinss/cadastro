<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="./assets/reset.css">
  <link rel="stylesheet" href="./assets/style.css">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>

<body>
  <main>
    <h1 class="cadastro">Catálogo</h1>
    <?php
    $pagina_atual = $_GET['pagina'];
    if (!file_exists('./paginas/' . $pagina_atual . '.php') || empty($_GET['pagina'])) {
        header("Location: ?pagina=login");
    } else {
        require_once('./paginas/' . $pagina_atual . '.php');
    }
    ?>
  </main>

</html>