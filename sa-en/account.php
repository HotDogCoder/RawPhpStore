<?php if( isset($_SESSION['id']) )
{ 

?>

<h2 class="title">My Account</h2>
<div class="form">
	<div class="left col60">
	
		<div class="form">
			<form action="dashboard.php?p=account&action=updateProfile" id="accoutinfo" method="post">
				<div class="left col50">
				    <p>
				        <input type="text" required name="fname" id="fname" value="<?php echo htmlspecialchars($_SESSION['first_name']); ?>">
    					<label alt="First Name" placeholder="First Name"></label>
    				</p>
				</div>
				<div class="right col50">
					<p>
					    <input type="text" required name="lname" id="lname" value="<?php echo htmlspecialchars($_SESSION['last_name']); ?>">
    					<label alt="Last Name" placeholder="Last Name"></label>
    				</p>
				</div>
				<div class="clear"></div>
			
				<p><input type="text" required name="num" id="num" value="<?php echo htmlspecialchars($_SESSION['phone']); ?>" >
					<label alt="Phone Number" placeholder="Phone Number"></label>
				</p>
				<p><input type="email" required name="eml" id="eml" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" >
					<label alt="Email Address" placeholder="Email Address"></label>
				</p>
				<p><input type="submit" value="Update Information"></p>
			</form>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php 
} 
else 
{
	echo "<script>window.location='index.php'</script>";
    die;
} 
?>