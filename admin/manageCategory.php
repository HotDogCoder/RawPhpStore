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
                echo "<script>window.location='dashboard.php?p=manageCategory&action=error'</script>";
           }
           else
           {
                echo "<script>window.location='dashboard.php?p=manageCategory&action=success'</script>";
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
                echo "<script>window.location='dashboard.php?p=manageCategory&action=error'</script>";
           }
           else
           {
                echo "<script>window.location='dashboard.php?p=manageCategory&action=success'</script>";
           }

            
        }
        
    } 
    
    
        $url=$baseurl . 'showCategories';
        $data = [];
        
        $json_data=@curl_request($data,$url);
        
        $allcategories = [];
        if ($json_data['code'] == 200) {
            $allcategories = $json_data['msg'];
        }
      // print_r($allcategories);
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
                                
                                <?php
                                   // if(count($allcategories)=="0")
                                   // {
                                        ?>
                                            <div class="right" style="padding: 10px 0;">
                                               <button onclick="addCategory();"
                                                       class="com-button com-submit-button com-button--large com-button--default">
                                                   <div class="com-submit-button__content"><span>Add Category</span></div>
                                               </button>
                                            </div>
                                        <?php
                                  //  }
                                ?>
                                
                                <!--start of datatable here-->


                                <table id="table_view" class="display" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category</th>
                                        <th>Parent Category</th>
                                        <th>Icon</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php 
                                    
                                        if($json_data['code']=="200")
                                        {
                                            
                                            foreach ($allcategories as $single_cat): 
                                            ?>

                                            <tr>
                                                <td><?php echo $single_cat['Category']['id']; ?></td>
                                                <td><?php echo $single_cat['Category']['category']; ?></td>
                                                <td><?php echo $single_cat['Dcategory']['parent_name']; ?></td>
                                                <td>
                                                    <?php echo $single_cat['Category']['icon']; ?>
                                                </td>
                                                <td>
                                                    <div class="more">
                                                        <button id="more-btn" class="more-btn">
                                                            <span class="more-dot"></span>
                                                            <span class="more-dot"></span>
                                                            <span class="more-dot"></span>
                                                        </button>
                                                        <div class="more-menu">
                                                            <div class="more-menu-caret">
                                                                <div class="more-menu-caret-outer"></div>
                                                                <div class="more-menu-caret-inner"></div>
                                                            </div>
                                                            <ul class="more-menu-items" tabindex="-1" role="menu" aria-labelledby="more-btn" aria-hidden="true">
                                                                <li class="more-menu-item" role="presentation" onclick="editCategory(<?php echo $single_cat['Category']['id']; ?>)">
                                                                    <button type="button" class="more-menu-btn" role="menuitem">Edit</button>
                                                                </li>
                                                                <li class="more-menu-item" role="presentation">
                                                                    <a href="dashboard.php?p=manageCategory&action=delete&id=<?php echo $single_cat['Category']['id']; ?>">
                                                                        <button type="button" class="more-menu-btn" role="menuitem">Delete</button>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                </td>
                                                
                                                
                                            </tr>
    
                                            <?php 
                                            
                                            endforeach; 
                                            
                                        }
                                        
                                    ?>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category</th>
                                        <th>Parent Category</th>
                                        <th>Icon</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>


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
        
        function addCategory() 
        {
            document.getElementById("PopupParent").style.display = "block";
            document.getElementById("contentReceived").innerHTML = "loading...";

            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    document.getElementById('contentReceived').innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "ajex-events.php?action=addCategoryO");
            xmlhttp.send();
        }
        
        function editCategory(id) 
        {
            
            document.getElementById("PopupParent").style.display = "block";
            document.getElementById("contentReceived").innerHTML = "loading...";

            var xmlhttp;
            if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // alert(xmlhttp.responseText);
                    document.getElementById('contentReceived').innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "ajex-events.php?action=editCategoryO&id="+id);
            xmlhttp.send();
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