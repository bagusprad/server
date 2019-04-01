<?php
// Check for the path elements
// Turn off error reporting
error_reporting(0);
// Report runtime errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
// Report all errors
error_reporting(E_ALL);
// Same as error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);
// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
$method = $_SERVER['REQUEST_METHOD'];
//site.com/data -> /data
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
// echo "request===".$request;
// echo "|||";
// echo "method===".$method;
// echo "|||";
 
// $input = json_decode(file_get_contents('php://input'),true);
// $input = file_get_contents('php://input');
// var_dump($input);die*();
$link = mysqli_connect('localhost', 'id8951683_bagustugas3', 'bagus123', 'id8951683_cacastie');
// $link = mysqli_connect('localhost', 'root', '', 'posyandu');
mysqli_set_charset($link,'utf8');
 
$params = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
// echo "data===".$data;
// echo "|||";
$id = array_shift($request);
// echo "id===".$id;
// echo "|||";
if ($params == 'data') {
	switch ($method) {
		case 'GET':
	    {
		    if (empty($id_penjual))
		    {
			    $sql = "select * from penjual"; 
			    // echo "select * from posyandu ";break;
		    }
		    else
		    {
		         $sql = "select * from penjual where id_penjual='$id_penjual'";
		         // echo "select * from posyandu where id='$id'";break;
		    }
	    }
	}
 
	$result = mysqli_query($link,$sql);
 
	if (!$result) {
		http_response_code(404);
		die(mysqli_error());
	}
	if ($method == 'GET') {
		$hasil=array();
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$hasil[]=$row;
		} 
		$resp = array('status' => true, 'message' => 'Data show succes', 'data' => $hasil);
	} else {
		$resp = array('status' => false, 'message' => 'Access Denied');
	}
}elseif ($method == 'POST') {
	$data = $_POST;
    if ($params == "create") {
    	$nama_warung=$data["nama_warung"];
	    $nama_penjual=$data["nama_penjual"];
		$querycek = "SELECT * FROM penjual WHERE nama_warung like '$nama_warung'";
		$result=mysqli_query($link,$querycek);
		if (mysqli_num_rows($result) == 0)
		{
			$query = "INSERT INTO penjual (
			nama_warung,
			nama_penjual)
			VALUES (				
			'$nama_warung',
			'$nama_penjual')";
			
			mysqli_query($link,$query);
			$resp = array('status' => true, 'message' => "warung $nama_warung ditambahkan");
		} else { 
			$resp = array('status' => false, 'message' => 'nama warung sudah terdaftar');
		}
    } elseif ($params == "update") {
    	$id_penjual=$data["id_penjual"];
	    $nama_warung=$data["nama_warung"];
	    $nama_penjual=$data["nama_penjual"];
	    $query = "UPDATE penjual 
	    	SET nama_warung = '$nama_warung',
			nama_penjual = '$nama_penjual'
			WHERE id_penjual =$id_penjual";
	    if (mysqli_query($link,$query)) {
	    	
			$resp = array('status' => true, 'message' => "warung $nama_warung diupdate");
	    } else {
	    	$resp = array('status' => false, 'message' => 'proses update gagal');
	    }
    } elseif ($params == "delete") {
    	$id_penjual=$data["id_penjual"];
	    $query = "DELETE FROM penjual WHERE id_penjual = $id_penjual";
	    if (mysqli_query($link,$query)) {
	    	
		    $resp = array('status' => true, 'message' => 'data berhasil dihapus');
	    } else {
	    	$resp = array('status' => false, 'message' => 'data gagal dihapus');
	    }
    }    
} else {
	$resp = array('status' => false, 'message' => 'data gagal');
}
echo json_encode($resp);
mysqli_close($link);
?>