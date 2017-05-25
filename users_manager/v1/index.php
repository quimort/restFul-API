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
$app->get('/rute/:id',function($id) use ($app){
    header('Content-Type: application/json');

    $rutid=$id;
    $conn = new ConexionBD();
//    die(var_dump($resultado));
//    die(json_encode(var_dump($resultado)));

    $resultado=$conn->getspecificrute($rutid);

    $rows = array();
    while ($row = mysqli_fetch_assoc($resultado))
    {
        array_push($rows, $row);
//        echo(json_encode($row));
    }
    echo (json_encode($rows));

//    die (var_dump(($rows)));
//
//
//    foreach($resultado as $valor)
//    {
//        $rute[] = array(
//            "pointid"=>$valor['pointid'],
//            "nombre"=>$valor['pointname'],
//            "longitude"=>$valor['pointlongitude'],
//            "latitude"=>$valor['pointlatitude']
//        );
//        die($rute);
//        echo (json_encode($rute, JSON_FORCE_OBJECT));
//
////        echo(var_dump($valor));
//    }
////        die (json_encode($rute, JSON_FORCE_OBJECT));

//    $rute = array();
//    while($r = mysqli_fetch_assoc($resultado)){
//        $rute[] = $r;
//        echo(json_encode($r));
////        $rute[] = array(
////            "pointid"=>$result['pointid'],
////            "nombre"=>$result['pointname'],
////            "longitude"=>$result['pointlongitude'],
////            "latitude"=>$result['pointlatitude']
////        );
//    }
//    echo (json_encode($rute));


});
$app->run();
