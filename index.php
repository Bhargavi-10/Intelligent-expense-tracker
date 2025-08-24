<?php
//connection to  the database

$host  = "localhost";
$username = "root";
$password = null;
try{
    $conn = new PDO("mysql:host=$host;dbname=student",$username,$password);
    $conn->SetAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    //echo"\nconnection done";
}
catch(PDOException $err){
   // echo"connection could not be established". $err->getMessage();
}
echo "<br>";
//echo "<p style='font-size = 40px;'>successfully connected to database , now let us manage your expenses<p>";
$amt = NULL;
$category = NULL;
$date  = NULL;

//adding(inserting) values to database

if(isset($_POST['add'])){
  if(isset($_POST['amount'])){
    $amt = $_POST['amount'];
    if($amt<0){
        echo "<br>";
        echo " invalid amount ";
    }
 }
  if(isset($_POST['category'])){
    $category = $_POST['category'];
 }
  if(isset($_POST['date'])){
    $date = $_POST['date'];
  }
$insert  = $conn->prepare("INSERT INTO expenses(day, category , amount) VALUES('$date' , '$category' , $amt)");
$insert->execute();
echo "<br>";
echo "<p style='font-size : 40px; text-align : center'>Data Entered Successfully<p>";
}

//VIEW ALL EXPENSES TILL DATE

if(isset($_POST['view'])){

    $view = $conn->prepare("SELECT * FROM expenses");
    $view->execute();
    $views = $view->fetchAll();
    echo "<p style = 'color : navy ;font-size : 40px ; text-align : center'>YOUR EXPENSES : <P>";
    echo"<table border = 1 style = 'text-align : center'>";
    echo "<tr><th style = 'font-size : 30px'>DATE</th><th style = 'font-size : 30px'>CATEGORY</th><th style = 'font-size : 30px'>AMOUNT</th></tr>";
    foreach($views as $val){
        echo "<tr>";
        echo "<td style = 'font-size : 20px'>".$val['day']."</td>";
        echo "<td style = 'font-size : 20px'>".$val['category']."</td>";
        echo "<td style = 'font-size : 20px'>".$val['amount']."</td>";
        echo "</tr>";
    }
    echo "</table>";
}

//CATEGORISE EXPENSES

if(isset($_POST['group'])){
  if(isset($_POST['categ'])){
    $categorise = $_POST['categ'];
    $selected_details= $conn->prepare("SELECT * FROM expenses WHERE category = '$categorise'");
    $selected_details->execute();
    $details=$selected_details->fetchAll();
    echo "<p style = 'font-size : 30px;color : navy; text-align : center'>YOUR EXPENSES : ";
    echo "<br>";
    echo "<br>";
    echo "<table border  =1  style = 'text-align : center'";
    echo "<tr><th style = 'font-size : 30px'>DATE</th><th style = 'font-size : 30px'>CATEGORY</th><th style = 'font-size : 30px'>AMOUNT SPENT</th></tr>";
   foreach($details as $val){
    echo "<tr>";
    echo "<td style = 'font-size : 20px'>".$val['day']."</td>";
    echo "<td style = 'font-size : 20px'>".$val['category']."</td>";
    echo "<td style = 'font-size : 20px'>".$val['amount']."</td>";
    echo "</tr>";
   }
   echo "</table>";
 }
}

//CATEGORISE BASED ON PARTICULAR DATE
if(isset($_POST['go'])){
 if(isset($_POST['days'])){
    $days = $_POST['days'];
    $viewday = $conn->prepare("SELECT * FROM expenses WHERE day = '$days' ");
    $viewday->execute();
    $days = $viewday->fetchALL();
    echo "<p style = 'font-size : 30px; color : navy '>YOUR EXPENSES : ";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<table border = 1 style = 'text-align : center'>";
    echo "<tr><th>DATE</th><th>CATEGORY</th><th>AMOUNT_SPENT</th></tr>";
    foreach($days as $val){
      echo "<tr>";
      echo "<td style = 'font-size : 20px'>".$val['day']."</td>";
      echo "<td style = 'font-size : 20px'>".$val['category']."</td>";
      echo "<td style = 'font-size : 20px'>".$val['amount']."</td>";
      echo "</tr>";
    }
    echo "</table>";
  }
}

//TOTAL EXPENSES TILL DATE

if(isset($_POST['yes'])){
    $sum = 0;
    $yes  = $conn->prepare("SELECT * FROM expenses");
    $yes->execute();
    $ans = $yes->fetchAll();
    foreach($ans as $val){
       $sum = $sum  + $val['amount'];
    }
    echo "<br>";
    echo "<p style = 'font-size : 30px ; color : navy ; text-align : center'>TOTAL EXPENSES";
    echo "<p style = 'font-size : 20px; text-align : center'>Total Expenses Till Today  : Rs.".$sum;
}
if(isset($_POST['no'])){
    return;
}

//calculation of total expenses according to each category
if(isset($_POST['category_tot'])){
if(isset($_POST['total'])){
    $vsum = 0;
    $tot = $_POST['total'];
    $viewtot = $conn->prepare("SELECT * FROM expenses WHERE category = '$tot'");
    $viewtot->execute();
    $vtot = $viewtot->fetchALL();
    foreach($vtot as $res){
       $vsum = $vsum + $res['amount'];
    }
    echo "<br>";
    echo "<p style = 'font-size : 30px ; color : navy ; text-align : center'>TOTAL EXPENSES";
    echo "<p style = 'font-size : 20px ;text-align : center'>total expense at ".$tot." is : Rs.".$vsum;
}
}
 //total expense on the basis of date 
if(isset($_POST['viewday'])){

    if(isset($_POST['day'])){
    $dsum = 0;
    $dtot = $_POST['day'];
    $dtotal = $conn->prepare("SELECT * FROM expenses WHERE day = '$dtot'");
    $dtotal->execute();
    $dview = $dtotal->fetchAll();
    foreach($dview as $val){
        $dsum = $dsum + $val['amount'];
    }
    echo "<br>";
    echo "<p style = 'font-size : 30px ; color : navy  ; text-align : center'>TOTAL EXPENSES";
    echo "<br>";
    echo "<p style = 'font-size : 20px; text-align : center'>total expense on ".$dtot." is : ".$dsum;
}
}


?>
