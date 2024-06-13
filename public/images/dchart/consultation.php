<?php


require "../dashboards/query.php";

$patid=$_REQUEST['patid'];
require "patquery.php";
require "teeth.php";
require "teeth1.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="Admin, Dashboard, Bootstrap" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>AemmS</title>

    <link rel="apple-touch-icon" sizes="180x180" href="../../img/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../../img/favicon/favicon32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/favicon/favicon16x16.png" />
    <link rel="manifest" href="../../img/favicon/manifest.json" />
    <link rel="mask-icon" href="../../img/favicon/safari-pinned-tab.svg" color="#5bbad5" />
    <meta name="theme-color" content="#ffffff" />

    <!-- fonts -->
    <link rel="stylesheet" href="../../fonts/md-fonts/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="../../fonts/font-awesome-4.7.0/css/font-awesome.min.css" />
    <!-- animate css -->
    <link rel="stylesheet" href="../../libs/animate.css/animate.min.css" />

     <!-- jquery-loading -->
     <link rel="stylesheet" href="../../libs/jquery-loading/dist/jquery.loading.min.css" />

    <!-- octadmin main style -->
    <link id="pageStyle" rel="stylesheet" href="../../css/style.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style>

input[type="text"], input[type="email"], select {
    min-height: 20px;
}

input, select, textarea {
    -webkit-box-shadow: 0px 0px 20px -4px #c2cfd6;
    -moz-box-shadow: 0px 0px 20px -4px #c2cfd6;
    box-shadow: 0px 0px 5px -4px #c2cfd6;}
    
    
    .btn-primary {
    -webkit-box-shadow: 0px 5px 25px -3px #0358C7;
    -moz-box-shadow: 0px 5px 25px -3px #0358C7;
    box-shadow: 0px 5px 5px -3px #0358C7;
    }

.cardsc1 {
    
     height: 26em;
    overflow-y: auto;
    white-space: nowrap;
   
}

.cardmn1 {
    height: 25em;
    overflow-y: auto;
    white-space: nowrap;
}
.nav-link {
    display: block;
    padding: 0.5rem 0.25rem;
}
.card-body {
    flex: 1 1 auto;
    padding: 0.25rem;
}
a {
    text-decoration: none !important;
}

h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6 {
  margin-bottom: 0.5rem;
  font-family: inherit;
  font-weight: 600;
  line-height: 0.2;
  color: inherit; }
  
  
  .card {
  color: #536c79;
  margin-bottom: 0.5rem;
  border-color: white;
  -webkit-box-shadow: 0px 0px 20px -4px #c2cfd6;
  -moz-box-shadow: 0px 0px 20px -4px #c2cfd6;
  box-shadow: 0px 0px 10px -9px #0066cc; }
  
  
  
  .table th, .table td {
    padding: 0.1rem;
    vertical-align: top;
    border-top: 1px solid #c2cfd6;  
  
}

.tr {
    padding: 0.1rem;
    vertical-align: top;
    border-top: 1px solid #c2cfd6;  
  
}

.card-header {
    padding: 0.5rem 1.25rem;
    margin-bottom: 0;
    background-color: #f0f3f5;
    border-bottom: 1px solid #c2cfd6;
}

.btn-danger {
    -webkit-box-shadow: 0px 5px 25px -3px #F03434;
    -moz-box-shadow: 0px 5px 25px -3px #F03434;
    box-shadow: 0px 5px 15px -4px #F03434;
    }
    
  input[type="text"], input[type="email"], select {
    min-height: 15px;
}



hr {
    margin-top: 0.25rem;
    margin-bottom: 0.25rem;
    border: 0;
        border-top-width: 0px;
        border-top-style: none;
        border-top-color: currentcolor;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
} 
</style>


</head>
<script src="jquery.min.js"></script>
<script>

/*-----------------chk app---------------------*/

function chkap(){
	
	  var doc=document.getElementById("dnxapp").value;
	  var did='<?php echo $did;?>'
	  
	  //alert(did)
	   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'dt='+doc+'&doc='+did+ "&funct=chkap",
		success: function(data){
	           //	alert(data);
               // window.location.assign("appointment.php?patid="+data);
                $("#aptable").html(data);
				$('.bs-example-modal-tapp').modal('show');
                       
		}
		}); 
	
}
/*------------medcine---------------------*/


 function Addmed() {
            var medname = document.getElementById("medname");
            var pmedtrn = document.getElementById("pmedtrn");
            
            var pmedtim = document.getElementById("pmedtim");
            var pmedday = document.getElementById("pmedday");
            
           // var pin = document.getElementById("pin");
           
            <?php $i=1; ?>
            AddRow(medname.value, pmedtrn.value,pmedtim.value,pmedday.value,<?php echo $i; ?>);
              <?php $i++; ?>
            medname.value = "";
            pmedtrn.value = "";
            pmedtim.value = "";
            pmedday.value = "";
            //pin.value=  "";
           
        };
 
        function Removemed(button) {
            //Determine the reference of the Row using the Button.
            var row = button.parentNode.parentNode;
            var name = row.getElementsByTagName("TD")[0].innerHTML;
            if (confirm("Do you want to delete?")) {
 
                //Get the reference of the Table.
                var table = document.getElementById("tbnew2");
 
                //Delete the Table row using it's Index.
                table.deleteRow(row.rowIndex);
                 var tBody = document.getElementById("tbnew2").getElementsByTagName("TBODY")[0];
 
            //Add Row.
            row = tBody.insertRow(-1);
            }
        };
 
        function AddRow(medname, pmedtrn,pmedtim,pmedday,i) {
            //Get the reference of the Table's TBODY element.
            var tBody = document.getElementById("tbnew2").getElementsByTagName("TBODY")[0];
 
            //Add Row.
            row = tBody.insertRow(-1);
 
            //Add Name cell.
            
            
    
            
            
           // var cell = row.insertCell(-1);
            //cell.innerHTML = "<input type='text' id='pcid["+row.rowIndex+"]' style='width: 450px;' name='pc["+row.rowIndex+"]' value="+name+">";
                cell = row.insertCell(-1);
            var ipty = document.createElement("INPUT");
            ipty.type = "text";
            ipty.value = medname;
             ipty.size = "45";
            ipty.name="medname["+row.rowIndex+"]";
            ipty.id="medid["+row.rowIndex+"]";
            cell.appendChild(ipty);
            
             cell = row.insertCell(-1);
            var ipty = document.createElement("INPUT");
            ipty.type = "text";
            ipty.value = pmedtim;
             ipty.size = "10";
            ipty.name="pmedtim["+row.rowIndex+"]";
            ipty.id="mtimid["+row.rowIndex+"]";
            cell.appendChild(ipty);
                cell = row.insertCell(-1);
            var ipty = document.createElement("INPUT");
            ipty.type = "text";
            ipty.value = pmedtrn;
             ipty.size = "5";
            ipty.name="mtrn["+row.rowIndex+"]";
            ipty.id="mtrnid["+row.rowIndex+"]";
            cell.appendChild(ipty);           
            
            
            
            
            //Add Country cell.
            cell = row.insertCell(-1);
            cell.innerHTML = "<input type='text' id='pmeddayid["+row.rowIndex+"]' style='width: 50px; text-align:center' name='pmedday["+row.rowIndex+"]' value="+pmedday+">";
            
            //cell = row.insertCell(-1);
            //cell.innerHTML = pin;
            
           
 
            //Add Button cell.
            cell = row.insertCell(-1);
            var btnRemove = document.createElement("INPUT");
            btnRemove.type = "button";
            btnRemove.value = "Remove";
            btnRemove.setAttribute("onclick", "Removemed(this);");
            cell.appendChild(btnRemove);
            
            $("#medname").focus();
          
        }
        



function allr(v1)
{
   
   
     patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'moden.php',
                    data:  "patid="+patfid+"&patbkid="+patbid+"&teeth="+v1,
                    async: false,
                    success: function (msg)
                    {
                        
                      //alert(msg);
                       
                       $("#teethex").html(msg);
                            // window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });  
}

function summary1(bkid,patid){
    
   // alert(bkid)
    
     $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'bkid='+bkid+'&patid='+patid+ "&funct=summary1",
		success: function(data){
		  
         // alert(data)          		  
			$("#viewtb").html(data);
                      
		}
		});  
    
}



function saveupdt(){
    
    
    $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#updtteeth').serialize() + "&funct=teethupdt",
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             //window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                           location.reload(); 
                    }
                    
                });
}


function summary(bkid,patid){
    
   // alert(bkid)
    
     $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'patbkid='+bkid+'&patid='+patid+ "&funct=summary",
		success: function(data){
		  
         // alert(data)          		  
			$("#viewtb").html(data);
                      
		}
		});  
    
}



function conslovr(bkid)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patfid);
    // alert(bkid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: "funct=conslovr"+"&patid="+patfid+"&patbkid="+bkid,
                    async: false,
                    success: function (msg)
                    {
                        
                      //alert(msg);
                             window.location.assign("condnqview.php");
                                                  
                            
                    }
                    
                });
}


function deldc(val,bkid){
    
 
    
     patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data:  "funct=deldc"+"&patid="+patfid+"&patbkid="+patbid+"&delid="+val,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}
function totsave(){
    
     patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=totsave"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
    
}

function medhis()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=savmedhis"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}



