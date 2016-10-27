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

$clientId = isset($_GET['id']) ? $_GET['id'] : null; 
$client = new Client($clientId);

$buttontext = "Criar novo usuário";
$title = "Criar novo cliente";
$firstName = null;
$lastName = null;
$birthday = null;

if ($clientId)
{
    $firstName = $client->getFirstName();
    $lastName = $client->getLastName();
    $birthday = Date::formatToView($client->getBirthday());
    $buttontext = "Salvar";
    $title = "Editar cliente";
    
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{

    $client->setFirstName($_POST['firstName']);
    $client->setLastName($_POST['lastName']);
    $client->setBirthday(Date::formatToDb($_POST['birthday']));
    $client->setPhones($_POST['phone']);
    $client->save();
    header('Location: /index.php');
    
}
?>

<html>
<head>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
<div class="container">
    <h1><?php echo $title ?></h1>
    <br>     
    <div class="col-md-6 col-md-offset-1" id="wrapper">
        <form action="manage.php/?id=<?php echo $clientId ?>" method="post">

            <div class="form-group">
                <input class="form-control" name="firstName" type="text" placeholder="Nome" value="<?php echo $firstName ?>"/>
            </div>

            <div class="form-group">
                <input class="form-control" name="lastName" type="text" placeholder="Sobrenome" value="<?php echo $lastName ?>"/>
            </div>

            <div class="form-group">
                <input class="form-control" name="birthday" type="date" placeholder="Data de Nascimento" value="<?php echo $birthday ?>"/>
            </div>

            <div class="form-group clonedInput" id="clonedInput1"> 
                <div class="row">
                    <div class="col-md-8">
                        <input class="form-control" name="phone[]" type="phone" placeholder="Telefone" value=""/>    
                    </div>
                    <div class="col-md-1">
                        <button class="clone btn btn-primary" id="add-button" type="button">Add</button>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <button class="remove btn btn-primary" type="button">Del</button>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <input class="btn btn-primary pull-right" type="submit" value="<?php echo $buttontext ?>"/>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" href="index.php">Voltar</a>
            </div>
        </form>
    </div>
</div>
</body>
<script src="assets/js/jquery.min.js"></script>
<script>
function clone(){
    $(this).parents(".clonedInput").clone()
        .insertAfter("#clonedInput1")
        .on('click', 'button.clone', clone)
        .on('click', 'button.remove', remove);
}
function remove(){
    $(this).parents(".clonedInput").remove();
}
$("button.clone").on("click", clone);

$("button.remove").on("click", remove);
</script>
<script src="assets/js/bootstrap.min.js"></script>
</html>