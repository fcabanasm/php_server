<html>
<head>
<title>CSVaMySql</title>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
</head>
<body>
<br>
<div class="container">
  <div class="row">
    <h1> SCRIPT CSV a Mysql </h1>
    <p> PHP para importar archivos CSV a MySql</p>
    <div class="col-md-12" style="border: 1px solid black">
    <div class="col-md-12">
      <h4>Esta sección es solo informativa, despliega los campos de las tablas en la BBDD big_table.</h4>
    </div>
    <div class="col-md-4">
      <?php
      $cons= mysqli_connect("localhost", "root","europa1935","big_data") or die(mysql_error());
      $showtables = "show tables";
      $showt = mysqli_query($cons,$showtables);
      if (!$showt) {
        die('Could not load data from file into table: ' .mysqli_error($cons));
      }
      echo "<table class='table table-bordered'>
            <tr>
              <th>Tablas en big_data:</th>
            </tr>";
      while ($row = $showt->fetch_assoc()) {

            $tibd = $row['Tables_in_big_data'];
            echo "<tr> <td>";
            echo "<a href='".$_SERVER['PHP_SELF']."?schema=".$tibd."'>$tibd</a>";
            echo "</td> </tr>";
        }
        echo "</table>";
     ?>
    </div>

    <div class="col-md-8" id="schema">
      <h4>Schema:</h4>
      <?php
      if(isset($_GET['schema'])){
          $tabla = $_GET['schema'];

          $cons= mysqli_connect("localhost", "root","europa1935","big_data") or die(mysql_error());
          $showsql = "show columns from $tabla;";
          $show = mysqli_query($cons,$showsql);
          if (!$show) {
            die('Could not load data from file into table: ' .mysqli_error($cons));
          }
          echo "<table class='table table-bordered'>
        <tr>
          <th>Campos de <u>$tabla</u></th>
          <th>Tipo</u></th>
        </tr>";
          while ($row = $show->fetch_assoc()) {
              if ($row['Field'] != "id") {
                echo "<tr>
                <td>";
                echo "<b>".$row['Field']."</b>";
                echo "</td>";
                echo "<td>".$row['Type']."</td>";
                echo "</tr>";
              }
            }
      echo "</table>";
        }
      ?>
    </div>
    </div>

    </br>
    <div class="col-md-12">

    </div>
    <div class="col-md-12" style="margin-top:2em">
      <table class='table table-hover table-bordered'>
        <thead>
          <tr>
            <th>Nombre del archivo: </th>
            <th>Columnas (primera linea del archivo)</th>
          </tr>
        </thead>
        <tbody>
<div class="col-md-12">
  <h4>Tabla de Directorio: <b>/home/statdata/php_server/csv/</b> </h4>
  <?php
    $directorio = opendir("/home/statdata/php_server/csv/");
    #$directorio = opendir("/home/fcabanasm/Escritorio/dev/php_server/csv/");
    while ($archivo = readdir($directorio)) {
      if ($archivo != "." && $archivo != "..") {
            echo "<tr><td><a style='font-size: 20px;' href='/csv/$archivo' target='_blank'>".$archivo."</a></td>";
              $file = new SplFileObject("csv/".$archivo);
              echo "<td>".$file->fgets()."</td>";
              $file = null;
            echo "</tr>";
  }
        }
  ?>
</div>

    </tbody>
    </table>
    </div>
    <div class="col-md-12" style="border: 1px dashed black">
      <h4>ATENCIÓN: Fijarse que las columnas esten creadas en la tabla y tengan el mismo nombre. <a href="#schema">(Ver Schema)</a> </h4>

    <form class="form-horizontal"action="csv2sql.php" method="post">
      <div class="col-md-2">
        <div class="form-group">
              <label for="csvfile" class="control-label ">Nombre del archivo</label>
          <div class="">
              <input type="name" class="form-control" name="csv" id="csv" value="test.csv">
          </div>
          ej.: test.csv
          </div>
      </div>
      <div class="col-md-10">
        <div class="form-group">
              <label for="table" class="control-label ">Columnas</label>
          <div class="">
              <input type="text" class="form-control" name="cols" id="cols" value="@,@,nombres,rut,rut_verificador,@,@,nacionalidad,nacimiento,sexo,civil,direccion,telf1,email,lvl_estudio,estudios,titulo">
          </div>
          ojo: @ para ignorar columna
        </div>
      </div>

      <div class="col-md-12">
        <div class="col-md-6">
      <div class="form-group">
            <label for="table" class="control-label ">Header (cabezera)</label>
        <div class="">
          <input type="radio" name="header" id="header" value="si" checked="true"> Si<br>
          <input type="radio" name="header" id="header" value="no"> No<br>
        </div>
      </div>
      </div>
      <div class="col-md-6">
      <div class="form-group">
            <label for="table" class="control-label ">Separador</label>
        <div class="">
          <input type="radio" name="separador" id="separador" value="," checked="true"> , (coma)<br>
          <input type="radio" name="separador" id="separador" value=";"> ; (punto y coma)<br>
        </div>
      </div>
      </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
              <label for="password" class="control-label ">Contraseña</label>
      		<div class="">
              <input type="password" class="form-control" name="password" id="password" placeholder="" value="europa1935" required="true">
      		</div>
          </div>
        <div class="form-group">
            <!--<label for="mysql" class="control-label ">Host</label>-->
    		<div class="">
            <input type="hidden" class="form-control" name="mysql" id="mysql" placeholder="" value="localhost" required="true">
    		</div>
        </div>
    	<div class="form-group">
            <!--<label for="username" class="control-label ">Usuario</label>-->
    		<div class="">
            <input type="hidden" class="form-control" name="username" id="username" placeholder="" value="root" required="true">
    		</div>
        </div>

      </div>
      <div class="col-md-6">
        <div class="form-group">
              <label for="table" class="control-label ">Tabla (si no existe la creará)</label>
      		<div class="">
              <input type="name" class="form-control" name="table" id="table" placeholder="personas" required="true">
      		</div>
          </div>
        <div class="form-group">
              <!--<label for="db" class="control-label ">Base de datos</label>-->
      		<div class="">
              <input type="hidden" class="form-control" name="db" id="db" placeholder="" value="big_data" required="true">
      		</div>
          </div>

