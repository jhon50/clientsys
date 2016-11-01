<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

use Setra\Helpers\Date;
use Setra\Helpers\CheckArray;
use Setra\Models\Client;
use Setra\Models\Error;
use Setra\Models\ConnectionManager;

$conn = ConnectionManager::get();
Client::setConnection($conn);

$clientId = isset($_GET['id']) ? $_GET['id'] : null;
$client = new Client($clientId);

$errors = new Error();

$buttontext = "Criar novo usuário";
$title = "Criar novo cliente";
$firstName = null;
$lastName = null;
$birthday = null;
$phones = [];
if ($clientId) {
    $firstName = $client->getFirstName();
    $lastName = $client->getLastName();
    $birthday = Date::formatToView($client->getBirthday());
    $buttontext = "Salvar";
    $title = "Editar cliente";
    $phones = $client->findPhones($clientId);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client->setFirstName($_POST['firstName']);
    $client->setLastName($_POST['lastName']);
    $client->setBirthday(Date::formatToDb($_POST['birthday']));

    //$client->setPhones($_POST['phones']);

    $phones = $_POST['phones'];
    $client->setPhones($phones);
    $client->save();
    header('Location: /index.php');

}
?>
<!DOCTYPE html>
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
            <label for="firstName">Nome</label>
                <input class="form-control" name="firstName" id="firstName" type="text" placeholder="Nome" value="<?php echo $firstName ?>"/>
            </div>

            <div class="form-group">
            <label for="lastName">Sobrenome</label>
                <input class="form-control" name="lastName" id="lastName" type="text" placeholder="Sobrenome" value="<?php echo $lastName ?>"/>
            </div>

            <div class="form-group">
            <label for="birthday">Data de Nascimento</label>
                <input class="form-control" name="birthday" id="birthday" type="date" placeholder="Data de Nascimento" value="<?php echo $birthday ?>"/>
            </div>

            <!-- begin phones section -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            Número
                        </th>
                        <th>
                            Principal
                        </th>
                        <th>
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $phone = current($phones);
                        do {
                            $i = intval(key($phones));
                    ?>
                        <tr class="clonedInput phone">
                            <td>
                                <input class="form-control phone" name="phones[<?php echo $i; ?>][number]" type="phone" value="<?php echo isset($phone['number']) ? $phone['number'] : "" ?>" placeholder="Telefone"/>
                            </td>
                            <td>                              
                                <select class="main" name="phones[<?php echo $i; ?>][main]">
                                    <option value="0">Não</option>
                                    <!-- Caso seja o principal selecione sim 
                                            Caso esteja criando novo cliente, selecione sim no primeiro telefone -->
                                    <option <?php if($phone['main'] || $phone == null){ ?> selected <?php } ?> value="1">Sim</option>
                                </select> 
                            </td>
                            <td>
                                <button class="clone phone btn btn-primary" id="add-button" type="button">Add</button>
                                <button class="remove phone btn btn-primary" type="button">Del</button>
                            </td>
                        </tr>
                    <?php
                        } while($phone = next($phones));
                    ?>
                </tbody>
            </table>
            <input type="hidden" id="phoneCounter" value="<?php echo count($phones) ?>">

            <!-- begin emails section -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            Email
                        </th>
                        <th>
                            Principal
                        </th>
                        <th>
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $phone = current($phones);
                        do {
                            $i = intval(key($phones));
                    ?>
                        <tr class="clonedInput email">
                            <td>
                                <input class="form-control email" name="emails[<?php echo $i; ?>][number]" type="email" value="<?php echo isset($phone['number']) ? $phone['number'] : "" ?>" placeholder="Email"/>
                            </td>
                            <td>                              
                                <select class="main" name="emails[<?php echo $i; ?>][main]">
                                    <option value="0">Não</option>
                                    <!-- Caso seja o principal selecione sim 
                                            Caso esteja criando novo cliente, selecione sim no primeiro telefone -->
                                    <option <?php if($phone['main'] || $phone == null){ ?> selected <?php } ?> value="1">Sim</option>
                                </select> 
                            </td>
                            <td>
                                <button class="clone email btn btn-primary" id="add-button" type="button">Add</button>
                                <button class="remove email btn btn-primary" type="button">Del</button>
                            </td>
                        </tr>
                    <?php
                        } while($phone = next($phones));
                    ?>
                </tbody>
            </table>
            <input type="hidden" id="emailCounter" value="<?php echo count($phones) ?>">

            <!-- begin addresses section  - plural == addresss para o script funcionar com palavras terminadas em 'S' -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            Endereço
                        </th>
                        <th>
                            Cidade
                        </th>
                        <th>
                            Principal
                        </th>
                        <th>
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $phone = current($phones);
                        do {
                            $i = intval(key($phones));
                    ?>
                        <tr class="clonedInput address">
                            <td>
                                <input class="form-control address" name="addresss[<?php echo $i; ?>][number]" type="email" value="<?php echo isset($phone['number']) ? $phone['number'] : "" ?>" placeholder="Email"/>
                            </td>
                            <td>
                                <input class="form-control city" name="emails[<?php echo $i; ?>][number]" type="email" value="<?php echo isset($phone['number']) ? $phone['number'] : "" ?>" placeholder="Email"/>
                            </td>
                            <td>                              
                                <select class="main" name="addresss[<?php echo $i; ?>][main]">
                                    <option value="0">Não</option>
                                    <!-- Caso seja o principal selecione sim 
                                            Caso esteja criando novo cliente, selecione sim no primeiro telefone -->
                                    <option <?php if($phone['main'] || $phone == null){ ?> selected <?php } ?> value="1">Sim</option>
                                </select> 
                            </td>
                            <td>
                                <button class="clone address btn btn-primary" id="add-button" type="button">Add</button>
                                <button class="remove address btn btn-primary" type="button">Del</button>
                            </td>
                        </tr>
                    <?php
                        } while($phone = next($phones));
                    ?>
                </tbody>
            </table>
            <input type="hidden" id="cityCounter" value="<?php echo count($phones) ?>">




            <?php 
            foreach($errors->getErrors() as $error){
            ?>
            <p class="alert alert-danger"> <?php $error ?> </p>

            <?php   
            }
            ?>

            <div class="form-group">
                <input class="btn btn-primary pull-right" type="submit" value="<?php echo $buttontext ?>"/>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" href="index.php">Voltar</a>
            </div>
        </form>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/cloner.js"></script>
<script>
    cloner("phone"); 
    cloner("email");  
    cloner("address"); 
</script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>