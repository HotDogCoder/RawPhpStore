$(document).ready(function() {

    $(document).on("click",".more", function (e) {
        $(this).toggleClass("show-more-menu");
    });



    $('html').click(function(e) {
        if(!$(e.target).hasClass('more') )
        {

            $(".more").removeClass("show-more-menu");
        }
    })

});

function submitAddNewCategory()
{
    
    var id=document.getElementById("id").value;
    var level=document.getElementById("level").value;
    var category_name=document.getElementById("category_name").value;
    var image=document.getElementById("imageData").value;
    
    
    $.post("ajex-events.php?q=submitAddNewCategory", {image: image,id:id,category_name:category_name,level:level}, function(result){
        $('#newEntry_'+level).append(result);
        document.getElementById('addCategory_'+level).innerHTML ="";
        
    });
    
    
    // var xmlhttp;
    // if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
    //     xmlhttp = new XMLHttpRequest();
    // } else {// code for IE6, IE5
    //     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    // }
    // xmlhttp.onreadystatechange = function () {
    //     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
    //         $('#newEntry_'+level).append(xmlhttp.responseText);
    //         document.getElementById('addCategory_'+level).innerHTML ="";
    //     }
    // }
    // xmlhttp.open("GET", "ajex-events.php?q=submitAddNewCategory&id="+id+"&level="+level+"&category_name="+category_name+"&image="+image);
    // xmlhttp.send();
}

function editCategoryRow(id)
{
    var category_id = id;
    
    $(".editBox").html("");
    document.getElementById('edit_'+category_id).innerHTML = "loading...";

    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            //alert(xmlhttp.responseText);
            document.getElementById('edit_'+category_id).innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "ajex-events.php?q=editCategory&category_id="+category_id);
    xmlhttp.send();
}

function editCategory()
{
    var id=document.getElementById("id").value;
    var category_name=document.getElementById("category_name").value;
    var level=document.getElementById("level").value;
    var image=document.getElementById("imageData").value;
    
    
    $.post("ajex-events.php?q=submitEditCategory", {image: image,id:id,category_name:category_name,level:level}, function(result){
        
        if(result=="200")
        {
            $(".editBox").html("");
            $('.title_'+id).html(category_name)
        }
        else
        {
            alert(result);
        }
        //$('#newEntry_'+level).append(result);
    });
}

function deleteCategory(id)
{
    var category_id = id;
    
    if (confirm('Are you sure you want to delete?')) 
    {
        
    } 
    else 
    {
        return false;
    }
    
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            //alert(xmlhttp.responseText);
            if(xmlhttp.responseText=="200")
            {
                document.getElementById('row_'+category_id).style.display="none";
            }
            else
            {
                alert(xmlhttp.responseText);
            }
            
        }
    }
    xmlhttp.open("GET", "ajex-events.php?q=deleteCategory&category_id="+category_id);
    xmlhttp.send();  
        
  
}


function encodeImgtoBase64(element) 
{

      var img = element.files[0];
      var reader = new FileReader();
      reader.onloadend = function() {
          document.getElementById('imageData').value=reader.result;
      }

      reader.readAsDataURL(img);
}
    
function UploadCategoryImage(imageData)
{

    var fileUpload = document.getElementById('uploadFile');

    var regex = new RegExp('([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png|.jpeg)$');
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

            if (height == 120 && width == 120) {
                
                encodeImgtoBase64(imageData);
                
              //document.getElementById("sliderImageform").submit();
                document.getElementById('uploadTrigger').style.background="url("+image.src+")";
                document.getElementById('uploadTrigger').style.backgroundPosition ="top";
                document.getElementById('uploadTrigger').style.backgroundRepeat ="no-repeat";
                document.getElementById('uploadTrigger').style.backgroundSize ="contain";
                
                document.getElementById('logouploadText').style.display ="none";
                document.getElementById('logoPlaceholderimage').style.display ="none";
                
            } 
            else 
            {

              alert('Size 120x120')
              return false;
            }
          }

        }
      } else {
        alert('This browser does not support HTML5.')
        return false;
      }
    } else {
      alert('Please select a valid Image file.')
      return false;
    }
}


function ConfirmDelete()
{
  var x = confirm("Are you sure you want to delete?");
  if (x)
      return true;
  else
    return false;
}

function myFunction(data) {
    var x = document.getElementById(data);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}

function ClosePopup() {

    document.getElementById("PopupParent").style.display = "none";

}
$(document).ready(function(){
    setTimeout(function(){
        $('#error_message').fadeOut();
    }, 3000);
    setTimeout(function(){
        $('#sucess_message').fadeOut();
    }, 3000);

    var password = document.getElementById("new-password")
        , confirm_password = document.getElementById("confirme-password");

    function validatePassword(){
        if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;

});



// Highcharts.chart('pie-chart', {
//     chart: {
//         plotBackgroundColor: null,
//         plotBorderWidth: null,
//         plotShadow: false,
//         type: 'pie',
//         height: 200,
//     },
//     title: {
//         text: ''
//     },
//     tooltip: {
//         pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//     },
//     plotOptions: {
//         pie: {
//             allowPointSelect: true,
//             cursor: 'pointer',
//             dataLabels: {
//                 enabled: false
//             },
//             showInLegend: true
//         }
//     },
//     series: [{
//         name: '',
//         colorByPoint: true,
//         data: [{
//             name: 'Chrome',
//             y: 25,
//             sliced: true,
//             selected: true
//         }, {
//             name: 'Internet Explorer',
//             y: 75
//         }]
//     }]
// });

// $.getJSON(
//     'https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/usdeur.json',
//     function (data) {

//         Highcharts.chart('hightchartslogins', {
//             chart: {
//                 height: 200,
//                 zoomType: 'x'
//             },
//             title: {
//                 text: ''
//             },
//             subtitle: {
//                 text: document.ontouchstart === undefined ?
//                     'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
//             },
//             xAxis: {
//                 type: 'datetime'
//             },
//             yAxis: {
//                 title: {
//                     text: 'Exchange rate'
//                 }
//             },
//             legend: {
//                 enabled: false
//             },
//             plotOptions: {
//                 area: {
//                     fillColor: {
//                         linearGradient: {
//                             x1: 0,
//                             y1: 0,
//                             x2: 0,
//                             y2: 1
//                         },
//                         stops: [
//                             [0, Highcharts.getOptions().colors[0]],
//                             [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
//                         ]
//                     },
//                     marker: {
//                         radius: 2
//                     },
//                     lineWidth: 1,
//                     states: {
//                         hover: {
//                             lineWidth: 1
//                         }
//                     },
//                     threshold: null
//                 }
//             },

//             series: [{
//                 type: 'area',
//                 name: 'USD to EUR',
//                 data: data
//             }]
//         });
//     }
// );