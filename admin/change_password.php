<?php
if( isset($_SESSION[PRE_FIX.'id']))
{
    if(isset($_GET['action'])){
            
        if($_GET['action']=="changeAdminPassword") 
        {

            $user_id = htmlspecialchars($_POST['user_id'], ENT_QUOTES);
            $password = htmlspecialchars($_POST['confirme_password'], ENT_QUOTES);
            
            $url=$baseurl . 'editAdminUserPassword';
            
            $data = array(
				"user_id" => $user_id,
				"password" => $password
			);
            
            $json_data=@curl_request($data,$url);
            
            
           if($json_data['code'] !== 200)
           {
                echo "<script>window.location='dashboard.php?p=changePassword&action=error'</script>";
           }
           else
           {
                echo "<script>window.location='dashboard.php?p=changePassword&action=success'</script>";
           }

            
        }
       
    }
?>

    <div class="qr-content">
            <div class="qr-page-content">
                <div class="qr-page toppaddzero">
                    <div class="qr-content-area">
                        <div class="qr-row">
                            <div class="qr-el">
                                <div class="page-title">
                                    <h2>Change Password</h2>
                                    <div class="head-area">
                                    </div>
                                </div>
                                <div class="panel margin-top-n">

                                    <form action="dashboard.php?p=changePassword&action=changeAdminPassword" method="post" class="inputs validatedForm">
                                        <input type="hidden" name="user_id" value="<? echo $_SESSION[PRE_FIX.'id']; ?>" >
                                        <div class="inputs__item inputs__item--new">
                                            <label for="new-password" class="inputs__label">New password</label>
                                            <input type="password" class="inputs__input" id="new-password" name="new_password" value="" required="required"/>
                                        </div>
                                        <div class="inputs__item inputs__item--new">
                                            <label for="new-password" class="inputs__label">Confirm password</label>
                                            <input type="password" class="inputs__input" name="confirme_password" id="confirme-password" value="" required="required"/>
                                        </div>


                                        <button name="password_update" class="com-button com-submit-button com-button--large com-button--default">
                                            <div class="com-submit-button__content"><span>Update Profile</span></div>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        

    
<?php
}
else 
{
	
     echo "<script>window.location='index.php'</script>";
    die;
    
} 
?>