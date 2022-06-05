<?php
if (isset($_SESSION[PRE_FIX.'sessionTokon'])) {
    if (isset($_GET['action'])) {

        if ($_GET['action'] == "addProduct") {

            $id             = htmlspecialchars($_POST['id'], ENT_QUOTES);
            $store_id             = htmlspecialchars($_POST['store_id'], ENT_QUOTES);
            $userid             = htmlspecialchars($_POST['userid'], ENT_QUOTES);
            $product_title        = htmlspecialchars($_POST['product_title'], ENT_QUOTES);
            $product_description = htmlspecialchars($_POST['product_description'], ENT_QUOTES);
            $store_id = htmlspecialchars($_POST['store_id'], ENT_QUOTES);
            $category_id = htmlspecialchars($_POST['category_id'], ENT_QUOTES);
            $price = htmlspecialchars($_POST['price'], ENT_QUOTES);
            $sale_price = htmlspecialchars($_POST['sale_price'], ENT_QUOTES);

            $url = $baseurl . 'addProduct';

            $data = array(
                "id" => $id,
                "store_id" => $store_id,
                "userid" => $userid,
                "product_title" => $product_title,
                "product_description" => $product_description,
                "category_id" => $category_id,
                "price" => $price,
                "sale_price" => $sale_price
            );

//print_r($data);exit;
            $json_data = @curl_request($data, $url);

            $json_return = $json_data['msg'];
            //  print_r($json_return);
            //  exit;
            if ($json_data['code'] !== 200) {
                echo "<script>window.location='dashboard.php?p=manageProducts&storeId=".$store_id."&action=error'</script>";
            } else {
                echo "<script>window.location='dashboard.php?p=manageProducts&storeId=".$store_id."&action=success'</script>";
            }


        }

        if($_GET['action'] == "deleteProduct")
        {
            $id                 = htmlspecialchars($_GET['id'], ENT_QUOTES);
            $store_id             = htmlspecialchars($_GET['store_id'], ENT_QUOTES);
            $deleted  = 'D';

              $url = $baseurl . 'deletedProduct';

              $data = array(
                  "id" => $id,
                  "deleted" => $deleted
              );


              $json_data = @curl_request($data, $url);

              $json_return = $json_data['msg'];
           // print_r($json_return);exit;
              if ($json_data['code'] !== 200) {
                  echo "<script>window.location='dashboard.php?p=manageProducts&storeId=".$store_id."&action=error'</script>";
              } else {
                  echo "<script>window.location='dashboard.php?p=manageProducts&storeId=".$store_id."&action=success'</script>";
              }
        }
         else
            {

            }

    } 

    $url      = $baseurl . 'storeProductsFromRes';
    //$url      = $baseurl . 'stoerProducts';
    $store_id = htmlspecialchars($_GET['storeId'], ENT_QUOTES);
    $userid   = htmlspecialchars($_GET['userid'], ENT_QUOTES);
    $data = array(
      'store_id'  => $store_id
    );

    $json_data = @curl_request($data, $url);
//print_r($json_data );exit;
    $allusers = [];
  
    if ($json_data['code'] == 200) {
        //echo $json_data['code'];exit;
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
                            <h2>All Products</h2>
                            <div class="head-area">
                            </div>
                        </div>

                        <div class="right" style="padding: 10px 0;">
                            <button onclick="addProduct(<?php echo $store_id;?>, <?php echo $userid;?>);"
                                    class="com-button com-submit-button com-button--large com-button--default">
                                <div class="com-submit-button__content"><span>Add Product</span></div>
                            </button>
                        </div>
                        <!--start of datatable here-->


                        <table id="table_view" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php 
                            
                            if($json_data['code']=="200")
                            {
                                
                                foreach ($allusers as $single_user): 
                                ?>
    
                                    <tr>
                                        <td>
                                          <?php echo $single_user['re_i']['item_id']; ?>
                                        </td>
                                        
                                        <td>
                                            
                                            <?php echo $single_user['re_i']['product_title']; ?>
                                        </td>
                                        <td><?php echo $single_user['re_i']['price'] ; ?></td>
                                        <td>
                                            <?php echo $single_user['c']['category']; ?>
                                        </td>
                                        
                                        <td>
                                            <?php echo $single_user['re_i']['created']; ?>
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
                                                        
                                                       <a href="javascript:" onclick="editProduct(<?php echo $store_id;?>, <?php echo $single_user['re_i']['item_id'];?>);">
                                                            <li class="more-menu-item" role="presentation">
                                                                <button type="button" class="more-menu-btn" role="menuitem">
                                                                    Edit
                                                                </button>
                                                            </li>
                                                        </a>
                                                        
                                                       <a href="?p=manageProducts&action=deleteProduct&store_id=<?php echo $store_id; ?>&id=<?php echo $single_user['re_i']['item_id']; ?>">
                                                            <li class="more-menu-item" role="presentation">
                                                                <button type="button" class="more-menu-btn" role="menuitem">
                                                                    Delete
                                                                </button>
                                                            </li>
                                                        </a>
                                                        
                                                        
    
    
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
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Created</th>
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
            'pageLength': 100
          }
        )
        $('#table_view2').DataTable({
            'pageLength': 35
          }
        )
      })

      function viewRestaurant (id) {

        document.getElementById('PopupParent').style.display = 'block'
        document.getElementById('contentReceived').innerHTML = 'loading...'

        var xmlhttp
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest()
        } else {// code for IE6, IE5
          xmlhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xmlhttp.onreadystatechange = function () {
          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // alert(xmlhttp.responseText);
            document.getElementById('contentReceived').innerHTML = xmlhttp.responseText
          }
        }
        xmlhttp.open('GET', 'ajex-events.php?action=viewRestaurant&id=' + id)
        xmlhttp.send()
      }
      
      function selectCategory(id,level)
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
            // alert(id);
            // return false;
            xmlhttp.open("GET", "ajex-events.php?action=selectCategory&id="+id+"&level="+level);
            xmlhttp.send();
        }


      function addProduct (store_id, userid) {

        document.getElementById('PopupParent').style.display = 'block'
        document.getElementById('contentReceived').innerHTML = 'loading...'

        var xmlhttp
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest()
        } else {// code for IE6, IE5
          xmlhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xmlhttp.onreadystatechange = function () {
          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // alert(xmlhttp.responseText);
            document.getElementById('contentReceived').innerHTML = xmlhttp.responseText
          }
        }
        //xmlhttp.open('GET', 'ajex-events.php?action=showAddCoupon')
        xmlhttp.open('GET', 'ajex-events.php?action=addProduct&store_id='+store_id+'&userid='+userid)
        xmlhttp.send()
      }
      
      function editProduct (store_id, id) {

        document.getElementById('PopupParent').style.display = 'block'
        document.getElementById('contentReceived').innerHTML = 'loading...'

        var xmlhttp
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest()
        } else {// code for IE6, IE5
          xmlhttp = new ActiveXObject('Microsoft.XMLHTTP')
        }
        xmlhttp.onreadystatechange = function () {
          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // alert(xmlhttp.responseText);
            document.getElementById('contentReceived').innerHTML = xmlhttp.responseText
          }
        }
        //xmlhttp.open('GET', 'ajex-events.php?action=showAddCoupon')
        xmlhttp.open('GET', 'ajex-events.php?action=editProduct&store_id='+store_id+'&id='+id)
        xmlhttp.send()
      }

      function Upload_image_desktop () {

        var fileUpload = document.getElementById('uploadFile')

        var regex = new RegExp('([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$')
        if (regex.test(fileUpload.value.toLowerCase())) {

          if (typeof (fileUpload.files) != 'undefined') {

            var reader = new FileReader()

            reader.readAsDataURL(fileUpload.files[0])
            reader.onload = function (e) {

              var image = new Image()

              image.src = e.target.result

              image.onload = function () {
                var height = this.height
                var width = this.width

                // if (height == 150 && width == 150) {

                //     document.getElementById('uploadTrigger').style.background="url("+image.src+")";
                //     document.getElementById('uploadTrigger').style.backgroundPosition ="top";
                //     document.getElementById('uploadTrigger').style.backgroundRepeat ="no-repeat";
                //     document.getElementById('uploadTrigger').style.backgroundSize ="contain";
                    
                //     document.getElementById('logouploadText').style.display ="none";
                //     document.getElementById('logoPlaceholderimage').style.display ="none";
                    
                // } else {

                //   alert('Size 150x150')
                //   return false
                // }
              }

            }
          } else {
            alert('This browser does not support HTML5.')
            return false
          }
        } else {
          alert('Please select a valid Image file.')
          return false
        }
      }

      function Upload_image_desktopCover () {

        var fileUpload = document.getElementById('uploadFileCover');

        var regex = new RegExp('([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$')
        if (regex.test(fileUpload.value.toLowerCase())) {

          if (typeof (fileUpload.files) != 'undefined') {

            var reader = new FileReader()

            reader.readAsDataURL(fileUpload.files[0])
            reader.onload = function (e) {

              var image = new Image()

              image.src = e.target.result

              image.onload = function () {
                var height = this.height
                var width = this.width

                if (height == 270 && width == 900) 
                {
                    document.getElementById('uploadTriggerCover').style.background="url("+image.src+")";
                    document.getElementById('uploadTriggerCover').style.backgroundPosition ="top";
                    document.getElementById('uploadTriggerCover').style.backgroundRepeat ="no-repeat";
                    document.getElementById('uploadTriggerCover').style.backgroundSize ="contain";
                    
                    document.getElementById('coveruploadText').style.display ="none";
                    document.getElementById('coverPlaceholderimage').style.display ="none";
                    
                    //"background-repeat: no-repeat; background-position: top;"
                  //document.getElementById("sliderImageform").submit();

                } else {

                  alert('Size 900x270')
                  return false
                }
              }

            }
          } else {
            alert('This browser does not support HTML5.')
            return false
          }
        } else {
          alert('Please select a valid Image file.')
          return false
        }
      }

    </script>
    <?php

} else {

    @header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;

} ?>