</div>
  <div class="form-group">
  <label for="login" class="control-label "></label>
    <div class="">
    <button type="submit" class="btn btn-primary">Subir</button>
  </div>
  </div>
    </form>
    </div>
    </div>
  </div>
</div>
</body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['username'])&&isset($_POST['mysql'])&&isset($_POST['db'])&&isset($_POST['username']))
{
$sqlname=$_POST['mysql'];
$username=$_POST['username'];
$table=$_POST['table'];
if(isset($_POST['password']))
{
$password=$_POST['password'];
}
else
{
$password= '';
}

$db=$_POST['db'];
$file=$_POST['csv'];
$cons= mysqli_connect("$sqlname", "$username","$password","$db") or die(mysql_error());

//If the fields in CSV are not seperated by comma(,)  replace comma(,) in the below query with that  delimiting character
//If each tuple in CSV are not seperated by new line.  replace \n in the below query  the delimiting character which seperates two tuples in csv
// for more information about the query http://dev.mysql.com/doc/refman/5.1/en/load-data.html
$cols=$_POST['cols'];
$header=$_POST['header'];
$separador=$_POST['separador'];

$cols = preg_replace('/\s+/', '', $cols);
$cols = preg_replace('/[;]/', ',', $cols);
$head = '('.$cols.')';
$head = str_ireplace("@","@q",$head);

$cols = preg_replace('/\s+/', '', $cols);
$cols = preg_replace('/[;]/', ',', $cols);
$cols = str_ireplace("@,","", $cols);
$cols = str_ireplace(",@","", $cols);
$cols = explode(",",$cols);
$fortable = "";
foreach ($cols as &$valor) {
  $fortable = $fortable.",".$valor." varchar(100)";
};
$ctable = "create table if not exists $table(id int not null auto_increment primary key $fortable)";
$cretab = mysqli_query($cons,$ctable);
if (!$cretab) {
	die('Could not load data from file into table: ' .mysqli_error($cons));
}
$result1=mysqli_query($cons,"select count(*) count from $table");
$r1=mysqli_fetch_array($result1);
$count1=(int)$r1['count'];

$sql2 = "LOAD DATA LOCAL INFILE 'csv/".$file. "'
      INTO TABLE `".$table."`
      CHARACTER SET UTF8
      FIELDS TERMINATED by '$separador'
      ENCLOSED BY '\"'".
      ($header == "si" ? '
        IGNORE 1 LINES': '')."
      ".$head."
";
#rut;rut_verificador;nombres;a_paterno;a_materno;direccion;comuna;region;cod_telf;telf1;@;@;@;@;@
#rut
#apellido
#comuna
#email
#nacionalidad
#nacimiento
#sexo
#civil
#direccion
#telf1
#nivel_estudio
#estudios
#titulo

$loaddata = mysqli_query($cons,$sql2);
if (!$loaddata) {
	die('Could not load data from file into table: ' .mysqli_error($cons));
}
$result2=mysqli_query($cons,"select count(*) count from $table");
$r2=mysqli_fetch_array($result2);
$count2=(int)$r2['count'];

$count=$count2-$count1;
if($count>0)
  echo "¡Bien!";
  echo "<b> en total $count registro/s fueron agregados a la tabla $table </b> ";
}
else{
echo "Completar los campos obligatorios.";
}

echo "<br><br><br><br>";
?>
<h4>Para administrar MySql (tablas, columnas, etc.) se puede hacer con <a href="https://www.mysql.com/products/workbench/">MySql Workbench</a></h4>
<h5>Instrucciones:</h5>
<ul>
  <li>0- Copiar fichero CSV (este no debe tener signos inválidos (como por ej: ?) ) al directorio.</li>
  <li>1- Ver el Schema de la tabla, para comprobar sus columnas.</li>
  <li>2- Copiar el nombre completo del archivo mostrado en la Tabla.</li>
  <li>3- Copiar las columnas y determinar cuales quiero ignorar (lo que se escriba en ese campo no debe terminar en coma (,) ) </li>
  <li>4- Verificar si tiene o no Headers, esto se puede comprobar en la tabla de arriba. Si muestra un registro, quiere decir que no tiene HEADER.</li>
  <li>5- Verificar la separación, esto se puede comprobar en la tabla de arriba, depende de la separación de las columnas (puede ser coma o punto y coma)</li>
  <li>4- Escribir Tabla (Si no existe, creara una tabla con las columnas escritas en el paso 3, de lo contrario agregara registros en la tabla existente).</li>
</ul>
</html>
