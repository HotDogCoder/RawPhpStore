<?php 
if( isset($_SESSION[PRE_FIX.'sessionTokon']))
{   
    if(isset($_GET['action'])){
        if($_GET['action']=="addCategory") 
        {

            $category_name = htmlspecialchars($_POST['category_name'], ENT_QUOTES);
            $parent_id = htmlspecialchars($_POST['parent_id'], ENT_QUOTES);
            if(isset($_FILES['icon']['tmp_name']) && $_FILES['icon']['tmp_name']!="") {
                $icon_base = file_get_contents($_FILES['icon']['tmp_name']);
                $icon = base64_encode($icon_base);

                $icon_arr = array("file_data" => $icon);
            }
            else
            {
                $icon_arr = "";
            }
            
            $id = @$_POST['id'];
        
            $url=$baseurl . 'addCategory';

            $icon_name = "";
            
            if($id=="")
            {
                $data = 
                    array(
                        "category" => $category_name, 
                        "parent_id" => $parent_id, 
                        "icon" => $icon_arr,
                    );  
            }
            else
            {
                $data = 
                    array(
                        "id" =>$id,
                        "category" => $category_name, 
                        "parent_id" => $parent_id, 
                        "icon" => $icon_arr,
                    );
            }
            
            $json_data=@curl_request($data,$url);
           
           if($json_data['code'] !== 200)
           {
                echo "<script>window.location='dashboard.php?p=manageCategoryNew&action=error'</script>";
           }
           else
           {
                echo "<script>window.location='dashboard.php?p=manageCategoryNew&action=success'</script>";
           }

            
        }
        else
        if($_GET['action']=="delete") 
        {

            $id = htmlspecialchars($_GET['id'], ENT_QUOTES);
            $url=$baseurl . 'deleteCategory';
            
            $data = 
                array(
                    "id" => $id
                );
            
            $json_data=@curl_request($data,$url);
            
    
           if($json_data['code'] !== 200)
           {
                echo "<script>window.location='dashboard.php?p=manageCategoryNew&action=error'</script>";
           }
           else
           {
                echo "<script>window.location='dashboard.php?p=manageCategoryNew&action=success'</script>";
           }

            
        }
        
    } 
    
    
        $url=$baseurl . 'showCategoriesNew';
        
        $data =array(
            "level"=>"0"    
        );
        
        $json_data=@curl_request($data,$url);
        $json_data=$json_data['msg'];
    //   print_r($json_data);exit;
        ?>

        <div class="qr-content">
            <div class="qr-page-content">
                <div class="qr-page zeropadding">
                    <div class="qr-content-area">
                        <div class="qr-row">
                            <div class="qr-el">

                                <div class="page-title">
                                    <h2>Manage Category</h2>
                                    <div class="head-area">
                                    </div>
                                </div>
                                
                                <div style="height:100vh; min-width:1800px;">
                                    
                                        
                                        <?php 
                                            $countData=count($json_data);
                                            if($countData>0)
                                            {   
                                                
                                                
                                                ?>
                                                    <div class="category" style="border:solid 1px #fbf9f9; height: auto; width:240px;padding: 8px 8px;border-radius: 3px;background: #fcfcfc;float: left;">
                                                        <?php
                                                        foreach ($json_data as $singleRow):
                                                                
                                                                $checkImageExist=checkImageExist($imagebaseurl.$singleRow['Category']['icon']);
                                                                if($checkImageExist=="200")
                                                                {
                                                                    $checkImageExist=$imagebaseurl.$singleRow['Category']['icon'];
                                                                }
                                                                else
                                                                {
                                                                    $checkImageExist="frontend_public/uploads/noimage.jpg";
                                                                }
                                                                
                                                                
                                                            ?>
                                                                <div id="row_<?php echo $singleRow['Category']['id']; ?>" style="border: solid 1px #f5f5f5;padding:2px 8px;font-size: 13px;background: white;margin: 0 0 5px 0;cursor: pointer;">
                                                                    <div style="float: left;" onclick="showCategory('<?php echo $singleRow['Category']['id']; ?>','1')" ><img src="<?php echo $checkImageExist; ?>" style="width: 30px; height: 30px;border-radius: 100%;"></div>
                                                                    <div style="float: left;margin-top: 10px;" class="title_<?php echo $singleRow['Category']['id']; ?>" onclick="showCategory('<?php echo $singleRow['Category']['id']; ?>','1')" > <?php echo $singleRow['Category']['category']; ?></div>
                                                                    <div style="float: right; margin-top: 10px;">
                                                                        
                                                                        
                                                                        <?php
                                                                            if($singleRow['Category']['featured']=="0")
                                                                            {
                                                                               ?>
                                                                                    <span style="font-size: 12px;margin-right: 5px;">
                                                                                        <a href="process.php?action=favCategory&featured=1&category_id=<?php echo $singleRow['Category']['id']; ?>"><span class="far fa-star"></span></a>
                                                                                    </span>
                                                                               <?php 
                                                                            }
                                                                            else
                                                                            if($singleRow['Category']['featured']=="1")
                                                                            {
                                                                               ?>
                                                                                    <span style="font-size: 12px;margin-right: 5px;">
                                                                                        <a href="process.php?action=favCategory&featured=0&category_id=<?php echo $singleRow['Category']['id']; ?>"><span class="fas fa-star" style="color:black;"></span></a>
                                                                                    </span>
                                                                               <?php 
                                                                            }
                                                                        ?>
                                                                        <span class="editCategory" onclick="editCategoryRow('<?php echo $singleRow['Category']['id']; ?>')" style="font-size: 12px;margin-right: 5px;">
                                                                            <span class="far fa-edit"></span>
                                                                        </span>
                                                                        
                                                                        <span class="deleteCategory" onclick="deleteCategory('<?php echo $singleRow['Category']['id']; ?>')" style="font-size: 12px;">
                                                                            <span class="far fa-trash-alt"></span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>
                                                                <div class="editBox" id="edit_<?php echo $singleRow['Category']['id']; ?>"></div>
                                                            <?php 
                                                        endforeach;  
                                                        ?>
                                                        <div id="newEntry_1"></div>
                                                        <div onclick="addCategory('0','1')" style="border: dashed 2px #f5f5f5;padding:8px 8px;font-size: 13px;background: #fbfbfb;margin: 0 0 5px 0;">
                                                            + Add New
                                                        </div>
                                                        <div id="addCategory_1"></div>
                                                    </div>
                                                    <span id="dataRecived_1"></span>
                                                    
                                                    <div class="clear"></div>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="category" style="border:solid 1px #fbf9f9; height: auto; width:240px;padding: 8px 8px;border-radius: 3px;background: #fcfcfc;float: left;">
                                                        <?php
                                                        foreach ($json_data as $singleRow): 
                                                            ?>
                                                                <div onclick="showCategory('<?php echo $singleRow['Category']['id']; ?>','1')" style="border: solid 1px #f5f5f5;padding:8px 8px;font-size: 13px;background: white;margin: 0 0 5px 0;">
                                                                    <?php echo $singleRow['Category']['category']; ?>
                                                                </div>
                                                            <?php 
                                                        endforeach;  
                                                        ?>
                                                        <div id="newEntry_1"></div>
                                                        <div onclick="addCategory('0','1')" style="border: dashed 2px #f5f5f5;padding:8px 8px;font-size: 13px;background: #fbfbfb;margin: 0 0 5px 0;">
                                                            + Add New
                                                        </div>
                                                        <div id="addCategory_1"></div>
                                                    </div>
                                                    <span id="dataRecived_1"></span>
                                                    
                                                    <div class="clear"></div>
                                                <?php
                                            }
                                        
                                             
                                        ?>
                                    
                                </div>
                                

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            </div>
        
<script>
        function showCategory(id,level)
        {
            document.getElementById('dataRecived_'+level).innerHTML = "loading...";
        
            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    document.getElementById('dataRecived_'+level).innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "ajex-events.php?q=showCategory&id="+id+"&level="+level);
            xmlhttp.send();
        }
                
        function addCategory(id,level)
        {
            document.getElementById('addCategory_'+level).innerHTML = "loading...";
        
            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    document.getElementById('addCategory_'+level).innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "ajex-events.php?q=addCategory&id="+id+"&level="+level);
            xmlhttp.send();
        }
        
        // function editCategoryRow(id)
        // {
        //     document.getElementById('addCategory_'+level).innerHTML = "loading...";
        
        //     var xmlhttp;
        //     if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        //         xmlhttp = new XMLHttpRequest();
        //     } else {// code for IE6, IE5
        //         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        //     }
        //     xmlhttp.onreadystatechange = function () {
        //         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        //             // alert(xmlhttp.responseText);
        //             document.getElementById('addCategory_'+level).innerHTML = xmlhttp.responseText;
        //         }
        //     }
        //     xmlhttp.open("GET", "ajex-events.php?q=editCategory&id="+id);
        //     xmlhttp.send();
        // }
        
        // function deleteCategory(id)
        // {
        //     document.getElementById('addCategory_'+level).innerHTML = "loading...";
        
        //     var xmlhttp;
        //     if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        //         xmlhttp = new XMLHttpRequest();
        //     } else {// code for IE6, IE5
        //         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        //     }
        //     xmlhttp.onreadystatechange = function () {
        //         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        //             // alert(xmlhttp.responseText);
        //             document.getElementById('addCategory_'+level).innerHTML = xmlhttp.responseText;
        //         }
        //     }
        //     xmlhttp.open("GET", "ajex-events.php?q=addCategory&category_id="+id+"&level="+level);
        //     xmlhttp.send();
        // }
                
                
</script>
    <?php
    
} 
else 
{
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>