<?php if( isset($_SESSION['id']) )
{ 

    ?>
    
        <h2 class="title">غير كلمة السر</h2>
        <div class="form">
            <div class="right col40">
                <div class="form">
                    <form action="dashboard.php?p=changepassword&action=changePassword" id="changepass" method="post">
                        <p>
                            <input type="password" id="oldpas" name="oldpas" required>
                            <label alt="كلمة المرور القديمة" placeholder="كلمة المرور القديمة"></label>
                        </p>
                        <p>
                            <input type="password" id="newpas" name="newpas" required>
                            <label alt="كلمة سر جديدة" placeholder="كلمة سر جديدة"></label>
                        </p>
                        <p>
                            <input type="password" id="renewpas" name="renewpas" required>
                            <label alt="أعد كتابة كلمة السر الجديدة" placeholder="أعد كتابة كلمة السر الجديدة"></label>
                        </p>
                        <p>
                            <input type="submit" value="تطوير كلمة السر">
                        </p>
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