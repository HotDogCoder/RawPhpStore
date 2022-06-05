<?php require_once("header.php");?>
    <div class="text-center landingimage bgimage searcharea">
        <div class="inner">
            <div class="wdth">
                <h1 style="font-family: ;"><?php echo SLIDER_AREA_H1_TITLE; ?></h1>
                <h2 style="font-family: ;"><?php echo SLIDER_AREA_H2_TITLE; ?></h2>
				<div style="background-color: transparent; text-align: center!important;">
                    <!-- <form action="search.php" method="get">
                        <div class="left col70"><i class="fa fa-map-marker" style="color: #e86942"></i>
                            <input  style="border: 10px solid red; border-radius: 10px;" type="text" name="address" onFocus="initializeAutocomplete()" id="locality" placeholder="Search..." autocomplete="off" required>
                            <input type="hidden" name="lat" id="us2-lat" required>
                            <input type="hidden" name="lng" id="us2-lon" required>
                            <input type="hidden" name="city" id="city">
                        </div>

                        <div class="right col30 radiobtn marginleft">
                            <input type="submit" value="Search" id="Search" />
                            <label for="Search" style="background: #e86942; border-radius: 10px;">Search</label>
                        </div>
                        <div class="clear"></div>						
                    </form> -->
					<a href="search.php?address=Dammam%2C+Saudi Arabia&lat=26.433333&lng=50.1&city=Dammam">
					<div class="radiobtn" style="width: 230px; margin-left: auto; margin-right: auto; margin-top: 3%">
						<label for="orderNow" style="background: #E86942; border-radius: 10px; font-weight: bold;">اطلب الان</label>
					</div>
					</a>
                </div>
            </div>
        </div>
    </div>
	<div style="background-color: white;
				height: 100%;
				width: 100%; 
				background-image: url(assets/img/new/lines_background@3x.png);
				background-size: 100%;
				background-repeat: no-repeat;">
    <div class="section howitworks textcenter sec1" style=" padding: 4.5%; padding-top: 9%;">
        <div class="wdth">
            <h2 class="title" style="font-family: ; padding-bottom: 3%; color: #38215C; font-size: 50px"><? echo HOW_IT_WORKS_TITLE; ?></h2>
            <ul>
                <li>
                    <div class="iconwork">
                        <span class="icon2"></span>
                    </div>
                    <h3 style="font-family: ; color: #38215C"><?php echo HOW_IT_WORKS_SUB_TITLE; ?></h3>
                    <p style="font-family: ; color: #38215C"><?php echo HOW_IT_WORKS_DESCRIPTION; ?></p>
                </li>
                <li>
                    <div class="iconwork">
                        <span class="icon3"></span>
                    </div>
                    <h3 style="font-family: ; color: #38215C"><?php echo HOW_IT_WORKS_SUB_TITLE_2; ?></h3>
                    <p style="font-family: ; color: #38215C"><?php echo HOW_IT_WORKS_DESCRIPTION_2; ?></p>
                </li>
                <li>
                    <div class="iconwork">
                        <span class="icon4"></span>
                    </div>
                    <h3 style="font-family: ; color: #38215C"><?php echo HOW_IT_WORKS_SUB_TITLE_3; ?></h3>
                    <p style="font-family: ; color: #38215C"><?php echo HOW_IT_WORKS_DESCRIPTION_3; ?></p>
                    
                </li>

            </ul>
            <div class="clear"></div>
        </div>
    </div>

    <div class="section workwithus textcenter" style="padding: 0%;">
        <div class="wdth">
            <h2 class="title" style="font-family: ; color: #38215C; font-size: 50px">انضم إلينا</h2>
            <h2 class="title" style="font-family: ; color: #38215C; font-size: 50px"><?php echo WORK_WITH_US_TITLE; ?></h2>
            <ul>
                <li style="background: transparent;">
                    <div class="bgimage workwithus" style="margin-bottom: 9%"></div>
                    <h2 style="font-family: ; margin-bottom: 5%; color: #38215C; font-size: 30px"><?php echo WORK_WITH_US_SUB_TITLE; ?></h2>
                    <p style="font-family: ; color: #38215C"><?php echo WORK_WITH_US_DESCRIPTION; ?>
                        <br>
                        <br>
                        <br>
                        <br>
                        <a href="<?php echo BECOME_RESTAURANT;?>" target="_blank" class="workwithusBtn" style="border-radius: 25px; background-color: #e86942; color: #38215C; font-weight: bold;" >
                            <?php echo WORK_WITH_US_BUTTON; ?>
                        </a>
                    </p>

                </li>
                <li style="background: transparent;">
                    <div class="bgimage becomeourRider" style="margin-bottom: 9%"></div>
                    <h2 style="font-family: ; margin-bottom: 5%; color: #38215C; font-size: 30px"><?php echo WORK_WITH_US_SUB_TITLE_2; ?></h2>
                    <p style="font-family: ; color: #38215C">
                        <?php echo WORK_WITH_US_DESCRIPTION_2; ?>
                        <br>
                        <br>
                        <br>
                        <br>
                        <a href="<?php echo BECOME_RIDER;?>" target="_blank" class="workwithusBtn" style="border-radius: 25px; background-color: #e86942; color: #38215C; font-weight: bold;">
                            <?php echo WORK_WITH_US_BUTTON_2; ?>
                        </a>
                    </p>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    
    <div class="mobrow bgimage appBanner" style="background: transparent; padding-top: 18%; padding-bottom: 10%">
        <div class="wdth">
            <!-- <div class="right col60 section"> -->
            <div class="col40 section" style="float: left;">
                <h2 class="title" style="color: #38215C; font-size: 55px; font-family: ; margin-right: 25%;"><?php echo MOBILE_APP_TITLE; ?></h2>
                <div style="margin-right: 8%; text-align: center;">
                <p class="appDescription" style="font-family: ; width: 350px; color: #38215C; font-size: 15px; padding-bottom: 5%"><?php echo MOBILE_APP_DESCRIPTION; ?></p>
                <p class="downloadNowTitle"><?php echo MOBILE_APP_DOWNLOAD; ?></p>
                <p class="logos" style="width: 350px;">
                    <a href="<?php echo FOOTER_ANDROID_URL; ?>" target="_blank"><img src="assets/img/gplaystore.svg" alt="play store" /></a>
                    <a href="<?php echo FOOTER_iOS_URL; ?>" target="_blank"><img src="assets/img/appstore.svg" alt="apple store" /></a>
                </p>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
	</div>
    <?php require_once("footer.php"); ?>