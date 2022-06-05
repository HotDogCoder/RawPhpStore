<?php if( isset($_SESSION['id']) )
{ 

?>

<h2 class="title">حسابي</h2>
<div class="form">
	<div class="right col60">
	
		<div class="form">
			<form action="dashboard.php?p=account&action=updateProfile" id="accoutinfo" method="post">
				<div class="right col50">
				    <p>
				        <input type="text" required name="fname" id="fname" value="<?php echo htmlspecialchars($_SESSION['first_name']); ?>">
    					<label alt="الاسم الاول" placeholder="الاسم الاول"></label>
    				</p>
				</div>
				<div class="left col50">
					<p>
					    <input type="text" required name="lname" id="lname" value="<?php echo htmlspecialchars($_SESSION['last_name']); ?>">
    					<label alt="الكنية" placeholder="الكنية"></label>
    				</p>
				</div>
				<div class="clear"></div>
			
				<p><input type="text" required name="num" id="num" value="<?php echo htmlspecialchars($_SESSION['phone']); ?>" >
					<label alt="رقم الهاتف" placeholder="رقم الهاتف"></label>
				</p>
				<p><input type="email" required name="eml" id="eml" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" >
					<label alt="البريد الإلكتروني" placeholder="Eالبريد الإلكتروني"></label>
				</p>
				<p><input type="submit" value="تحديث المعلومات"></p>
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