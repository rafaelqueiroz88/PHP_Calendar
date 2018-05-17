<?php
session_start();
include 'database.php';
include 'processar.php';
use DatabaseConnector\Database;
use Processo\Eventos as Evento;
new Database;
$db = Database::GetConnection();

new Evento( $db );

$_SESSION['user_id']    = 1;
$_SESSION['user_name']  = "Rafael";
$_SESSION['atual']      = isset( $_GET['ano'] ) ? $_GET['ano'] : date( "Y" );
$_SESSION['anterior']   = $_SESSION['atual'] - 1;
$_SESSION['proximo']    = $_SESSION['atual'] + 1;
$_SESSION['mes']        = isset( $_GET['mes'] ) ? $_GET['mes'] : date( "m" );
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendário</title>
    <link rel="stylesheet" href="estilo.css" />
  </head>
  <body>
    <br />
    <nav>
      <center>
        <table>
          <tr>
            <td>
              <?php
              echo '<a href="?ano=' . $_SESSION['anterior'] . '" class="botao-navegar" >';
              echo 'Voltar para ' . $_SESSION['anterior'];
              echo '</a>';
              ?>
            </td>
            <td>
              <?php
              echo '<a href="?ano=' . $_SESSION['proximo'] . '" class="botao-navegar" >';
              echo 'Avançar para ' . $_SESSION['proximo'];
              echo '</a>';
              ?>
            </td>
          </tr>
        </table>
      </center>
    </nav>

    <br />

    <div class="calendario">
      <?php
      // Contador de anos
      for( $a = $_SESSION['atual']; $a < $_SESSION['proximo']; $a++ ) :
        // Contador de meses
        for( $m = $_SESSION['mes']; $m < $_SESSION['mes'] + 1; $m++ ) :
          /**
           * Tratamento dos meses
           * dm = dia do mes
           * nm = nome do mes
           */
          $dm = 0;
          $nm = "";
          switch( $m ) :
            case 1:
              $dm = 31;
              $nm = "Janeiro";
              break;
            case 2:
              /**
               * Calculando ano bissexto
               */
              if( $_SESSION['atual'] % 4 == 0 ) :
                $dm = 29;
              else :
                $dm = 28;
              endif;
              $nm   = "Fevereiro";
              break;
            case 3:
              $dm = 31;
              $nm = "Março";
              break;
            case 4:
              $dm = 30;
              $nm = "Abril";
              break;
            case 5:
              $dm = 30;
              $nm = "Maio";
              break;
            case 6:
              $dm = 30;
              $nm = "Junho";
              break;
            case 7:
              $dm = 31;
              $nm = "Julho";
              break;
            case 8:
              $dm = 31;
              $nm = "Agosto";
              break;
            case 9:
              $dm = 30;
              $nm = "Setembro";
              break;
            case 10:
              $dm = 31;
              $nm = "Outubro";
              break;
            case 11:
              $dm = 30;
              $nm = "Novembro";
              break;
            case 5:
              $dm = 31;
              $nm = "Dezembro";
              break;
          endswitch;
          echo '<center>';
          echo '<ul>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=1">Janeiro</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=2">Fevereiro</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=3">Março</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=4">Abril</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=5">Maio</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=6">Junho</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=7">Julho</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=8">Agosto</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=9">Setembro</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=10">Outubro</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=11">Novembro</a></li>';
          echo '<li><a class="botao-navegacao" href="index.php?mes=12">Dezembro</a></li>';
          echo '</ul>';
          echo '</center>';
          echo $nm;
          echo '<br />';
          echo '<hr />';
          // Contador de dias
          $s = 1;
          for( $d = 1; $d <= $dm; $d++ ) :
            Evento::ListarEventosPorMes( $a, $m, $d );
          endfor;
        endfor;
      endfor;
      ?>
    </div>
  </body>
</html>