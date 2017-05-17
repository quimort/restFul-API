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
        $connection = new ConexionBD();
        $app->response()->header("Content-Type", "application/json");
        $json=$app->request()->getBody();
        $data = json_decode($json, true);
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['Password'];
        $to_post=array("respuesta"=>array());
        $result="";
        if($connection->validarnombre($name)!=0){
            $to_post["respuesta"]["name"]="name already exist";
        }
        if($connection->validaremail($email)!=0){
                $to_post["respuesta"]["mail"]="email already exist";
            }
        else{
                $result = $connection->insertuser($name,$password,$email);
                $to_post["respuesta"]["apiyek"]=$result;
        }

        echo json_encode($to_post);
});
$app->post('/resface',function ()use($app){
    $connection = new ConexionBD();
    $app->response()->header("Content-Type", "application/json");
    $json=$app->request()->getBody();
    $data = json_decode($json, true);
    $id=$data['ID'];
    $token=$data['Token'];
    $result=$connection->insertviafacebook($id,$token);
    $to_result = array("result"=>$result);
    echo json_encode($to_result);
});
$app->post('/login',function () use($app){
    $app->response()->header("Content-Type", "application/json");
    $json=$app->request()->getBody();
    $data = json_decode($json, true);
    $name=$data['name'];
    $password=$data['password'];
    $conn=new ConexionBD();
    $result = $conn->loginuser($name,$password);
    $to_result=array("respuesta"=>$result);
    echo json_encode($to_result);
});
$app->run();

?>