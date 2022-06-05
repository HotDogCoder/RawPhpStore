<?php 

if( isset($_SESSION[PRE_FIX.'restaurant_id'])){ ?>

<h2 class="title">Manage Menu</h2>
<?php 

$userid=$_SESSION[PRE_FIX.'restaurant_id'];

if( isset($_SESSION[PRE_FIX.'restaurant_id']))
{

	$user_id = $userid;
	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json",
		"api-key: ".API_KEY." "
	);

	$data = array(
		"user_id" => $user_id
	);

	$ch = curl_init( $baseurl.'/showMainMenus' );

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$return = curl_exec($ch);

	$json_data = json_decode($return, true);

	$curl_error = curl_error($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if($json_data['code'] !== 200)
	{
		?>
            <div class="textcenter nothingelse">
                <img src="img/nomenu.png" style="display: inline-block;" alt="" />
                <h3 style="font-size: lighter; font-size: 25px; color: #aaa;">Whoops!</h3>
            </div>
        <?php
	} 
	else 
	{
		
		echo "<ul class='mainmenus'>";
    		foreach( $json_data['msg'] as $str => $val ) 
    		{
    			$cc = count($val['RestaurantMenu']);
    			$currency=$val['Currency']['symbol'];
    			foreach ($val['RestaurantMenu'] as $menukey => $menuvalue) 
    			{
    				$restaurant_id = $menuvalue['restaurant_id'];
    				?>
                        <li>
                            <div class="inrdiv">
                            
                                <div class="left col15">
                                    <?php
                                        
                                        if($menuvalue['image']!="")
                                        {
                                            $imageURL=$image_baseurl.$menuvalue['image'];
                                            $checkImage=checkImageExist($imageURL);
                                            
                                            if($checkImage=="200")
                                            {
                                            	?>
                                            		<img src='<?php echo $image_baseurl.$menuvalue['image'];?>' style="width: 80px;">
                                            	<?php 
                                            }
                                            else
                                            {
                                            	?>
                                            		<img src='img/noimage.jpg' style="width: 80px;">
                                            	<?php 
                                            }
                                        }
                                        else
                                        {
                                        	?>
                                        		<img src='img/noimage.jpg' style="width: 80px;">
                                        	<?php 
                                        }
                                    ?>
                                </div>
                                <div class="left col40">
                                  <h3>
                                      <?php echo utf8_decode($menuvalue['name']); ?> <span class="editlink"><a href="javascript:;" data-menu-id="<?php echo $menuvalue['id']; ?>" data-menu-name="<?php echo utf8_decode($menuvalue['name']); ?>" data-menu-description="<?php echo utf8_decode($menuvalue['description']); ?>" class="main_menu_edit"><i class="fa fa-pencil"></i></a></span></h3>
                                  
                                  <p><?php echo ucwords(strtolower(utf8_decode($menuvalue['description']))); ?></p>
                                  <p>
                                    <?php
                            			if($menuvalue['active']=="1")
                            			{
                            				?>
                            					<a href="process.php?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menuID=<?php echo $menuvalue['id']; ?>&active=0&removeMenu=ok" onclick="return confirm('Are you sure?')" style="color:#C3242E; text-decoration:none;">Delete</a>
                            				<?php
                            			}
                            			else
                            			{
                            				?>
                            					<a href="process.php?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menuID=<?php echo $menuvalue['id']; ?>&active=1&removeMenu=ok" onclick="return confirm('Are you sure?')" style="color:green; text-decoration:none; ">Restore</a>
                            				<?php
                            			}
                            		?>
                                  </p>
                                  <p style="display:none;">
                                	<lable>Index:</lable>
                                  	<input type="text" name="<?php echo $menuvalue['id']; ?>_index" id="<?php echo $menuvalue['id']; ?>_index" style="width: 50px; padding: 4px; margin: 8px;" value="<?php echo $menuvalue['index']; ?>" >
                                
                                  	<span id="<?php echo $menuvalue['id']; ?>_buttonBox">
                                  		<input type='button' value="Update" style="width: 50px; padding: 5px; margin: 2px;" onclick="updateindex(<?php echo $menuvalue['id']; ?>)">
                                  	</span>
                                  </p>
                                </div>
                                <div class="right col20" style="text-align: right;">
                                  <p class="icon" onClick="openmenu(<?php echo $menuvalue['id']; ?>)" style="margin-top: 20px;"><span class="fa fa-chevron-down"></span></p>
                                </div>
                            
                                <div class="clear"></div>
                            </div>
                            <div id="main_menu_edit_div_<?php echo $menuvalue['id']; ?>"></div>
                          
                            <ul class="mainmenus_items" id="<?php echo $menuvalue['id']; ?>">
                                <?php $totalrows = count($menuvalue['RestaurantMenuItem']);
    						    
    						    foreach ($menuvalue['RestaurantMenuItem'] as $key => $value) 
    						    {
    							
    							?>
                                    <li>
                                        <div class="left col15">
                                            <?php
                                                
                                                if($value['image']!="")
                                                {
                                                    $imageURL=$image_baseurl.$value['image'];
                                                    $checkImage=checkImageExist($imageURL);
                                                    
                                                    if($checkImage=="200")
                                                    {
                                                        ?>
                                                            <img src='<?php echo $image_baseurl.$value['image'];?>' style="width: 80px;">
                                                        <?php 
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                            <img src='img/noimage.jpg' style="width: 80px;">
                                                        <?php 
                                                    }
                                                }
                                                else
                                                {
                                                	?>
                                                		<img src='img/noimage.jpg' style="width: 80px;">
                                                	<?php 
                                                }
                                                
                                                
                                            ?>
                                        </div>
                                      <div class="left col80">
                                        <h3><?php echo utf8_decode($value['name']); ?> <span class="editlink"> <a href="javascript:;" data-main-menu-id="<?php echo $menuvalue['id']; ?>" data-menu-id="<?php echo $value['id']; ?>" data-menu-name="<?php echo utf8_decode($value['name']); ?>" data-menu-description="<?php echo utf8_decode($value['description']); ?>" data-menu-price="<?php echo $value['price']; ?>" data-out-of-stock="<?php echo $value['out_of_order']; ?>" class="main_menu_item_edit"><i class="fa fa-pencil"></i></a> </span></h3>
                                        <p><?php echo utf8_decode($value['description']); ?></p>
                                		<p>
                                			<?php
                                				if($value['active']=="1")
                                				{
                                					?>
                                						<a href="process.php?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menuItemID=<?php echo $value['id']; ?>&active=0&removeMenuItem=ok" onclick="return confirm('Are you sure?')" style="color:red; text-decoration:none; display:none1;">Delete</a>
                                					<?php
                                				}
                                				else
                                				{
                                					?>
                                						<a href="process.php?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menuItemID=<?php echo $value['id']; ?>&active=1&removeMenuItem=ok" onclick="return confirm('Are you sure?')" style="color:green; text-decoration:none; display:none1;">Restore</a>
                                					<?php
                                				}
                                			?>
                                		</p>
                                      </div>
                                      <div class="right textcenter">
                                	  	
                                        <p class="price"> <?php echo $currency; echo $value['price']; ?>
                                            <?php 
                                                $outofstock = $value['out_of_order'];
                                									
        									    if($outofstock == 1)
        									    {
        										    echo "<br><p style='color:red;'>"."Out Of Stock"."</p>";
        										} 
        										else
        										{
        											echo "<br><p style='color:green;'>"."Available"."</p>";
        										}
        								    ?>
                                        </p>
                                      </div>
                                      <div class="clear"></div>
                                      <div id="main_menu_item_edit_div_<?php echo $value['id']; ?>"></div>
                                      <ul class="menuextrasection_ul">
                                        <?php //show restaurant extra menu section
            								foreach ($value["RestaurantMenuExtraSection"] as $sectionkey => $sectionvalue) 
            								{
                                		        ?>
                                                    <li>
                                                      <h4><?php echo utf8_decode($sectionvalue['name']); if($sectionvalue['required']=='1') { echo "<span style='margin-left:10px; font-size: 14px; color: #aaa;'>(Required)</span>"; } ?> <span class="editlink"> <a href="javascript:;" data-section-id="<?php echo $sectionvalue['id']; ?>" data-section-name="<?php echo utf8_decode($sectionvalue['name']); ?>" data-section-req="<?php echo $sectionvalue['required']; ?>" data-restaurant-id="<?php echo $restaurant_id; ?>" data-menu-item-id="<?php echo $value['id']; ?>" class="main_menu_item_section_edit"><i class="fa fa-pencil"></i></a> </span>
		  </h4>
                                                      <p style="text-align:left; margin-bottom: 21px;">
                                            				<?php
                                            					if($sectionvalue['active']=="1")
                                            					{
                                            						?>
                                            							<a href="process.php?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&sectionID=<?php echo $sectionvalue['id']; ?>&active=0&removeMenuSection=ok" onclick="return confirm('Are you sure?')" style="color:red; text-decoration:none;">Delete</a>
                                            						<?php
                                            					}
                                            					else
                                            					{
                                            						?>
                                            							<a href="process.php?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&sectionID=<?php echo $sectionvalue['id']; ?>&active=1&removeMenuSection=ok" onclick="return confirm('Are you sure?')" style="color:green; text-decoration:none;">Restore</a>
                                            						<?php
                                            					}
                                            				?>
                                            			</p>
                                            		  <div id="main_menu_item_section_edit_div_<?php echo $sectionvalue['id']; ?>"></div>
                                                      <ul class="menuextrasection_item">
                                                        <?php foreach ($sectionvalue['RestaurantMenuExtraItem'] as $keyy => $valuee) {
                                            												?>
                                                        <li>
                                                          <div class="left col70"><?php echo utf8_decode($valuee['name']); ?> <span style="margin-top:2px;" class="editlink"> <a href="javascript:;" data-menu-section-item-id="<?php echo $valuee['id']; ?>" data-menu-section-item-name="<?php echo utf8_decode($valuee['name']); ?>" data-menu-section-item-price="<?php echo $valuee['price']; ?>" data-menu-extra-section-id="<?php echo $sectionvalue['id']; ?>" class="main_menu_item_section_item_edit"><i class="fa fa-pencil"></i></a> </span>
              </div>                                      <div class="right col30 textright"><?php echo $currency; echo $valuee['price']; ?>
                                                            <?php
                                            					if($valuee['active']=="1")
                                            					{
                                            						?>
                                            							<a href="process.php?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menu_extra_item_id=<?php echo $valuee['id']; ?>&active=0&removeMenuSectionItem=ok" onclick="return confirm('Are you sure?')" style="color:red; text-decoration:none;font-size: 12px;"><span class="fa fa-trash"></span></a>
                                            						<?php
                                            					}
                                            					else
                                            					{
                                            						?>
                                            							<a href="process.php?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menu_extra_item_id=<?php echo $valuee['id']; ?>&active=1&removeMenuSectionItem=ok" onclick="return confirm('Are you sure?')" style="color:green; text-decoration:none; font-size: 12px;">Restore</a>
                                            						<?php
                                            					}
                                            				?>
                                                          </div>
                                                          <div class="clear"></div>
                                                          <div id="main_menu_item_section_item_edit_div_<?php echo $valuee['id']; ?>"></div>
                                                        </li>
                                                        <?php
                                            											} ?>
                                                        <li style="padding: 0;">
                                                          <div class="addmenu" style="margin-top: 0;">
                                                            <h3 class="addnewmenu_extraitem" data-menu-extra-section-id="<?php echo $sectionvalue['id']; ?>"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add Section Extra Item</h3>
                                                          </div>
                                                        </li>
                                                      </ul>
                                                      <div class="clear"></div>
                                                    </li>
                                                    <?php
                                            								}
                                            								?>
                                                    <li style="padding: 0;">
                                                      <div class="addmenu" style="margin-top: 0;">
                                                        <h3 class="addnewmenu_extrasection" data-restaurant-id="<?php echo $restaurant_id; ?>" data-menu-item-id="<?php echo $value['id']; ?>"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add Menu Extra Section</h3>
                                                      </div>
                                                    </li>
                                                </ul>
                                                <div class="clear"></div>
                                    </li>
                                    <?php
                                		} 
                                	?>
                                    <li style="padding: 0;">
                                      <div class="addmenu" style="margin-top: 0;">
                                        <h3 class="addnewmenu_item" data-menu-id="<?php echo $menuvalue['id']; ?>"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add New Menu Item</h3>
                                      </div>
                                    </li>
                                  </ul>
                                  <?php //}
                                					//show restaurant menu item = end... ?>
                        </li>
                    <?php
    			}
    		}
		echo "</ul>";
		    ?>
                <div class="addmenu">
                  <h3 id="addnewmenu"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add New Menu</h3>
                </div>
            <?php
	}

	curl_close($ch);

} 
else 
{
	echo "<script>window.location='index.php'</script>";
    die;
} 


?>
<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