function delclasi(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=delclasi",
		success: function(data){
	           	//alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function delmedhis(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=delmedhis",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}


function preg()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=savpreg"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

function delpreg(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=delpreg",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function finapp()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=finapp"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

function delfin(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=delfin",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}



function totchrg()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=totchrg"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

function delchrg(delval,bill)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+'&billno='+bill+ "&funct=delchrg",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function chk(det){
    
    
      patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data:  "patid="+patfid+"&patbkid="+patbid+"&teeth="+det+"&funct=dtmt",
                    async: false,
                    success: function (msg)
                    {
                        if(msg=='1')
                        {
                           alert(msg);  
                        }
                        else
                        {
                            
                            alert(msg);
                        }
                      
                       
                      // $("#teethex").html(msg);
                            // window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });  
}


function chxd()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#chxd').serialize() + "&funct=savchxd"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                      // alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

function delchxd(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=delchxd",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function denex()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#denex').serialize() + "&funct=savdenex"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

function deldenex(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=deldenex",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function treatmnt()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=savtreatmnt"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

function deltreatmnt(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=deltreatmnt",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function finchrge(){
    
     totrate= document.getElementById('tmtch').value; 
        discount= document.getElementById('tdisc').value; 
        
        if(discount=='')
        {
            discount=0;
        }
        // alert(totrate)
         //alert(discount)
             document.getElementById('tfinal').value = (parseFloat(totrate)-parseFloat(discount)).toFixed(3); 
}



function disclasi()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=disclasi"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

function med()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   // alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#medd').serialize() + "&funct=savmed"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                      // alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

 
  function prmed(){
    
     patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
  
                  $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:"funct=prmed"+"&patid="+patfid+"&patbkid="+patbid,
		success: function(data){
			$("#mp").html(data);
                      
		}
		});  
    
  }

function delmed(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=delmed",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function visitn()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=savvisitn"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

function delvisitn(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=delvisitn",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function nappnt()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=savnappnt"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}

function delnappnt(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=delnappnt",
		success: function(data){
	           	//alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function rmrk()
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    //alert(patbid);
     $.ajax({
                    type: 'POST',
                    url: 'consultation.ajax.php',
                    data: $('#treatmnt').serialize() + "&funct=savrmrk"+"&patid="+patfid+"&patbkid="+patbid,
                    async: false,
                    success: function (msg)
                    {
                        
                       //alert(msg);
                             window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
                                                  
                            
                    }
                    
                });
}


function winclosepr(){
    
      patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
    window.location.assign("leavcertifctview.php");    
           window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);
}

 function printDivpr3(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              divElements + "</body>";

            //Print Page
            window.print();
//window.onfocus=function(){ window.close();}
            //Restore orignal HTML
           document.body.innerHTML = oldPage;

          
        }
		
		function printDivpr(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              divElements + "</body>";

            //Print Page
            window.print();
//window.onfocus=function(){ window.close();}
            //Restore orignal HTML
           document.body.innerHTML = oldPage;

          
        }

function delpmp(delhval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delhId='+delhval+ "&funct=delpmp",
		success: function(data){
	           //	alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}

function delrmrk(delval)
{
    
    patfid='<?php echo $_REQUEST['patid']; ?>';
    patbid='<?php echo $_REQUEST['patbkid']; ?>';
   $.ajax({
		type: "POST",
		url: "consultation.ajax.php",
		data:'delId='+delval+ "&funct=delrmrk",
		success: function(data){
	           	//alert(data);
                   window.location.assign("consultation.php?patid="+patfid+"&patbkid="+patbid);    
		}
		});
}


$(document).ready(function() {
    
  //  alert('f')
      var discount= document.getElementById('tmtch').value;
     var exded= document.getElementById('tdisc').value; 
   
   
    if(discount==0)
    {discount=0;
   }
    else {discount=discount;} 
       if(exded==0)
    {exded=0;}
    else {exded=exded;}  
     
    var totval=(parseFloat(discount)-parseFloat(exded)).toFixed(3);
     
     document.getElementById('tfinal').value = totval; 
    
    
    });

</script>
<body class="app sidebar-fixed aside-menu-off-canvas aside-menu-hidden header-fixed">
    <header class="app-header navbar">
        <div class="hamburger hamburger--arrowalt-r navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto">
            <div class="hamburger-box">
                <div class="hamburger-inner"></div>
            </div>
        </div>
        <!-- end hamburger -->
         <?php 
        
        if($userjobid=='dc1')
        {
         ?> 
        <a class="navbar-brand" href="../dashboards/dashboard-cn.php">
            <strong> <i class="mdi mdi-home"></i>Home</strong>
        </a>
        <?php
        
        }
        else if($userjobid=='dn1')
        {
         ?> 
        <a class="navbar-brand" href="../dashboards/dashboard-dn.php">
            <strong> <i class="mdi mdi-home"></i>Home</strong>
        </a>
        <?php
        
        }
       else if($userjobid=='rc1')
        {
         ?> 
        <a class="navbar-brand" href="../dashboards/dashboard-rp.php">
            <strong> <i class="mdi mdi-home"></i>Home</strong>
        </a>
        <?php
        
        }
        else if($userjobid=='lb1')
        {
         ?> 
        <a class="navbar-brand" href="../dashboards/dashboard-lb.php">
            <strong> <i class="mdi mdi-home"></i>Home</strong>
        </a>
        <?php
        
        }
        else if($userjobid=='nr1')
        {
         ?> 
        <a class="navbar-brand" href="../dashboards/dashboard-nr.php">
            <strong> <i class="mdi mdi-home"></i>Home</strong>
        </a>
        <?php
        
        }
        else
        {
           ?> 
        <a class="navbar-brand" href="../dashboards/dashboard.php">
            <strong> <i class="mdi mdi-home"></i>Home</strong>
        </a>
        <?php  
            
        }
         ?>

        <div class="hamburger hamburger--arrowalt-r navbar-toggler sidebar-toggler d-md-down-none mr-auto">
            <div class="hamburger-box">
                <div class="hamburger-inner"></div>
            </div>
        </div>
        <!-- end hamburger -->

        <div class="navbar-search">
            <button type="submit" class="navbar-search-btn">
                <i class="mdi mdi-magnify"></i>
            </button>
            <input type="text" class="navbar-search-input" placeholder="Find User a user, team, meeting .." />
        </div>
        <!-- end navbar-search -->

        <ul class="nav navbar-nav ">
            <li class="nav-item ">
                <a class="nav-link" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-bell-ring-outline"></i>
                    <span class="notification hertbit"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right notification-list animated flipInY nicescroll-box">

                    <div class="dropdown-header">
                        <strong>Notification</strong>
                        <span class="badge badge-pill badge-theme pull-right"> new 5</span>
                    </div>
                    <!--end dropdown-header -->

                    <div class="wrap">

                        <a href="#" class="dropdown-item">
                            <div class="message-box">
                                <div class="u-img">
                                    <img src="../../img/products/product-1.jpg" alt="user" />
                                </div>
                                <!-- end u-img -->
                                <div class="u-text">
                                    <div class="u-name">
                                        <strong>A New Order has Been Placed </strong>
                                    </div>
                                    <small>2 minuts ago</small>
                                </div>
                                <!-- end u-text -->
                            </div>
                            <!-- end message-box -->
                        </a>
                        <!-- end dropdown-item -->

                        <a href="#" class="dropdown-item">
                            <div class="message-box">
                                <div class="u-img">
                                    <img src="../../img/products/product-2.jpg" alt="user" />
                                </div>
                                <div class="u-text">
                                    <div class="u-name">
                                        <strong>Order Updated</strong>
                                    </div>
                                    <small>10 minuts ago</small>
                                </div>
                                <!-- end u-text -->
                            </div>
                            <!-- end message-box -->
                        </a>
                        <!-- end dropdown-item -->

                        <a href="#" class="dropdown-item">
                            <div class="message-box">
                                <div class="u-img">
                                    <img src="../../img/products/product-3.jpg" alt="user" />
                                </div>
                                <!-- end u-img -->
                                <div class="u-text">
                                    <div class="u-name">
                                        <strong>A New Order has Been Placed </strong>
                                    </div>
                                    <small>30 minuts ago</small>
                                </div>
                                <!-- end u-text -->
                            </div>
                            <!-- end message-box -->
                        </a>
                        <!-- end dropdown -->

                        <a href="#" class="dropdown-item">
                            <div class="message-box">
                                <div class="u-img">
                                    <img src="../../img/products/product-4.jpg" alt="user" />
                                </div>
                                <!-- end u-img -->
                                <div class="u-text">
                                    <div class="u-name">
                                        <strong> Order has Been Rated </strong>
                                    </div>
                                    <small>32 minuts ago</small>
                                </div>
                                <!-- end u-text -->
                            </div>
                            <!-- end message-box -->
                        </a>
                        <!-- end dropdown -->
                    </div>
                    <!-- end wrap -->

                    <div class="dropdown-footer ">
                        <a href="./dashboard-project-management.html">
                            <strong>See all messages (150) </strong>
                        </a>
                    </div>
                    <!-- end dropdown-footer -->
                </div>
                <!-- end notification-list -->

            </li>
            <!-- end nav-item -->

            <li class="nav-item ">
                <a class="nav-link" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-comment-multiple-outline"></i>
                    <span class="notification hertbit"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right message-list animated flipInY nicescroll-box">

                    <div class="dropdown-header">
                        <strong>Messages</strong>
                        <span class="badge badge-pill badge-theme pull-right"> new 15</span>
                    </div>
                    <!-- end dropdown-header -->
                    <div class="wrap">

                        <a href="#" class="dropdown-item">
                            <div class="message-box">
                                <div class="u-img">
                                    <img src="../../img/users/207.jpg" alt="user" />
                                    <span class="notification online"></span>
                                </div>
                                <!-- end u-img -->
                                <div class="u-text">
                                    <div class="u-name">
                                        <strong>Natalie Wall</strong>
                                    </div>
                                    <p class="text-muted">Anyways i would like just do it</p>
                                    <small>2 minuts ago</small>
                                </div>
                                <!-- end u-text -->
                            </div>
                            <!-- end message-box -->
                        </a>
                        <!-- end dropdown-item -->

                        <a href="#" class="dropdown-item">
                            <div class="message-box">
                                <div class="u-img">
                                    <img src="../../img/users/205.jpg" alt="user" />
                                    <span class="notification offline"></span>
                                </div>
                                <!-- end u-img -->
                                <div class="u-text">
                                    <div class="u-name">
                                        <strong>Steve johns</strong>
                                    </div>
                                    <p class="text-muted">There is Problem inside the Application</p>
                                    <small>10 minuts ago</small>
                                </div>
                                <!-- end u-text -->
                            </div>
                            <!-- end message-box -->
                        </a>
                        <!-- end dropdown-item -->

                        <a href="#" class="dropdown-item">
                            <div class="message-box">
                                <div class="u-img">
                                    <img src="../../img/users/216.jpg" alt="user" />
                                    <span class="notification buzy"></span>
                                </div>
                                <!-- end u-img -->
                                <div class="u-text">
                                    <div class="u-name">
                                        <strong>Taniya Jan</strong>
                                    </div>
                                    <p class="text-muted">Please Checkout The Attachment</p>
                                    <small>30 minuts ago</small>
                                </div>
                                <!-- end u-text -->
                            </div>
                            <!-- end message-box -->
                        </a>
                        <!-- end dropdown-item -->

                        <a href="#" class="dropdown-item">
                            <div class="message-box">
                                <div class="u-img">
                                    <img src="../../img/users/218.jpg" alt="user" />
                                    <span class="notification away"></span>
                                </div>
                                <!-- end u-img -->
                                <div class="u-text">
                                    <div class="u-name">
                                        <strong>Tim Johns</strong>
                                    </div>
                                    <!-- end u-name -->
                                    <p class="text-muted">Anyways i would like just do it</p>
                                    <small>32 minuts ago</small>
                                </div>
                                <!-- end u-text -->
                            </div>
                            <!-- end message-box -->
                        </a>
                        <!-- end dropdown-item -->
                    </div>
                    <!-- end wrap -->
                    <div class="dropdown-footer ">
                        <a href="./dashboard-project-management.html">
                            <strong>See all messages (150) </strong>
                        </a>
                    </div>
                    <!-- end dropdown-footer -->
                </div>
                <!-- end message-list -->
            </li>
            <!-- end nav-item -->


            <li class="nav-item ">
                <a class="nav-link" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-altimeter"></i>
                    <span class="notification hertbit"></span>
                </a>
                <!-- end navlink -->
                <div class="dropdown-menu dropdown-menu-right task-list animated flipInY nicescroll-box">

                    <div class="dropdown-header">
                        <strong>Task List</strong>
                        <span class="badge badge-pill badge-theme pull-right"> new 3</span>
                    </div>
                    <!-- end dropdown-header -->
                    <div class="wrap">
                        <a href="#" class="dropdown-item">
                            <strong>Task 1</strong>
                            <small class="pull-right">50% Complete</small>
                            <div class="progress xs">
                                <div class="progress-bar bg-danger" style="width: 50%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">

                                </div>
                            </div>
                        </a>
                        <!-- end dropdown-item -->

                        <a href="#" class="dropdown-item">
                            <strong>Task 2</strong>
                            <small class="pull-right">20% Complete</small>

                            <div class="progress xs">
                                <div class="progress-bar bg-success" style="width: 20%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">

                                </div>
                            </div>
                        </a>

                        <!-- end dropdown-item -->
                        <a href="#" class="dropdown-item">
                            <strong>Task 3</strong>
                            <small class="pull-right">80% Complete</small>

                            <div class="progress xs ">
                                <div class="progress-bar bg-warning" style="width: 80%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">

                                </div>
                            </div>
                        </a>
                        <!-- end dropdown-item -->

                        <a href="#" class="dropdown-item">
                            <strong>Task 4</strong>
                            <small class="pull-right">60% Complete</small>

                            <div class="progress xs ">
                                <div class="progress-bar bg-info" style="width: 60%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">

                                </div>
                            </div>
                        </a>
                        <!-- end dropdown-item -->
                    </div>
                    <!-- end wrap -->
                    <div class="dropdown-footer ">
                        <a href="./dashboard-project-management.html">
                            <strong>view all task (20) </strong>
                        </a>
                    </div>
                    <!-- end dropdown-footer -->

                </div>
                <!-- dropdown-menu -->
            </li>
            <!-- end navitem -->

            <li class="nav-item dropdown">
                <a class="btn btn-round btn-theme btn-sm" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">

                   <span class=""><?php echo ucwords($name); ?>
                        <i class="fa fa-arrow-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right user-menu animated flipInY ">
                     <div class="wrap">
                        <div class="dw-user-box">
                            <div class="u-img">
                                <img src="../../img/users/<?php echo $pfid; ?>.jpg" alt="user" />
                            </div>
                            <div >
                                <h5><?php echo ucwords($name); ?></h5>
                                <p class="text-muted">1wall@gmail.com</p>
                                <a href="#" class="btn btn-round btn-theme btn-sm">View Profile</a>
                            </div>
                        </div>
                        <!-- end dw-user-box -->

                        <a class="dropdown-item" href="#">
                            <i class="fa fa-user"></i> Profile</a>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-wrench"></i> Settings</a>

                        <div class="divider"></div>

                        <a class="dropdown-item" href="../../logout.php">
                            <i class="fa fa-lock"></i> Logout</a>
                    </div>
                    <!-- end wrap -->
                </div>
                <!-- end dropdown-menu -->
            </li>
            <!-- end nav-item -->


        </ul>

        
    </header>
    <!-- end header -->

    <div class="app-body">
        <div class="sidebar" id="sidebar">
            <nav class="sidebar-nav" id="sidebar-nav-scroller">
                <ul class="nav">
                  <li class="nav-title">Menus</li>
                  <?php

                        while ( $menurow = mysql_fetch_array( $menuresult ) )
                        {
                            if ( $menurow['mId'] == 'm01' )
                            {
                                $y = "";
                            } else
                            {
                                $y = 'hidden';
                            }
                                $submenu = "select a.sMenuName,a.smPath from submenu a INNER JOIN menus b on b.mId=a.mId where a.mId='".$menurow['mId']."' and a.status='Y' order by a.smId asc ";                            
                                $subresult = mysql_query( $submenu ) or die( "Query failed" . mysql_error() );
                                
                               
                            ?>

                  
                    
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i <?php //echo $y; ?> class="mdi <?php echo $menurow['menIcon']; ?>"></i> <span><?php echo $menurow['menuName']; ?></span></a>

                        <ul class="nav-dropdown-items">
                        <?php 
                         while ( $subrow = mysql_fetch_array( $subresult ) )
                                  { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $subrow['smPath']; ?>"> <?php echo $subrow['sMenuName']; ?></a>
                            </li>
                            <?php   } ?> 
                        </ul>

                    </li>
                    <?php } ?>
                </ul>
            </nav>

        </div>
        <!-- end sidebar -->

        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb bc-colored bg-theme" id="breadcrumb">
               
            </ol>

            <div class="container-fluid">
                <div class="animated fadeIn">
                <div class="col-sm-12" >

                            <div class="card">
                                <div class="card-header text-theme">
                                <small>Patient</small>
                                    <strong>Details</strong>                                 
                    
                                </div>
                                <div class="card-body">
                                 <?php
                                 
                                  if(isset($_REQUEST['billno']))
                                 {
                                    $billno=$_REQUEST['billno'];
                                    }
                                 else
                                 {
                                    $billno='';
                                    }
                                    $patrow=mysql_fetch_array($patresult);

                                      if($patrow['patAge']>'2')
                               {
                                $patrow['patAge']=floor($patrow['patAge']);
                               }
                               else
                               {
                                $patrow['patAge']=$patrow['patAge'];
                               }
                                    
                                    //$buktype=$patrow['patbkType'];
                                   // $patTypeval=$patrow['patTypeval'];
                                    
                                    if($patrow['patType']=='G')
                                            {$ptype='General';}
                                            
                                             if($patrow['patType']=='I')
                                            {

                                            	//$ptype='';

                                         if($patrow['patTypeval']=='1')
                                            {
                                                $ptype='NATIONAL LIFE AND GENERAL INSURANCE';
                                            }
                                            else if($patrow['patTypeval']=='2')
                                            {
                                                $ptype='NEXTCARE';
                                            }
                                            else if($patrow['patTypeval']=='3')
                                            {
                                                $ptype='VIPUL BETTER CARE';
                                            }
                                            else if($patrow['patTypeval']=='4')
                                            {
                                                $ptype='MEDNET  INSURANCE';
                                            }else if($patrow['patTypeval']=='5')
                                            {
                                                $ptype='SAICOHEALTH INSURANCE';
                                            }
											 else if($patrow['patTypeval']=='6')
                                            {
                                                $ptype='AETNA';
                                            }
											 else if($patrow['patTypeval']=='7')
                                            {
                                                $ptype='OMAN QATAR INSURANCE COMPANY';
                                            }

                                    }
                                             if($patrow['patType']=='C')
                                            {
                                                
                                                   if($patrow['patTypeval']=='1')
                                            {
                                                $ptype='COMPANY MEMBERSHIP';
                                            }
                                            else  if($patrow['patTypeval']=='2')
                                            {
                                                $ptype='FAMILY MEMBERSHIP';
                                            }
                                            else  if($patrow['patTypeval']=='3')
                                            {
                                                $ptype='TAJ HYPERMARKET';
                                            }
                                            }
                                            
                                            
                                    
                                    ?> 
                                    
                                    <table class="table"  width="100%"  style="margin-bottom: -5px;">
                                    
                                    <tr style="color: black; font-size: 18px;">
                                    
                                    <td style="width: 5%; height: 30px;">File # : &nbsp;</td>
                                    <th style="text-align: left; color: blue;"><?php echo $patrow['patFile']; ?></th>
                                    <td style="width: 7%;">Name: &nbsp;</td>
                                    <th style="color: blue;"><?php echo $patrow['patName']." ".$patrow['patTribe']; ?></th>
                                    <td style="width: 5%;">Age: &nbsp;</td>
                                    <th style="color: blue;"><?php echo $patrow['patAge']; ?></th>
                                    <td style="width: 5%;">Gender: &nbsp;</td>
                                    <th style="color: blue;"><?php echo $patrow['patSex']; ?></th>
                                    <td style="width: 8%;">Country: &nbsp;</td>
                                    <th style="color: blue;"><?php echo $patrow['patCtry']; ?></th>
                                    <td style="width: 8%;">Type: &nbsp;</td>
                                    <th style="color: blue;"><?php echo $ptype; ?></th>
                                    </tr>
                                    
                                    </table>
                            </div>
                </div>
                    <div class="row">
                        
                        <!--end col-->

                        <div class="col-sm-6" >

                            <div class="card">
                                <div class="card-header text-theme">
                                <small>Patient</small>
                                    <strong>Consultation</strong>
                                                          <span style="margin-left: 50%;"> Case Sheets<div  class="hamburger hamburger--arrowalt-r navbar-toggler aside-menu-toggler ">
            <div class="hamburger-box">
                <div class="hamburger-inner"> </div>
            </div>
        </div></span>
                                </div>
                                <div class="card-body">
                                <div class="cardmn">
                                <table style="width: 100%;">
                                <tr>
                                
                                <td>&nbsp;&nbsp;<img src="dchart/top/t71.png" alt="q18" data-toggle="modal" data-target=".bs-example-modal-lg18"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/t72.png" alt="q17" data-toggle="modal" data-target=".bs-example-modal-lg17"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/t73.png" alt="q16" data-toggle="modal" data-target=".bs-example-modal-lg16"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/t74.png" alt="q15" data-toggle="modal" data-target=".bs-example-modal-lg15"/></td>
                                <td>&nbsp;<img src="dchart/top/t11.png" alt="q14" data-toggle="modal" data-target=".bs-example-modal-lg14"/></td>
                                <td>&nbsp;<img src="dchart/top/t12.png" alt="q13" data-toggle="modal" data-target=".bs-example-modal-lg13"/></td>
                                <td>&nbsp;<img src="dchart/top/t13.png" alt="q12" data-toggle="modal" data-target=".bs-example-modal-lg12"/></td>
                                <td>&nbsp;<img src="dchart/top/t14.png" alt="q11" data-toggle="modal" data-target=".bs-example-modal-lg11"/></td>
                                <td>&nbsp;<img src="dchart/top/t21.png" alt="q21" data-toggle="modal" data-target=".bs-example-modal-lg21"/></td>
                                <td>&nbsp;<img src="dchart/top/t22.png" alt="q22" data-toggle="modal" data-target=".bs-example-modal-lg22"/></td>
                                <td>&nbsp;<img src="dchart/top/t23.png" alt="q23" data-toggle="modal" data-target=".bs-example-modal-lg23"/></td>
                                <td>&nbsp;<img src="dchart/top/t24.png" alt="q24" data-toggle="modal" data-target=".bs-example-modal-lg24"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/t81.png" alt="q25" data-toggle="modal" data-target=".bs-example-modal-lg25"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/t82.png" alt="q26" data-toggle="modal" data-target=".bs-example-modal-lg26"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/t83.png" alt="q27" data-toggle="modal" data-target=".bs-example-modal-lg27"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/t84.png" alt="q28" data-toggle="modal" data-target=".bs-example-modal-lg28"/></td> 
                                </tr>
                                <tr>
                                <td style="text-align: center;">8</td>
                                <td style="text-align: center;">7</td>
                                <td style="text-align: center;">6</td>
                                <td style="text-align: center;">5</td>
                                <td style="text-align: center;">4</td>
                                <td style="text-align: center;">3</td>
                                <td style="text-align: center;">2</td>
                                <td style="text-align: center;">1</td>
                                <td style="text-align: center;">1</td>
                                <td style="text-align: center;">2</td>
                                <td style="text-align: center;">3</td>
                                <td style="text-align: center;">4</td>
                                <td style="text-align: center;">5</td>
                                <td style="text-align: center;">6</td>
                                <td style="text-align: center;">7</td>
                                <td style="text-align: center;">8</td>
                                </tr>
                                <tr>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/b71.png" alt="q38" data-toggle="modal" data-target=".bs-example-modal-lg38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/b72.png" alt="q37" data-toggle="modal" data-target=".bs-example-modal-lg37"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/b73.png" alt="q36" data-toggle="modal" data-target=".bs-example-modal-lg36"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/b74.png" alt="q35" data-toggle="modal" data-target=".bs-example-modal-lg35"/></td>
                                <td>&nbsp;<img src="dchart/bottom/b11.png" alt="q34" data-toggle="modal" data-target=".bs-example-modal-lg34"/></td>
                                <td>&nbsp;<img src="dchart/bottom/b12.png" alt="q33" data-toggle="modal" data-target=".bs-example-modal-lg33"/></td>
                                <td>&nbsp;<img src="dchart/bottom/b13.png" alt="q32" data-toggle="modal" data-target=".bs-example-modal-lg32"/></td>
                                <td>&nbsp;<img src="dchart/bottom/b14.png" alt="q31" data-toggle="modal" data-target=".bs-example-modal-lg31"/></td>
                                <td>&nbsp;<img src="dchart/bottom/b21.png" alt="q41" data-toggle="modal" data-target=".bs-example-modal-lg41"/></td>
                                <td>&nbsp;<img src="dchart/bottom/b22.png" alt="q42" data-toggle="modal" data-target=".bs-example-modal-lg42"/></td>
                                <td>&nbsp;<img src="dchart/bottom/b23.png" alt="q43" data-toggle="modal" data-target=".bs-example-modal-lg43"/></td>
                                <td>&nbsp;<img src="dchart/bottom/b24.png" alt="q44" data-toggle="modal" data-target=".bs-example-modal-lg44"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/b81.png" alt="q45" data-toggle="modal" data-target=".bs-example-modal-lg45"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/b82.png" alt="q46" data-toggle="modal" data-target=".bs-example-modal-lg46"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/b83.png" alt="q47" data-toggle="modal" data-target=".bs-example-modal-lg47"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/b84.png" alt="q48" data-toggle="modal" data-target=".bs-example-modal-lg48"/></td> 
                                </tr>
                                </table>
                                
                                                 <br />
                                                   <hr /> 
                                                   <?php
                                                   
                                               
                                                $bkid=$_REQUEST['patbkid'];
                                                   
                                                   $tqry1="select sum(totdis) as tot1 from denchrg where patid='".$patrow['patFile']."' and bkid='".$bkid."'";
                                                   $tqryli1=mysql_query($tqry1) or die("Query failedf".mysql_error());
                                                   $tqryrw1=mysql_fetch_array($tqryli1);
                                                   $tc1=$tqryrw1['tot1'];
                                                   
                                   $pritqry="SELECT denbal FROM `dentbalnc` where patFile='".$patrow['patFile']."' order by id desc LIMIT 1";
                       // echo $pritqry;exit;
                         
                        $pritqryli=mysql_query($pritqry) or die("Query failed3".mysql_error());
                        $pritqryrw=mysql_fetch_array($pritqryli);
                         
                       $blamnt=($pritqryrw['denbal']+$blamnt);
                                                   
                                                
                                                   $tc=$blamnt;
                                                   
                                                  
                                                   $tqry="select sum(tc) as totch from denconsultation where patbkid='".$_REQUEST['patbkid']."'";
                                                   $tqryli=mysql_query($tqry) or die("Query failedf".mysql_error());
                                                   $tqryrw=mysql_fetch_array($tqryli);
												   $ta= $tqryrw['totch'];
                                                   
                                                    ?>
                                                                        <form class="form-horizontal" method="POST" id="treatmnt" action="">
                                             <table style="width: 100%;">
                                             <tr>
                                             <td >Medical History</td>
                                             <td style="width: 200px; margin-right: -50px;" colspan="3">
                                             <input type="text" style="width: 350px;"   id="medhistry" name="medhistry"/></td>
                                             <td><button onclick="medhis()" class="btn btn-success btn-sm">Add</button></td>
                                             </tr>
                                              <tr><td colspan="5"><br /><hr /></td></tr>
                                              <tr>
                                             <td>Pregnancy</td>
                                             <td colspan="3"><input type="text" style="width: 350px;"   id="denpreg" name="denpreg"/></td>
                                             <td><button onclick="preg()" class="btn btn-success btn-sm">Add</button></td>
                                             </tr>
                                              <tr><td colspan="5"><br /><hr /></td></tr>
                                             <tr>
                                             <td colspan="4">Medicine</td>
                                             <!--td><input type="text" style="width: 250px;" id="medname" name="medname"/></td>
                                             <td><input type="text" style="width: 100px;" id="medf1" name="medf1"/></td>
                                             <td><input type="text" style="width: 100px;" id="medf2" name="medf2"/></td>
                                             <td><button onclick="med()" class="btn btn-success btn-sm">Add</button></td-->
                                             <td> <button type="button" class="btn btn-success btn-sm" onclick="popmed()" data-toggle="modal" data-target=".bs-example-modal-lg4">        
                                           Add</button></td>
                                             </tr>
                                             
                                              <tr><td colspan="5"><br /><hr /></td></tr>
                                             <tr>
                                             <td>Description</td>
                                             <td style="width: 200px; margin-right: -50px;"><textarea rows="2" name="vdesc" cols="22"></textarea></td>
                                             
                                            <td><button onclick="visitn()" class="btn btn-success btn-sm">Add</button></td>
                                             </tr>
                                             <tr><td colspan="5"><br /><hr /></td></tr>
                                             <tr>
                                             <td>Next Appointment</td>
                                             <td><input type="date" class="form-control" style="width: 250px;"   id="dnxapp" name="dnxapp" onchange="chkap()" />
                                             </td>
                                            <td>Slot</td>
                                             <td><input type="text" class="form-control" style="width: 250px;"   id="slot" name="slot"/>
                                             </td>
											  <td>
                                             <a data-toggle="modal" data-target=".bs-example-modal-tapp" class="btn btn-success" style="margin-top: 1px; background-color: white; height: 25px; width: 20px; vertical-align: middle;" onchange="chkap()"></a>
				
                                             </td>
										</tr>
                                             <tr>
											 <td > Time</td><td>
                                             <input type="text" class="form-control" style='width:100px; text-align:center' id="inputDate1" name="bktim" value="<?php echo $patrw['patbktm']; ?>" data-plugin="formatter" data-pattern="[[99]]:[[99]],[[aa]]" />
                                        </td>
										<td > AM/PM</td>
										 <td>
                                            <select id="select2" class="form-control" id="apm" name="apm" >
                                                    
                                                    <option value="AM" >AM</option>
                                                    <option value="PM" >PM</option>
                                                </select></td>
                                             <td><button onclick="nappnt()" class="btn btn-success btn-sm">Add</button></td>
                                             </tr>
                                              <tr><td colspan="5"><br /><hr /></td></tr>
                                              <tr>
                                             <!--td>Total Charge</td>
                                             <td colspan="5" style="width: 200px;">
                                             <input type="text" style="width: 100px;" value="<?php echo $tc; ?>"  id="tmtch" name="tmtch"/>
                                             Discount <input type="text" style="width: 100px;" onkeyup="finchrge()"  id="tdisc" name="tdisc"/>
                                             Final Amount<input type="text" style="width: 100px;"  id="tfinal" name="tfinal"/>
                                             <button onclick="totchrg()" class="btn btn-success btn-sm">Add</button>
                                             </td-->
                                              <td>Previous Balance</td>
                                             <td colspan="5" style="width: 200px;">
                                             <input type="text" class="form-control" style="width: 300px; text-align:center; border-color: blue;" value="<?php echo $tc; ?>"  />
                                             <!--button onclick="totchrg()" class="btn btn-success btn-sm">Add</button-->
                                             </td>
                                             <td></td>
                                             
                                             </tr>
											 <tr><td colspan="5"><br /><hr /></td></tr>
                                              <tr>
                                             <td colspan="5" style="width: 200px;">Total Charge
                                             <input type="text" style="width: 100px;" value="<?php echo $ta; ?>"  id="tmtch" name="tmtch"/>
                                             Discount <input type="text" style="width: 100px;" onkeyup="finchrge()" value="<?php echo $tc1; ?>"   id="tdisc" name="tdisc"/>
                                             Final Amount<input type="text" style="width: 100px;"  id="tfinal" name="tfinal"/>
                                             <button onclick="totchrg()" class="btn btn-success btn-sm">Add</button>
                                             </td>
                                             
                                             
                                             </tr>
                                              <tr><td colspan="5"><br /><hr /></td></tr>
											  
                                             <tr>
                                             <td>Disease Classification</td>
                                             <?php 
                                             
                                             $patqryd='';
                                            $patqryd.="SELECT * FROM `diseasetype` where distype='D' and status='Y'";
                                             $patqresultd=mysql_query($patqryd) or die("Query failed".mysql_error());
                                               
                                             
                                             ?>
                                             <td colspan="3">
                                             
                                                  
                                                    <select class="form-control" name="dendis">
                                                        <option value="k">------------------------------Select------------------------------</option>
                                                       <?php  
                                                        while($patrowd=mysql_fetch_array($patqresultd))
                                                        
                                                         { ?>
                                                            
                                                         <option value="<?php  echo $patrowd['did']; ?>"><?php echo $patrowd['disname']; ?></option>   
                                                       <?php  } ?>
                                                    </select>
                                             </td>
                                             <td><button onclick="disclasi()" class="btn btn-success btn-sm">Add</button></td>
                                             </tr>
                                              <tr><td colspan="5"><br /><hr /></td></tr>
                                              <tr>
                                              <td>Remark</td>
                                              <td colspan="3"><textarea rows="2" name="dnrmk" cols="45"></textarea></td>
                                              <td><button onclick="rmrk()" class="btn btn-success btn-sm">Add</button></td>
                                              </tr>
                                              
                                              <tr><td colspan="5"><br /><hr /></td></tr>
                                              <tr>
                                              <td>
                                              Final Appoitment
                                              </td>
                                              <td colspan="3">
                                              <select name="fincon" class="form-control">
                                              <option value="">-------------------------------Select-------------------------</option>
                                              <option value="Yes">Yes</option>
                                              <option value="No">No</option>
                                              </select>
                                              </td>
                                              <td><button onclick="finapp()" class="btn btn-success btn-sm">Add</button></td>
                                              </tr>
                                              <!--tr><td colspan="5" style="text-align: center;">
                                              <button type="button" style=" padding: 1px 50px;"  onclick="totsave()" class="btn btn-success btn-sm" >Add</button>
                                              </td></tr-->
                                             </table>
                                              </form>   
                                                   
                                      </div>
                                </div>
                                <!-- end card-body -->
                            </div>
                            <!-- end card -->

                        </div>
                        
                        <!--/.col-->
                        <div class="col-sm-6" >

                            <div class="card">
                                <div class="card-header text-theme">
                                <small>Patient</small>
                                    <strong>Summary</strong>
                                    
                                </div>
                                <div class="cardsc">
                                <!------------------------------------------------------teeth History----------------------------------------------->
                                <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry='';
                                            $patqry.="SELECT * FROM `denconsultation` where patbkid='".$patbookId."' ";
                                            $patqresult=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $pillrow1=mysql_fetch_array($patqresult);
                                            
                                          // echo $pillrow1['teeth'];
                                            if($pillrow1['teeth']!='')
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            require "doneqry.php";
                                            //$patest=mysql_query($patqry) or die("Query failed".mysql_error());
                                            //$patestrw=mysql_fetch_array($patest);
                                            
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                
                                 <div class="table-responsive">
                                 
                                 
                                 
                                 
                                          
                                        <table  style="width: 100%;">
                                <tr>
                                
                                <td>&nbsp;&nbsp;<img src="dchart/top/<?php echo $val18; ?>.png" onclick="allr('q18')" onmouseover="chk1('q18')" alt="q18" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/<?php echo $val17; ?>.png" onclick="allr('q17')" onmouseover="chk1('q17')" alt="q17" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/<?php echo $val16; ?>.png" onclick="allr('q16')" onmouseover="chk1('q16')" alt="q16" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/<?php echo $val15; ?>.png" onclick="allr('q15')" onmouseover="chk1('q15')" alt="q15" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/top/<?php echo $val14; ?>.png" onclick="allr('q14')" onmouseover="chk1('q14')" alt="q14" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/top/<?php echo $val13; ?>.png" onclick="allr('q13')" onmouseover="chk1('q13')" alt="q13" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/top/<?php echo $val12; ?>.png" onclick="allr('q12')" onmouseover="chk1('q12')" alt="q12" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/top/<?php echo $val11; ?>.png" onclick="allr('q11')" onmouseover="chk1('q11')" alt="q11" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/top/<?php echo $val21; ?>.png" onclick="allr('q21')" onmouseover="chk1('q21')" alt="q21" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/top/<?php echo $val22; ?>.png" onclick="allr('q22')" onmouseover="chk1('q22')" alt="q22" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/top/<?php echo $val23; ?>.png" onclick="allr('q23')" onmouseover="chk1('q23')" alt="q23" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/top/<?php echo $val24; ?>.png" onclick="allr('q24')" onmouseover="chk1('q24')" alt="q24" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/<?php echo $val25; ?>.png" onclick="allr('q25')" onmouseover="chk1('q25')"  alt="q25" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/<?php echo $val26; ?>.png" onclick="allr('q26')" onmouseover="chk1('q26')" alt="q26" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/<?php echo $val27; ?>.png" onclick="allr('q27')" onmouseover="chk1('q27')" alt="q27" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/top/<?php echo $val28; ?>.png" onclick="allr('q28')" onmouseover="chk1('q28')" alt="q28" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td> 
                                </tr>
                                <tr>
                                <td style="text-align: center;">8</td>
                                <td style="text-align: center;">7</td>
                                <td style="text-align: center;">6</td>
                                <td style="text-align: center;">5</td>
                                <td style="text-align: center;">4</td>
                                <td style="text-align: center;">3</td>
                                <td style="text-align: center;">2</td>
                                <td style="text-align: center;">1</td>
                                <td style="text-align: center;">1</td>
                                <td style="text-align: center;">2</td>
                                <td style="text-align: center;">3</td>
                                <td style="text-align: center;">4</td>
                                <td style="text-align: center;">5</td>
                                <td style="text-align: center;">6</td>
                                <td style="text-align: center;">7</td>
                                <td style="text-align: center;">8</td>
                                </tr>
                                <tr>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/<?php echo $val38; ?>.png" onclick="allr('q38')" onmouseover="chk1('q38')" alt="q38" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/<?php echo $val37; ?>.png" onclick="allr('q37')" onmouseover="chk1('q37')" alt="q37" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/<?php echo $val36; ?>.png" onclick="allr('q36')" onmouseover="chk1('q36')" alt="q36" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/<?php echo $val35; ?>.png" onclick="allr('q35')" onmouseover="chk1('q35')" alt="q35" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/bottom/<?php echo $val34; ?>.png" onclick="allr('q34')" alt="q34" onmouseover="chk1('q34')" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/bottom/<?php echo $val33; ?>.png" onclick="allr('q33')" alt="q33" onmouseover="chk1('q33')" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/bottom/<?php echo $val32; ?>.png" onclick="allr('q32')" alt="q32" onmouseover="chk1('q32')" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/bottom/<?php echo $val31; ?>.png" onclick="allr('q31')" alt="q31" onmouseover="chk1('q31')" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/bottom/<?php echo $val41; ?>.png" onclick="allr('q41')" alt="q41" onmouseover="chk1('q41')" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/bottom/<?php echo $val42; ?>.png" onclick="allr('q42')" alt="q42" onmouseover="chk1('q42')" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/bottom/<?php echo $val43; ?>.png" onclick="allr('q43')" alt="q43" onmouseover="chk1('q43')" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;<img src="dchart/bottom/<?php echo $val44; ?>.png" onclick="allr('q44')" alt="q44" onmouseover="chk1('q44')" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/<?php echo $val45; ?>.png" onclick="allr('q45')" onmouseover="chk1('q45')" alt="q45" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/<?php echo $val46; ?>.png" onclick="allr('q46')" onmouseover="chk1('q46')" alt="q46" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/<?php echo $val47; ?>.png" onclick="allr('q47')" onmouseover="chk1('q47')" alt="q47" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td>
                                <td>&nbsp;&nbsp;<img src="dchart/bottom/<?php echo $val48; ?>.png" onclick="allr('q48')" onmouseover="chk1('q48')" alt="q48" data-toggle="modal" data-target=".bs-example-modal-lgr38"/></td> 
                                </tr>
                                </table>
                                    </div>
                                 
                                 </div>
                                </div>
                                
                                
                                <!------------------------------------------------------Medical History----------------------------------------------->
                                 <!------------------------------------------------------Meddical History----------------------------------------------->
                                <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry='';
                                            $patqry.="SELECT * FROM `dentlhist` where patbkid='".$patbookId."' ";
                                            $patqresult=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $pillrow1=mysql_fetch_array($patqresult);
                                            
                                            $patest=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $patestrw=mysql_fetch_array($patest);
                                            if(($pillrow1['dnhid']!='')&&($pillrow1['denhistry']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div class="table-responsive">
                                        <table class=" color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center;">Medical History</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult1=mysql_query($patqry) or die("Query failed".mysql_error());
                                            while($pillrow=mysql_fetch_array($patqresult1))
                                            {
                                                
                                                $prillo=$pillrow['denhistry'];
                                          $prillw = wordwrap($prillo, 45, "\n", true);
                                            $prillp = htmlentities($prillw);
                                            $prill = nl2br($prillp);
                                          ?>
                                                <tr >
                                                    <td style="vertical-align: middle;"><?php echo $i; ?></td>
                                                    <td style="vertical-align: middle;"><?php echo $prill; ?></td>
                                                    <td style="text-align: right; vertical-align: middle;"><button type="button" style="  padding: 1px 1px;" id="btn" onclick="delmedhis('<?php echo $pillrow['dnhid']?>')" class="btn btn-danger btn-sm"></i> Delete</button>
                        </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                
                                
                               <!------------------------------------------------------Pregnancy----------------------------------------------->
                                
                                <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry='';
                                            $patqry.="SELECT * FROM `dentpreg` where patbkid='".$patbookId."' ";
                                            $patqresult=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $pillrow1=mysql_fetch_array($patqresult);
                                            
                                            $patest=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $patestrw=mysql_fetch_array($patest);
                                            if(($pillrow1['dnprid']!='')&&($pillrow1['denpreg']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div class="table-responsive">
                                        <table class=" color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center;">Pregnancy</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult1=mysql_query($patqry) or die("Query failed".mysql_error());
                                            while($pillrow=mysql_fetch_array($patqresult1))
                                            {
                                                
                                                $prillo=$pillrow['denpreg'];
                                          $prillw = wordwrap($prillo, 45, "\n", true);
                                            $prillp = htmlentities($prillw);
                                            $prill = nl2br($prillp);
                                          ?>
                                                <tr >
                                                    <td style="vertical-align: middle;"><?php echo $i; ?></td>
                                                    <td style="vertical-align: middle;"><?php echo $prill; ?></td>
                                                    <td style="text-align: right; vertical-align: middle;"><button type="button" style="  padding: 1px 1px;" id="btn" onclick="delpreg('<?php echo $pillrow['dnprid']?>')" class="btn btn-danger btn-sm"></i> Delete</button>
                        </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                
                                
                               
                                
                               <!------------------------------------------------------Medical Prescription----------------------------------------------->
                                 <div id="medfcs" class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry='';
                                            $patqry.="SELECT * FROM `dentmedpc` where patbkid='".$patbookId."' ";
                                            $patqresult=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $pillrow1=mysql_fetch_array($patqresult);
                                            if(($pillrow1['pmpid']!='')&&($pillrow1['pmedicns']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div  class="table-responsive">
                                        <table class=" color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="6" style="text-align: center;">Medicine Prescription
                                                    
                                                  
                                                     
                                                     </th>
                                                    
                                                </tr>
                                                <tr>
                                                <td>#</td><td>Medicine</td><td> </td><td>Turn</td><td>Day</td>
                                                <td style="text-align: right;"><button type="button" style="background-color: gold; padding: 1px 2px; " onclick="prmed()" data-toggle="modal" data-target=".bs-example-modal-lgmp">        
                                          Print
                                           </button></td>
                                                
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult1=mysql_query($patqry) or die("Query failed".mysql_error());
                                            while($pillrow=mysql_fetch_array($patqresult1))
                                            {
                                          $str=$pillrow['pmedicns'];
                                          
                                            $MessageW = wordwrap($str, 45, "\n", true);
                                            $MessageW = htmlentities($MessageW);
                                            $illhistry = nl2br($MessageW);
                                            if($pillrow['pmpfood']=='')
                                            {
                                                $dos=" ";
                                            }
                                            else
                                            {
                                                $dos=$pillrow['pmpfood']." ml";
                                            }
                                            
                                            if($pillrow['pmptrn']=='')
                                            {
                                                $trn="-";
                                            }
                                            else
                                            {
                                                $trn=$pillrow['pmptrn'];
                                            }
                                          ?>
                                                <tr >
                                                    <td style="vertical-align: middle; "><?php echo $i; ?></td>
                                                    <td style="vertical-align: middle; color:blue;"><?php echo $illhistry;?></td>
                                                    
                                                    <td style="vertical-align: middle; color:blue;"><?php echo $dos;?></td>
                                                    <td style="vertical-align: middle; color:blue;"><?php echo $trn;?></td>
                                                    <td style="vertical-align: middle; color:blue;"><?php echo $pillrow['pmpday'];?></td>
                                                    <td style="text-align: right; vertical-align: middle;"><button type="button" style="  padding: 1px 1px;" id="btn" onclick="delpmp('<?php echo $pillrow['pmpid']?>')" class="btn btn-danger btn-sm"></i> Delete</button>
                        </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                
                                <!------------------------------------------------------Disease Classification----------------------------------------------->
                                <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry='';
                                            $patqry.="SELECT a.*,b.disname FROM `dendisclasi` a inner join diseasetype b on a.distyp=b.did where a.patbkid='".$patbookId."' ";
                                            $patqresult=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $pillrow1=mysql_fetch_array($patqresult);
                                            
                                            $patest=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $patestrw=mysql_fetch_array($patest);
                                            if(($pillrow1['dnclid']!='')&&($pillrow1['distyp']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div class="table-responsive">
                                        <table class=" color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center;">Disease Classification</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult1=mysql_query($patqry) or die("Query failed".mysql_error());
                                            while($pillrow=mysql_fetch_array($patqresult1))
                                            {
                                                
                                                /*$prillo=$pillrow['denhistry'];
                                          $prillw = wordwrap($prillo, 45, "\n", true);
                                            $prillp = htmlentities($prillw);
                                            $prill = nl2br($prillp);*/
                                          ?>
                                                <tr >
                                                    <td style="vertical-align: middle;"><?php echo $i; ?></td>
                                                    <td style="vertical-align: middle;"><?php echo $pillrow['disname']; ?></td>
                                                    <td style="text-align: right; vertical-align: middle;">
                                                    <button type="button" style="  padding: 1px 1px;" id="btn" onclick="delclasi('<?php echo $pillrow['dnclid']?>')" class="btn btn-danger btn-sm"></i> Delete</button>
                        </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                
                                
                                <!------------------------------------------------------Visit Details----------------------------------------------->
                                 
                                 <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry='';
                                            $patqry.="SELECT dvid,denvistnm,dnvdesc,dvpay FROM `dentvisit` where patbkid='".$patbookId."' ";
                                            $patqresult=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $pillrow1=mysql_fetch_array($patqresult);
                                            if(($pillrow1['dvid']!='')&&($pillrow1['dnvdesc']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div class="table-responsive">
                                        <table class=" table color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="6" style="text-align: center;">Visit</th>
                                                    
                                                </tr>
                                                <tr>
                                                <td style="text-align: left;">Details</td>
                                                
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult1=mysql_query($patqry) or die("Query failed".mysql_error());
                                            while($pillrow=mysql_fetch_array($patqresult1))
                                            {
                                          $str=$pillrow['dnvdesc'];
                                          
                                            $MessageW = wordwrap($str, 95, "\n", true);
                                            $MessageW = htmlentities($MessageW);
                                            $illhistry = nl2br($MessageW);
                                          ?>
                                                <tr >
                                                    <!--td style="vertical-align: middle; "><?php echo $i; ?></td-->
                                                   <td style="vertical-align: middle; color:blue;"><?php echo $illhistry;?></td>
                                                  
                                                    <td style="text-align: right; vertical-align: middle;">
                                                    <button type="button" style="  padding: 1px 1px;" id="btn" onclick="delvisitn('<?php echo $pillrow['dvid']?>')" class="btn btn-danger btn-sm"></i> Delete</button>
                        </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                
                                
                                
                                <!------------------------------------------------------nxt appointment----------------------------------------------->
                                <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry='';
                                            $patqry.="SELECT dnapmnt,dnaptm,dnapid FROM `dentnapp` where patbkid='".$patbookId."' ";
                                            $patqresult=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $pillrow1=mysql_fetch_array($patqresult);
                                            if(($pillrow1['dnapid']!='')&&($pillrow1['dnapmnt']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div class="table-responsive">
                                        <table class=" color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center;">Next Appointment</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult1=mysql_query($patqry) or die("Query failed".mysql_error());
                                            while($pillrow=mysql_fetch_array($patqresult1))
                                            {
                                                date_default_timezone_set("Asia/Muscat");
                                                 $dt=$pillrow['dnapmnt']; 
                                     $newDate = date("d-m-Y", strtotime($dt));
                                     $newDate=$newDate." ".$pillrow['dnaptm']; ;
                                     
                                       

                                               
                                          ?>
                                                <tr >
                                                    <!--td style="vertical-align: middle;"><?php echo $i; ?></td-->
                                                    <td style="vertical-align: middle;"><?php echo $newDate; ?></td>
                                                    <td style="text-align: right; vertical-align: middle;">
                                                    <button type="button" style="  padding: 1px 1px;" id="btn" onclick="delnappnt('<?php echo $pillrow['dnapid']?>')" class="btn btn-danger btn-sm"></i> Delete</button>
                        </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                   <!------------------------------------------------------Treatment ----------------------------------------------->
                                 
                                   <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry2='';
                                            $patqry2.="SELECT tmtdsc,tc FROM `denconsultation` WHERE patbkid='".$patbookId."' and trmnt not like 'dt0'";
                                            $patqresult2=mysql_query($patqry2) or die("Query failed".mysql_error());
                                            while($pillrow12=mysql_fetch_array($patqresult2))
											{
                                            if(($pillrow12['tmtdsc']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
											}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div class="table-responsive">
                                        <table class=" color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="6" style="text-align: center;">Treatments</th>
                                                    
                                                </tr>
                                                <tr>
                                                <td></td><td>Treatment</td><td>Charge</td>
                                                
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult11=mysql_query($patqry2) or die("Query failed".mysql_error());
                                            while($pillrow11=mysql_fetch_array($patqresult11))
                                            {
                                                
                                                if($pillrow11['tmtdsc']=='')
                                                {
                                                    $tvis='hidden';
                                                }
                                                else
                                                {
                                                    $tvis='';
                                                }
                                         
                                          ?>
                                                <tr  <?php echo $tvis;?> >
                                                    <td style="vertical-align: middle; "><?php// echo $i; ?></td>
                                                    <td style="vertical-align: middle; color:blue;"><?php echo $pillrow11['tmtdsc'];?></td>
                                                    <td style="vertical-align: middle; color:blue;"><?php echo $pillrow11['tc'];?></td>
                                                    
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                <!------------------------------------------------------Total Charge----------------------------------------------->
                                 
                                   <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry2='';
                                            $patqry2.="SELECT `chid`, `totch`, `totdis`, `finrate` FROM `denchrg` WHERE bkid='".$patbookId."' ";
                                            $patqresult2=mysql_query($patqry2) or die("Query failed".mysql_error());
                                            $pillrow12=mysql_fetch_array($patqresult2);
                                            if(($pillrow12['chid']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div class="table-responsive">
                                        <table class=" color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="6" style="text-align: center;">Treatment Charge</th>
                                                    
                                                </tr>
                                                <tr>
                                                <td>#</td><td>Total</td><td>Discount</td><td>Final Amount</td>
                                                
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult11=mysql_query($patqry2) or die("Query failed".mysql_error());
                                            while($pillrow11=mysql_fetch_array($patqresult11))
                                            {
                                         
                                          ?>
                                                <tr >
                                                    <td style="vertical-align: middle; "><?php echo $i; ?></td>
                                                    <td style="vertical-align: middle; color:blue;"><?php echo $pillrow11['totch'];?></td>
                                                    <td style="vertical-align: middle; color:blue;"><?php echo $pillrow11['totdis'];?></td>
                                                    <td style="vertical-align: middle; color:blue;"><?php echo $pillrow11['finrate'];?></td>
                                                    <td style="text-align: right; vertical-align: middle;">
                                                    <button type="button" style="  padding: 1px 1px;" id="btn" onclick="delchrg('<?php echo $pillrow11['chid']?>','<?php echo $_REQUEST['billno']?>')" class="btn btn-danger btn-sm"></i> Delete</button>
                        </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                
                                
                                
                                <!------------------------------------------------------Doctors Remarks----------------------------------------------->
                                <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry='';
                                            $patqry.="SELECT denremark,drmkid FROM `dentrmrk` where patbkid='".$patbookId."' ";
                                            $patqresult=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $pillrow1=mysql_fetch_array($patqresult);
                                            if(($pillrow1['drmkid']!='')&&($pillrow1['denremark']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div class="table-responsive">
                                        <table class=" color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center;">Remarks</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult1=mysql_query($patqry) or die("Query failed".mysql_error());
                                            while($pillrow=mysql_fetch_array($patqresult1))
                                            {
                                                
                                                $prillo=$pillrow['denremark'];
                                          $prillw = wordwrap($prillo, 45, "\n", true);
                                            $prillp = htmlentities($prillw);
                                            $prill = nl2br($prillp);
                                          ?>
                                                <tr >
                                                    <!--td style="vertical-align: middle;"><?php echo $i; ?></td-->
                                                    <td style="vertical-align: middle;"><?php echo $prill; ?></td>
                                                    <td style="text-align: right; vertical-align: middle;">
                                                    <button type="button" style="  padding: 1px 1px;" id="btn" onclick="delrmrk('<?php echo $pillrow['drmkid']?>')" class="btn btn-danger btn-sm"></i> Delete</button>
                        </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                
                                 <!------------------------------------------------------Final appointment----------------------------------------------->
                                <div class="card-body">
                                
                                 <?php  $patbookId=$_REQUEST['patbkid'];
                                          $patqry='';
                                            $patqry.="SELECT * FROM `denfincols` where patbkid='".$patbookId."' ";
                                            $patqresult=mysql_query($patqry) or die("Query failed".mysql_error());
                                            $pillrow1=mysql_fetch_array($patqresult);
                                            if(($pillrow1['finid']!='')&&($pillrow1['finyn']!=''))
                                            {$visblty='';}
                                            else
                                            {$visblty='hidden';}
                                            ?>
                                 <div class="card-body" <?php echo $visblty ?> >                               
                                 <form class="form-horizontal" method="POST" id="prsnt" action="">
                                 <div class="table-responsive">
                                        <table class=" color-table primary-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center;">Final Appointment</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody style="color: black;">
                                         <?php
                                            //echo $patqry;
                                            $i=1;
                                            $patqresult1=mysql_query($patqry) or die("Query failed".mysql_error());
                                            while($pillrow=mysql_fetch_array($patqresult1))
                                            {
                                                
                                               
                                          ?>
                                                <tr >
                                                    <!--td style="vertical-align: middle;"><?php echo $i; ?></td-->
                                                    <td style="vertical-align: middle;"><?php echo $pillrow['finyn']; ?></td>
                                                    <td style="text-align: right; vertical-align: middle;">
                                                    <button type="button" style="  padding: 1px 1px;" id="btn" onclick="delfin('<?php echo $pillrow['finid']?>')" class="btn btn-danger btn-sm"></i> Delete</button>
                        </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                 
                                 </form>
                                 </div>
                                </div>
                                
                                 <!------------------------------------------------------Final save----------------------------------------------->
                              
                                <div class="card-body">
                                <?php
                                $patbookId=$_REQUEST['patbkid'];
                                $patqryqq='';
                                $patqryqq.="SELECT * FROM `dendisclasi` where patbkid='".$patbookId."' ";
                                            $patqresultqq=mysql_query($patqryqq) or die("Query failed".mysql_error());
                                            
                                $patestrwqq=mysql_fetch_array($patqresultqq);
                                if((isset($patestrwqq['distyp'])))
                                            {
                                                $visblty='';
                                            //echo "ddddd";
                                            }
                                            else
                                            {
                                                // echo "qwe";
                                                $visblty='hidden';
                                            }
                                            ?>
                                 <div class="card-body" <?php echo $visblty; ?> > 
                                  <table style="width: 100%;">
                                
                                 <tr >
                                
                                 <td style="text-align: center; vertical-align: middle;">
                                 <button type="button" style="  padding: 1px 80px;" id="btn" onclick="conslovr('<?php echo $patbookId;?>')" class="btn btn-success btn-sm"></i> Save</button>
                        </td>
                                                </tr>
                                
                                </table> 
                                </div>
                               
                                </div>
                                <!-- end card-body -->
                            </div>
                            <!-- end card -->

                        </div>
                    </div>
                    <!--/.row-->

               
                    
                    
                    
                    
                    <!--/.row-->
                </div>

            </div>
  
          <div class="form-group row" hidden>
                                            <label class="col-md-3 form-control-label">Date</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="inputDate1" data-plugin="formatter" data-pattern="[[9999]]-[[99]]-[[99]]" />
                                                <p class="text-secondary">2017-01-01</p>
                                            </div>
                                        </div>
                                        
                                       
                                               
    
            <!-- /.conainer-fluid -->

        </main>
        <!-- end main -->
            

        <aside class="aside-menu">
            <div class="aside-header bg-theme text-uppercase">Case Sheets</div>
            <div class="aside-body">
            
            <table  style="border: aqua; width: 100%;" >
             <tr>
             <td style="text-align: left; width:30px;">#</td>
             <td style="text-align: center;">Date</td>
             <td style="text-align: center;">Doctor</td>
             </tr>
             <?php 
             date_default_timezone_set("Asia/Muscat");
             $ptfile=$_REQUEST['patid'];
             $preq="SELECT a.*,b.docname,b.docdepid FROM `patbooking` a inner join doctors b on b.docid=a.patbkdr WHERE a.`patbkFile`='".$ptfile."' and a.patconslstat='Y' and a.status='Y' order by a.patbkId desc";
            
           // echo $preq;
             $preqli=mysql_query($preq) or die("Query failed".mysql_error());
             $i=1;
              
             while($preqrw=mysql_fetch_array($preqli))
             {
                $depid=$preqrw['docdepid'];
                
                if($depid=='dn')
                {
                    $btn='summary';
                }
                else
                {
                    $btn='summary1';
                }
                
               ?> 
                <tr style="color:blue;">
             <td style="text-align: left; width:30px;"><?php echo $i; ?></td>
             <td style="text-align: center;">             
            <a href="#" onclick="<?php echo $btn;?>('<?php echo $preqrw['patbkId'] ?>','<?php echo $ptfile; ?>')" data-toggle="modal" data-target="#modalRegister"> <?php    echo date("d-m-Y", strtotime($preqrw['patbkdt'])); ?></a>
             </td>
             <td style="text-align: center;"><?php  echo $preqrw['docname']; ?></td>
             </tr>
            <?php  
            
             $pqry="SELECT * FROM `patbooking` WHERE `patbkFile`='".$ptfile."' and patbkdt='".$preqrw['patbkdt']."'  ";
             $pqryli=mysql_query($pqry) or die("Query failed".mysql_error());
             $pqrylirw=mysql_fetch_array($pqryli);
             
             $bkid=$pqrylirw['patbkId'];  
             $i++;
             }
            
             
             ?>
             </table>
               
                <!-- <h6 class="text-theme"><!--Social Colors</h6> -->
               

                <!-- <h6 class="text-theme"><!--Social Colors</h6> -->
                
            </div>

        </aside>
        <!-- end aside -->
         <div class="modal fade fade bs-example-modal-tapp" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Today's Appointments</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div  class="modal-body">
                <div id="printbdy13">
                    <table class="table table-bordered" id="aptable"  width='100%'></table>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect text-left"  data-dismiss="modal">Close</button>
                </div>
                </div>
                
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

      <div class="modal fade fade bs-example-modal-lgmp" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Print Medicine Prescription</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <!--div  class="modal-body">
              
				
				<div id="printbdy13">
                 <table class="table table-bordered" id="mp"  width='100%'></table>
                </div>
				
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-success waves-effect text-left" onclick="printDivpr3('printbdy13')">Print</button>
                    <button type="button" class="btn btn-danger waves-effect text-left" onclick="winclosepr()" data-dismiss="modal">Close</button>
                </div>
                </div-->
				
				
				 <div  class="modal-body">
                <div id="printbdy11">
                  <table class="table table-bordered" id="mp"  width='100%'></table>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-success waves-effect text-left" onclick="printDivpr('printbdy11')">Print</button>
                    <button type="button" class="btn btn-danger waves-effect text-left" onclick="winclosepr()" data-dismiss="modal">Close</button>
                </div>
                </div>
				
				
                
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
        
         <!---------------------------------------view consl------------------------>
        
        
         <div class="modal fade fade bs-example-modal-lgvw" id="modalRegister" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style=" width: 1600px; margin-left: -40%;">
                <div class="modal-header">
                    <h6 class="modal-title">Consultation Summary</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div  class="modal-body">
                <div id="printbdy11">
                  <div class="tstdetail" id="viewtb"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-success waves-effect text-left" onclick="printDivpr('printbdy11')">Print</button>
                    <button type="button" class="btn btn-danger waves-effect text-left" onclick="winclosepr()" data-dismiss="modal">Close</button>
                </div>
                </div>
                
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    </div>
    <!-- end app-body -->
    
    <div class="modal fade fade bs-example-modal-lgr38" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="width: 1300px; margin-left: -20%;">
                <div class="modal-header">
                    <h6 class="modal-title" >Examination</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                   <div class="teethex" id="teethex"></div>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-warning waves-effect text-left" data-dismiss="modal">Close</button>
                   
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    
    <div class="modal fade fade bs-example-modal-lg4" id="modalid" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="width: 1200px;">
                <div class="modal-header">
                    <h6 class="modal-title">Medicine Prescription</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" >
                <form class="form-horizontal" method="POST" id="medd" action="">
                    <table class="table" id="tblCustomers" class=" table-bordered" >
            <tr><td style="vertical-align: middle;">Select Medicine </td>
            
             <td> <input type="text" id="medname" style="width: 300px;" class="form-control"    name='medname' list="itmlist3">
                                            <datalist id="itmlist3">
                                             <?php
                                             
                                                            $sqlqy="SELECT `mid`, `mname` FROM `comnmedi` ";
                                                            $invqry=mysql_query($sqlqy) or die("Query failedq".mysql_error());
                                
                                                            while ( $itm = mysql_fetch_array( $invqry ) )
{
                                                            ?>
                                                            <option value="<?php echo trim($itm['mname']);?>"><?php echo trim($itm['mname']);?></option>
                                                            <?php }?>
                                            </datalist></td>
                                            
                                            <td >
                                                    <select id="pmedtim" name="pmedtim" style="width: 150px;" class="form-control form-control-sm" >
                                                    <option value="" />Dose
                                                    <option value="1" />1 ml
                                                    <option value="2.5" />2.5 ml
                                                    <option value="3.5" />3.5 ml
                                                    <option value="4" />4 ml
                                                    <option value="5" />5 ml
                                                </select>
                                                    </td>
                                            <td  >
                                                    <select id="pmedtrn" name="pmedtrn" style="width: 120px;" class="form-control form-control-sm" >
                                                    <option value="" />Select Turn
                                                    <option value="OD" />OD
                                                    <option value="BD" />BD
                                                    <option value="TDS" />TDS
                                                    <option value="Q 12 H" />Q 12 H
                                                    <option value="Q 8 H" />Q 8 H
                                                    <option value="Q 6 H" />Q 6 H
                                                </select>
                                                    </td>
                                                    <!--td style="text-align:left; width:50px !important; vertical-align: middle;">
                                                    <select id="pmedtim" name="pmedtim" style="width: 150px;" class="form-control form-control-sm" >
                                                    <option value="" />Select Time
                                                    <option value="Before Food" />Before Food
                                                    <option value="After Food" />After Food
                                                </select>
                                                    </td-->
                                                    
                                                      <td >
                                                    <input type="text" size="70" style="width: 100px;" class="form-control" id="pmedday" name="pmedday"   />
                                                    </td>
                                                    <td>
                                                    days/day
                                                    </td>
               <td>
                                                    <button type="button" class="btn btn-info waves-effect text-left" onclick="Addmed()">Add</button>
                                                    </td>
                
                <!--td><input type="button" onclick="Add()" value="Add"/></td-->
            </tr>
            </table>            
            <table id="tbnew2">
        <thead>
            <tr>
                <th style="text-align: center; width: 550px">Medicines</th>
                
                
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            
        </tfoot>
    </table>
    </form>
                </div>
                <div class="modal-footer">
                     <button type="button" class="btn btn-success waves-effect text-left" onclick="med()">Save</button>
                    <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
	
    <div class="modal fade fade bs-example-modal-lgdn" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" >Update Examination</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                   <form class="form-horizontal" method="POST" id="updtteeth" action="">
                                               <table id="constest" class="table"  width="100%" >
                                               <tr>
                                               <td colspan="2" style="text-align: center; height: 30px; font-size: large;">Teeth Q18</td>
                                               </tr>
                                    <tr style="color: black;">
                                    <td style="">Chief Complaint</td> 
                                    <td> 
                                     <textarea rows="2" style="width: 500px;"  id="cmp" name="cmp18" cols="30"><?php echo $rw18['cc']; ?></textarea>
                                     <input type="hidden" name="q18" value="q18" />
                                    </td> 
                                </tr>
                                <tr style="color: black;">
                                    <td style="">HPI</td> 
                                    <td>  
                                     <textarea rows="2" style="width: 500px;"  id="pcomplaint" name="hpi18" cols="30"><?php echo $rw18['hpi']; ?></textarea>
                                    </td> 
                                </tr>
                                <tr style="color: black;">
                                    <td style="">Treatment Done</td> 
                                    <td> 
                                     <textarea rows="2" style="width: 500px;"  id="pcomplaint" name="denh18" cols="30"><?php echo $rw18['dh']; ?></textarea>
                                    </td> 
                                </tr>
                                 <tr style="color: black;">
                                    <td style="">Dental Examination</td> 
                                    <td> 
                                    <textarea rows="2" style="width: 500px;" id="pcomplaint" name="dex18" cols="30"><?php echo $rw18['de']; ?></textarea>
                                    </td> 
                                </tr>
                                <tr style="color: black;">
                                    <td style="">Diagnosis</td> 
                                    <td> 
                                      <textarea rows="2" style="width: 500px;" id="diag" name="diag18" cols="30"><?php echo $rw18['dg']; ?></textarea>
                                    </td> 
                                </tr>
                                <tr style="color: black;">
                                    <td style="">X-Ray</td> 
                                    <td> 
                                     <textarea rows="2" style="width: 500px;" id="xry" name="xry18" cols="30"><?php echo $rw18['xry']; ?></textarea>
                                    </td> 
                                </tr>
                                <tr style="color: black;">
                                    <td style="">Treatment</td> 
                                    <td>
                                    <!--textarea rows="2" style="width: 500px;" id="tmnt" name="tmnt18" cols="30"><?php echo $rw18['trmnt']; ?></textarea-->
                                      <select class="form-control" style="width: 500px; background-color: ghostwhite;" onchange="selrate()"  id="tmnt" name="tmnt18">
                                                        <option value="><?php echo $rw18['trmnt']; ?>"><?php echo $rw18['tmtdsc']; ?></option>
                                                      
                                                       <option value="$rw18['trmnt']">------------------------------Select------------------------------</option>
                                                       <?php  
                                                        while($patrowd=mysql_fetch_array($tqli))
                                                        
                                                         { ?>
                                                            
                                                         <option value="<?php  echo $patrowd['dtid']; ?>"><?php echo $patrowd['dtname']; ?></option>   
                                                       <?php  } ?>
                                                    </select>
                                     </td> 
                                </tr>
                                 <tr style="color: black;">
                                    <td style="">Charge</td> 
                                    <td>  <input type="text" style="width: 500px;" id="tchr" value="<?php echo $rw18['tc']; ?>" name="tchr18"/></td> 
                                </tr>
                                 <tr style="color: black;">
                                    <td style="">Additional Tests</td> 
                                    <td>  <input type="text" style="width: 500px;" id="atst" <?php echo $rw18['at']; ?> name="atst18"/></td> 
                                </tr>
                                </table>
                               
                                        </form>
                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-success waves-effect text-left" onclick="saveupdt()">update</button>
                    <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
    <?php 

require 'mod.php';
require 'mod1.php';

//require 'modr1.php';
//require 'modr2.php';

//require 'moden.php';

 ?>
    <footer class="app-footer">
        <a href="#" class="text-theme">AemmS</a> &copy; AemmS.
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="../../libs/jquery/dist/jquery.min.js"></script>
    <script src="../../libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../libs/bootstrap/bootstrap.min.js"></script>
    <script src="../../libs/PACE/pace.min.js"></script>
    <script src="../../libs/chart.js/dist/Chart.min.js"></script>
    <script src="../../libs/nicescroll/jquery.nicescroll.min.js"></script>

   <!-- jquery-loading -->
   <script src="../../libs/jquery-loading/dist/jquery.loading.min.js"></script>
    <!-- dashboard-pm -example -->
    <script src="../../js/dashboard-pm-example.js"></script>

    <!-- octadmin Main Script -->
    <script src="../../js/app.js"></script>
    
     
    <script>
    //////////////////////////////////////////////////////
//  Template Name: octAdmin
//  Author: octathemes
//  Email: octathemes@gmail.com
//  File: form-masks-example.js
///////////////////////////////////////////////////

$(function () {
    "use strict";
    // Alert Dialog

// basic masks

var formatted = new Formatter(document.getElementById('inputDate1'), {

    'pattern': '{{99}}:{{99}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputDate2'), {

    'pattern': '{{99}}:{{99}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputTime'), {

    'pattern': '{{99}}: {{99}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputTimeDate'), {

    'pattern': '{{99}}: {{99}} {{99}}/{{99}}/{{9999}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputCharacters'), {

    'pattern': '{{aaaaaaaaa}}',
    'persistent': true
});


// Extended masks

var formatted = new Formatter(document.getElementById('inputPhone'), {

    'pattern': '({{999}}) {{999}}-{{9999}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputPhone2'), {

    'pattern': '+186 {{999}}-{{999}}-{{9999}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputPhoneExt'), {

    'pattern': '({{999}}) {{999}}-{{9999}} x{{99999}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputCredit'), {

    'pattern': '{{9999}}-{{9999}}-{{9999}}-{{9999}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputProduct'), {

    'pattern': 'a{{*}}-{{9999}}-C{{999}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputPercent'), {

    'pattern': '{{99}}.{{99}}%',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputCurrency'), {

    'pattern': '${{999}},{{999}},{{999}}.{{99}}',
    'persistent': true
});

var formatted = new Formatter(document.getElementById('inputCurrency2'), {

    'pattern': '${{999}},{{999}},{{999}}.{{99}}',
    'persistent': true
});

});
    </script>
          <script src="../../libs/form-masks/dist/formatter.min.js"></script>  
          
               

</body>

</html>