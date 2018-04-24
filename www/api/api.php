<?php
  // get the HTTP method, path and body of the request
  require_once('config.php');
  $method = $_SERVER['REQUEST_METHOD'];
  $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
  $input = json_decode(file_get_contents('php://input'),true);
  $input = ($input == NULL ? array() : $input);

  // connect to the mysql database
  //godaddy
  //$link = mysqli_connect('localhost', 'jumpingjacks123', 'Metacaroteno123', 'allathand_db') or die ("No pudo conectarse");
  //local
$link = mysqli_connect(DB_SERVER, DB_USER, DB_PWD, DB_NAME) or die ("No pudo conectarse");
    mysqli_set_charset($link,'utf8');
  // retrieve the table and key from the path
  $table = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
  $key = array_shift($request)+0;

  // escape the columns and values from the input object
  $columns = preg_replace('/[^a-z0-9_]+/i','',array_keys($input));

  $values = array_map(function ($value) use ($link) {
    if ($value===null) return null;
    return mysqli_real_escape_string($link,(string)$value);
  },array_values($input));

  // build the SET part of the SQL command
  $set = '';
  for ($i=0;$i<count($columns);$i++) {
    $set.=($i>0?',':'').'`'.$columns[$i].'`=';
    $set.=($values[$i]===null?'NULL':'"'.$values[$i].'"');
  }

  $data=Array();
  for($i=0;$i<count($columns);$i++){
    $data[$columns[$i]] = $values[$i];
  }

  if($method==='POST'&& $table==='entradas_salidas'){
    //echo 'estoy insertando una entrada/salida'
    echo json_encode($data);
    echo $data['tipo'];
    $tipo=$data['tipo'];
    $id_producto= $data['id_producto'];
    $existencia=$data['existencia'];

  if ($data['tipo']==='salida'){

      $sql = "update `productos` set existencia = existencia-$existencia where id=$id_producto";
        $result=mysqli_query($link, $sql);

    }else if ($data['tipo']==='entrada'){
      $sql = "update `producto` set $set where id=$key";
    }
  }
  // create SQL based on HTTP method
  switch ($method) {
    case 'GET':
      $sql = "select * from `$table`".($key?" WHERE id=$key":''); break;
    case 'PUT':
      $sql = "update `$table` set $set where id=$key"; break;
    case 'POST':
      $sql = "insert into `$table` set $set"; break;
    case 'DELETE':
      $sql = "delete from `$table` where id=$key"; break;
  }
  if($table==='entradas_salidas'&& $method==='GET'){
    $sql = "SELECT entradas_salidas.`id`, entradas_salidas.`id_productos`,
          productos.nombre AS 'nombre_del_producto',entradas_salidas.`tipo`,
          entradas_salidas.`hora`, entradas_salidas.`cantidad` FROM `entradas_salidas`
          JOIN productos ON entradas_salidas.id_productos=productos.id";
  }

  // excecute SQL statement
  $result = mysqli_query($link,$sql);


  // die if SQL statement failed
  if (!$result) {
    http_response_code(404);
    die(mysqli_error($link));
  }

  // print results, insert id or affected row count
  if ($method == 'GET') {
    if (!$key) echo '[';
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
      echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }
    if (!$key) echo ']';
  } elseif ($method == 'POST') {
    echo mysqli_insert_id($link);
  } else {
    echo mysqli_affected_rows($link);
  }

  // close mysql connection
  mysqli_close($link);
