<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('vendor/autoload.php');
use Setra\Models\ConnectionManager;
use Setra\Models\Client;
use Setra\Helpers\Date;

$conn = ConnectionManager::get();
Client::setConnection($conn);

$clients = new Client();
$clients = $clients->findAll();

?>

<html>
<head>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
<div class="container">
    <a href="manage.php" class="btn btn-primary">Inserir</a>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>
                    Nome
                </th>
                <th>
                    Sobrenome
                </th>
                <th>
                    Data de Nascimento
                </th>
            </tr>
        </thead>        
        <tbody>
            <?php
                foreach($clients as $client){
            ?>

            <tr>
                <td><?php echo $client['first_name']; ?></td>
                <td><?php echo $client['last_name']; ?></td>
                <td><?php echo Date::formatToView($client['birthday']); ?></td>
                <td><a href="manage.php?id=<?php echo $client['id']; ?>">Editar</a></td>
                <td><a href="delete.php?id=<?php echo $client['id']; ?>">Delete</a></td>
            </tr>

            <?php                
                }   
            ?>
        </tbody>
    </table>
</div>
</body>
<script src="assets/js/bootstrap.min.js"></script>
</html>