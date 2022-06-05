<?php 
if( isset($_SESSION[PRE_FIX.'sessionTokon']))
{
    if(isset($_GET['action'])){
        
        if($_GET['action']=="deleteSlider") 
        {

            $id = htmlspecialchars($_GET['id'], ENT_QUOTES);
            
            $url=$baseurl . 'deleteWebSliderImage';
            
            $data = 
                array(
                    "id" => $id
                );
            
            $json_data=@curl_request($data,$url);
           
           if($json_data['code'] !== 200)
           {
                echo "<script>window.location='dashboard.php?p=webSliders&action=error'</script>";
           }
           else
           {
                echo "<script>window.location='dashboard.php?p=webSliders&action=success'</script>";
           }

            
        }
        else
        if($_GET['action'] == "addWebSliderImage") 
        {
            $user_id    = $_SESSION[PRE_FIX.'id'];
            $image_base = file_get_contents($_FILES['image']['tmp_name']);
            $image      = base64_encode($image_base);
            
            $url        = $baseurl . 'addWebSliderImage';
            
            $data = 
                array(
                    "user_id"   => $user_id, 
                    "image"     => array("file_data" => $image)
                );
            
            $json_data = @curl_request($data,$url);
            
            
           // do some checking to make sure it sent
           //var_dump($json_data);
           //die;
    
           if($json_data['code'] !== 200)
           {
                echo "<script>window.location='dashboard.php?p=webSliders&action=error'</script>";
           }
           else
           {
                echo "<script>window.location='dashboard.php?p=webSliders&action=success'</script>";
           }

        }
    }
    
    
        $url=$baseurl . 'showWebSliderImages';
        $data = array();
        
        $json_data=@curl_request($data,$url);
        
        $allusers = array();
        if ($json_data['code'] == 200) {
            $allusers = $json_data['msg'];
        }

        ?>

        <div class="qr-content">
            <div class="qr-page-content">
                <div class="qr-page zeropadding">
                    <div class="qr-content-area">
                        
                        <div class="qr-row">
                            <div class="qr-el">

                                <div class="page-title">
                                    <h2>Web Sliders</h2>
                                    <div class="head-area">
                                    </div>
                                </div>
                                
                                <div class="qr-row1">
                                    
                                    <?php 
                                    
                                        if($json_data['code']=="200")
                                        {
                                           foreach ($allusers as $single_user): 
                                        
                                            ?>
                                            
                                                <div class="qr-el qr-el-1" style="float: left;">
                                                    <div style="height: 160px;">
                                                        <a href="dashboard.php?p=webSliders&action=deleteSlider&id=<?php echo $single_user['WebSlider']['id']; ?>" class="hover_image">
                                                            <div class="deleteIcon">
                                                                <i class="fa fa-trash" style="margin-top: 5px;"></i>
                                                            </div>
                                                        </a>
                                                        <img src="<?php echo $imagebaseurl.$single_user['WebSlider']['image']; ?>" alt="slider image" style="width: 100%; height: 100%">
                                                    </div>
                                                </div>
                                            
                                            <?php 
                                            
                                            endforeach;  
                                        }
                                        
                                    ?>
                                    
                                    <form id="sliderImageform" action="dashboard.php?p=webSliders&action=addWebSliderImage" method="POST" enctype="multipart/form-data">
                                        
                                        <div class="qr-el qr-el-1" style="float: left;">
                                            <label for="uploadFile" class="hoviringdell uploadBox" id="uploadTrigger" style="height: 160px;">
                                                <img src="frontend_public/uploads/attachment/upload.png">
                                                <div class="uploadText">
                                                    <span style="color:#F69518;">Browse</span><br>
                                                     Size 390x250px  
                                                </div>
                                            </label>
                                        </div>
                                        <input name="image" class="hidden" id="uploadFile" type="file" required="required">
                                        <input value="Submit" class="buttoncolor full_width" style="border: 0px;" type="hidden">
                                    </form>
                                   <div style="clear:both;"></div>
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
    <script>
        $(document).ready(function () {
            $('#table_view').DataTable({
                    "pageLength": 100
                }
            );
            $('#table_view2').DataTable({
                    "pageLength": 35
                }
            );
        });
        
        
        
        document.getElementById("uploadFile").onchange = function () {
            Upload_image_desktop();
        };
        
        
        function Upload_image_desktop() {

            var fileUpload = document.getElementById("uploadFile");
    
    
            var regex = new RegExp("(.jpg|.png|.jpeg)$");
            if (regex.test(fileUpload.value.toLowerCase())) {
    
    
                if (typeof (fileUpload.files) != "undefined") {
    
                    var reader = new FileReader();
    
                    reader.readAsDataURL(fileUpload.files[0]);
                    reader.onload = function (e) {
    
                        var image = new Image();
    
    
                        image.src = e.target.result;
    
    
                        image.onload = function () {
                            var height = this.height;
                            var width = this.width;
    
                            if (height == 250 && width == 390) 
                            {
    
                                document.getElementById("sliderImageform").submit();
    
                            } else {
    
                                alert("Size 390x250");
                                return false;
                            }
                        };
    
                    }
                } else {
                    alert("This browser does not support HTML5.");
                    return false;
                }
            } else {
                alert("Please select a valid Image file.");
                return false;
            }
        }
        
    </script>
    <?php
    
} 
else 
{
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>