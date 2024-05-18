<?php

$server="db";
$user="root";
$password="test";
$database="notas_db";
$port="3306";

try{
	$conn=new PDO("mysql:host=$server;port=$port;dbname=$database", $user, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $error){
	echo 'PDOException: ',  $error->getMessage(), "\n";
}

function getUserData($conn) {
	$results = [];
	try {
		$sql="SELECT * FROM users WHERE email=:email";
		$records = $conn->prepare($sql);
		$records->bindParam(":email", $_POST['email']);
		$records->execute();
		$results = $records->fetch(PDO::FETCH_ASSOC);

		return $results;
	} catch (Exception $e) {
		echo 'Excepción: ',  $e->getMessage(), "\n";
	}
}
function getUserNotes($user, $conn) {
	try {
		$sql="SELECT * FROM notes WHERE email=:id";
		$records = $conn->prepare($sql);
		$records->bindParam(":id", $user['email']);
		$records->execute();
		$results = $records->fetchAll(PDO::FETCH_ASSOC);

		return $results;
	} catch (Exception $e) {
		echo 'Excepción: ',  $e->getMessage(), "\n";
	}
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE" && isset($_GET['note_id'])){
	deleteNote($conn, $_GET['note_id']);
	$_GET['note_id'] = null;
}
function deleteNote($conn, $noteId){
	$sql = "DELETE FROM notes WHERE id = :note_id";
	try {
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':note_id', $noteId);	
		$stmt->execute();

	} catch (Exception $e) {
		echo 'Excepción: ', $e->getMessage(), "\n";
	}
}

?>