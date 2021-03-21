<!Doctype HTML>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
</head>

<body>
<div>
<nav class="navbar">
        <a class="navbar-brand" href="index.php" class="logo"><img src="./images/banklogo.jpg" width="5%" height="50px" alt="LOGO"/></a>
		<span class="title">TSF Bank</span>
		
    </nav>
</div>
<br>
<br>
    <form method="post"> 
        <button class="Cbutton" name="View_customer"
                value="View_customer"> View Customers</button> 
        <button class="Tbutton" name="Transaction_Details" value="Transaction_Details"> Transaction Details</button>
            </form>
	    
		<button class="open-button" onclick="Transfer()">Transfer Money</button>
		<br><br><br>

<div class="form-popup" id="myForm">
  <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-container">
    <h1 style="background-color:#c2c2a3;">Transfer Money</h1>
<table>
<tr>
    <td><label <b>Transfer from</b></label></td>
    <td><input type="int" placeholder="" name="Sender" required></td>
</tr>
<tr>
    <td><label ><b>Transfer To</b></label></td>
    <td><input type="int" placeholder="" name="Receiver" required></td>
</tr>
<tr>
	<td><label ><b>Amount</b></label></td>
    <td><input type="bigint" placeholder="Enter Amount" name="Amount" required></td>
</tr>
<br>
<tr><td></td></tr>
<tr>
	<td><button type="button" class="btn cancel" onclick="closeForm()">Close</button></td>
    <td><button type="submit" class="btn" style= width: 50px;" >Submit</button></td>
</tr>
	</table>
  </form>
</div>

<script>
function Transfer() {
  document.getElementById("myForm").style.display = "block";
}
function closeForm() {
  document.getElementById("myForm").style.display = "none";
}

</script>
<div>
<footer> 
        <p style="color:white;">Join us on</p>
        <div>
            <b>|</b>&nbsp;<a href="#">GITHUB</a>&nbsp;<b>|</b>&nbsp;
            <a href="#">LINKEDIN</a>&nbsp;<b>|</b>&nbsp;
            <a href="#">FACEBOOK</a>&nbsp;<b>|</b>&nbsp;
            <a href="#">TWITTER</a>&nbsp;<b>|</b>&nbsp;
        </div>
        <p style="color:white;" >&copy;  All rights reserved.</p>
    </footer>
	</div>
<?php
	require ("connection.php");
    if (isset($_POST['Sender'])&&isset($_POST['Receiver'])&&isset($_POST['Amount'])){
	$Sender=$_POST['Sender'];
	$Receiver=$_POST['Receiver'];
	$Amount=$_POST['Amount'];
	$sql="select Current_Balance from Customer where Account_no=".$_POST['Sender'];
	$res=mysqli_query($conn,$sql);
	$arr = mysqli_fetch_row($res);
	
	$sql="select Current_Balance from Customer where Account_no=".$_POST['Receiver'];
	$res=mysqli_query($conn,$sql);
	$arr1 = mysqli_fetch_row($res);
	
	
	if($arr !=NULL && $arr1 !=NULL ){
	if ($arr[0] < $Amount){
		echo "<script>alert('Insufficient Account Balance')</script>";
	}
	else {	
	$sql="Update Customer Set Current_Balance=Current_Balance-".$Amount." where Account_no=".$_POST['Sender'];
	mysqli_query($conn,$sql);
	$sql="Update Customer Set Current_Balance=Current_Balance+".$Amount." where Account_no=".$Receiver;
	if (mysqli_query($conn,$sql)){
		
    $sql="insert into transaction (Debiter_id,Crediter_id,Trans_amount)Values(?,?,?)";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "ddd", $Sender, $Receiver,$Amount);
        mysqli_stmt_execute($stmt);  
    		
	echo "<script>alert('Money Transfer Successfully')</script>";
	}}
	
	}
	}
	else{
		echo "<script>alert(' Money Not transferred Enter details Correctly')</script>";
	}
	}
	
	if($_GET){
    if(isset($_GET['View_customer'])){
        View_customer();
    }
	}
    
	 
	 if(isset($_POST['View_customer'])) { 
	$sql="select Account_no,Name,Current_Balance from Customer "; 	
	$res = mysqli_query($conn, $sql);
	

	echo"<table border='1' class='customer' >";
	echo "<tr><th>Account_no</th><th>Customers</th>
	<th> Balance(INR)</th></tr>";
	
	while ($row=mysqli_fetch_array($res))
	   {
		 echo"<tbody style='overflow-y:scroll; height:20px;'>";  
	     echo"<tr><td>".$row['Account_no']." </td>";
		 echo"<td>".$row['Name']."</td>";
		 echo"<td>".$row['Current_Balance']."</td></tr>";
		 
	}
    echo"</table><br>";
    } 
	if(isset($_POST['Transaction_Details'])) { 
	$sql="select * from Transaction"; 	
	$res = mysqli_query($conn, $sql);
	
	echo"<table border='1' class='customer' >";
	echo "<tr><th>Transaction_No</th><th>Crediter_id</th>
	<th> Debiter_id</th><th>Amount</th></tr>";
	while ($row=mysqli_fetch_array($res))
	   {
	     echo"<tr><td>".$row['T_id']." </td>";
		 echo"<td>".$row['Crediter_id']."</td>";
		 echo"<td>".$row['Debiter_id']."</td>";
		 echo"<td>".$row['Trans_amount']."</td></tr>";
		 
	}
    echo"</table><br>";
    }
        
    ?> 
      


</body>
</html>



