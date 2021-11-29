<!DOCTYPE html>
<html lang="pt-br">
 
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Painel Principal</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
</head>

<body>
        <div class="container">
        <div class="row">
        <div>
               <h2>Engenharia de Software &#9881;</h2>
            </div>
          </div>
            </br>
            <div class="row">

                <table class="table table-striped" id="minhaTabela" name="minhaTabela">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Email</th>
                            <th scope="col">Sexo</th>
                            <th scope="col">Data de Nascimento</th>
                            <th scope="col">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'mysql.php';
                       
                        if(!$_SESSION['logado'])
                        {
                            header("Location: logout.php");
                            exit();
                        }
                        $mysql = MySQL::conectar();
                        $sql = 'SELECT
                        `pessoa`.`id`,
                        `pessoa`.`nome`,
                        `pessoa`.`sexo`,
                        `pessoa`.`data_nascimento`,
                        `usuario`.`email`
                    FROM
                        `pessoa`,
                        `usuario`
                    WHERE
                        `pessoa`.`id` = `usuario`.`idPessoa` AND `pessoa`.`id` = '.$_SESSION['id'].'
                    ORDER BY
                        `pessoa`.`id`
                    DESC';

                        foreach ($mysql->query($sql)as $row) {
                            echo '<tr>';
                            echo '<th scope="row">'. $row['id'] . '</th>';
                            echo '<td>'. $row['nome'] . '</td>';
                            echo '<td>'. $row['email'] . '</td>';
                            echo '<td>'. $row['sexo'] . '</td>';
                            $date = date_create($row['data_nascimento']);
                            echo '<td>'. date_format($date,"d/m/Y") . '</td>';
                            echo '<td width=250>';
                            echo '<a class="btn btn-primary" href="read.php?id='.$row['id'].'">Info</a>';
                            echo ' ';
                            echo '<a class="btn btn-warning" href="update.php?id='.$row['id'].'">Atualizar</a>';
                            echo ' ';
                            echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Excluir</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        MySQL::desconectar();
                        ?>
                    </tbody>
                </table>

                <p>
                    <a href="logout.php" class="btn btn-danger">SAIR</a>
                </p>

            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
$(document).ready( function () {
    $('#minhaTabela').DataTable();
} );

    </script>
</body>

</html>
