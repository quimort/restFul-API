<?php
include '../include/conexionBD.php';
include '.././libs/vendor/autoload.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$salt='$2a$07$usesomesillystringforsalt$';


$app->get('/list', function () {
    $connection = new ConexionBD();
    $query="SELECT * FROM users";
    $result =$connection->query($query);
    $data="";
    $i=0;
    while($row = mysqli_fetch_array($result)){
         $data[$i][0]=$row['name'];
         $data[$i][1]=$row['email'];
         $i++;
    }
    echo json_encode($data);
});

$app->post('/register',function () use($app){
        $app->response()->header("Content-Type", "application/json");
        $name = $app->request()->post('name');
        $connection = new ConexionBD();
        $email = $app->request()->post('email');
        $password = $app->request()->post('password');
        $result = $connection->insertuser($name,$password,$email);
        echo json_encode($result);
});
$app->run();

?>