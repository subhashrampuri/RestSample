<?php 
	header('Content-Type: application/json');
	$req_method = $_SERVER['REQUEST_METHOD'];
	//echo $req_method;
	$response = array();
	switch ($req_method)
	{
		case "GET":
			response(doGET());
			break;
		case "POST":
			response(doPOST());
			break;
		case "PUT":
			response(doPUT());
			break;
		case "DELETE":
			response(doDELETE());
			break;
	}

function doGet()
{
	if(@$_GET['ID'])
	{
		@$ID = $_GET['ID'];
		$where = " WHERE `ID`=".$ID;
	}
	else
	{
		$ID=0;
		$where = "";
	}
	$dbconnect 	= mysqli_connect("localhost","root","","employeedb");
	$query 		= mysqli_query($dbconnect, "Select * from `employee` ".$where);
	//$data 		= mysqli_fetch_assoc($query);
	//print_r($data);
	
	
	while($data = mysqli_fetch_array($query))
	{
		$response[] = array("ID"=>$data['ID'],"Name"=>$data['Name'],"Email"=>$data['Email'],"City"=>$data['City']);
	}
	
	return $response;
}	

function doPOST()
{
	if($_POST)
	{
		
		//return ($_POST['Name'] . " | " . $_POST['Email'] . " | " .$_POST['City']);
		
		$dbconnect 	= mysqli_connect("localhost","root","","employeedb");
		$query 		= mysqli_query($dbconnect, "INSERT INTO `employee` (`Name`, `Email`, `City`) VALUES 
						('".$_POST['Name']."','".$_POST['Email']."','".$_POST['City']."')");
		
		if($query == true)
		{
			$response = array("message"=>"Successfully inserted");
		}else
		{
			$response = array("message"=>"Failed to insert");
		}
		return $response;
		
	}
}	

function doPUT()
{
	parse_str(file_get_contents('php://input'), $_PUT);
	print_r($_PUT);
	if($_PUT)
	{
		$str = "Update `employee` SET `Name` ='".$_POST['Name']."', `City` ='".$_POST['City']."' WHERE `ID` = '".$_GET['ID']."' ";
		echo $str;
	/*	$dbconnect 	= mysqli_connect("localhost","root","","employeedb");
		$query 		= mysqli_query($dbconnect, "Update `employee` SET `Name` ='".$_PUT['Name']."', `City` ='".$_PUT['City']."' WHERE `ID` = '".$_GET['ID']."' ");
									 
		if($query == true)
		{
			$response = array("message"=>"Successfully Updated");
		}else
		{
			$response = array("message"=>"Failed to Update");
		}
	
		return $response;
	*/	
	}		
	
}	

function doDELETE()
{
	if($_GET['ID'])
	{
		$dbconnect 	= mysqli_connect("localhost","root","","employeedb");
		$query 		= mysqli_query($dbconnect, "DELETE from `employee` WHERE `ID` ='".$_GET['ID']."'");
		if($query == true)
		{
			$response = array("message"=>"Successfully deleted");
		}else
		{
			$response = array("message"=>"Failed to delete");
		}
		return $response;
	}
}	
//output 
function response($response)
{
	//echo json_decode($response, true);
	echo json_encode(array("status" => "200", "data"=>$response));
	//echo array_map(array("status" => "200", "data"=>$response));
	
}

?>