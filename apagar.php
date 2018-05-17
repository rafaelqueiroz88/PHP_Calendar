<?php
$id = $_GET['id'];
session_start();

include 'database.php';
include 'processar.php';

use DatabaseConnector\Database;
use Processo\Eventos as Evento;
new Database;

$db = Database::GetConnection();

$d        = isset( $_GET['d'] ) ? $_GET['d'] : date( "d" );
$a        = isset( $_GET['a'] ) ? $_GET['a'] : date( "Y" );
$m        = isset( $_GET['mes'] ) ? $_GET['mes'] : date( "m" );

new Evento( $db );
Evento::ApagarEvento( $id );
$_SESSION['evento_removido'] = "Evento removido com sucesso!";
header("location:eventos.php?d=$d&m=$m&a=$a");
?>