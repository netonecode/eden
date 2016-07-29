<?php include("menu.php");?>
<script  type="text/javascript" src="jquery-1.11.1.js"> </script>
<script  type="text/javascript" src="json2.js"> </script>
<script>
	$(document).ready(function(){
		$("#addlogbutton").click(function(){
			var _len = $("#tab tr").length; 
		/*       
			$("#calllog").append("<tr id="+_len+" align='center'>"
									+"<td>"+_len+"</td>"
									+"<td>Dynamic TR"+_len+"</td>"
									+"<td><input type='text' name='desc"+_len+"' id='desc"+_len+"' /></td>"
									+"<td><a href=\'#\' onclick=\'deltr("+_len+")\'>删除</a></td>"
									+"</tr>");            
								});
		*/
			$("#calllog").append("<tr id=log"+_len+ ">"
		    					+"<td>" + json.callday + "</td>"
		    					+"<td>" + json.calltime + "</td>"
		   						+"<td>" + json.dnid + "</td>"
		   						+"<td>" + json.caller_id_name + "</td>"
		   						+"<td>"+ json.caller_id_number + "</td>"
		   						+"<td>" + json.cname + "</td>"		    					
		    					+ '<td><a href="'+ json.recording + '"</td>'
		    					+ '<td style="cursor:pointer"><a href = "javascript:void(0)" onclick = "document.getElementById(\'light\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'">write</a></td>'
								+ '<td><a href="todaysbooking.php" target="_blank"><img src="images/rsz_contact-book-128x128.png" title="Book Now" id="img1" /></a><img src="images/rsz_129-128.png" id="img1" title="Inquire" style="padding-left:5%" /><img src="images/rsz_headphones_318-109965.png" title="Telemarketer" id="img1" style="padding-left:5%" /><img src="images/rsz_icon175x175.jpg" title="Prank Call" id="img1" style="padding-left:5%" /></td>'
		
		  						+ '</tr>'); 
		  						return false;           
				});
			});

var wsUri = "ws://24.234.172.13:8080/";
  var output;

  function init()
  {
    output = document.getElementById("output");
    testWebSocket();
  }

  function testWebSocket()
  {
    websocket = new WebSocket(wsUri);
    websocket.onopen = function(evt) { onOpen(evt) };
    websocket.onclose = function(evt) { onClose(evt) };
    websocket.onmessage = function(evt) { onMessage(evt) };
    websocket.onerror = function(evt) { onError(evt) };
  }

  function onOpen(evt)
  {
  	$('#statustext').text("CONNECTED");
    //alert("WS: CONNECTED");
    var mydata = {'action' : "getincomingsms"};
    var str = JSON.stringify(mydata);
    doSend(str);
  }

  function onClose(evt)
  {
   	$('#statustext').text("DISCONNECTED");
 	
    //alert("DISCONNECTED");
  }

  function onMessage(evt)
  {
    writeToScreen(evt.data);
    //websocket.close();
  }

  function onError(evt)
  {
   	$('#statustext').text(evt.data);
  	
    //alert('<span style="color: red;">ERROR:</span> ' + evt.data);
  }

  function doSend(message)
  {
    //alert("SENT: " + message);
    websocket.send(message);
  }

  function writeToScreen(message)
  {
    //var pre = document.createElement("p");
    //pre.style.wordWrap = "break-word";
    //alert(message.indexOf("{"));
    if (message.indexOf("{") == 0) {
	    //var json = strToJson(message)
	    var json = JSON.parse(message);
	    if (!json.replytype) {
	    //alert(json.destination);
	   		getNewIncoming(json);
	   	} else if (json.replytype == 'sendsms') {
	   		getSendSmsReply(json);
	   	}
	}
	
    //pre.innerHTML = '<span style="color: blue;">' + "Response: " + message +'</span>';
    //output.appendChild(pre);
    //alert("Response: " + message);
  }

	function strToJson(str){	
		var json = eval('(' + str + ')'); 
		return json; 
	} 
  window.addEventListener("load", init, false);

function sendmsg () {
	
	var dest = $("#dest").val();
	if (!dest) {
		alert("Error: dest is null");
		return false;
	}
	$("#msgresult").html("<font color=gray>sending ...</font>");
	var body = $("#smsbody").val();
	var mydata = {'action':'sendsms', 'dest':dest, 'smsbody':body};
	var str  = JSON.stringify(mydata);
	websocket.send(str);
	
}

function getSendSmsReply(json) {
	//alert(json.message);
	$("#msgresult").html("<font color=red>msg sent</font>");
	//document.getElementById('light').style.display='none';//document.getElementById('fade').style.display='none';
		
}
 			
function getNewIncoming (json) {
	
	var _len = $("#tab tr").length; 
	if (!json.To) {
		return;
	}
	
	$("#calllog").append("<tr id=log"+_len+ ">"
		    					+"<td>" + json.callday + "</td>"
		    					+"<td>" + json.calltime + "</td>"
		   						+"<td>" + json.SmsStatus + "</td>"
		   						+"<td>" + json.To + "</td>"
		   						+"<td>"+ json.ToCity + "</td>"
		   						+"<td>" + json.From + "</td>"		    	 					
		    					+ '<td>'+ json.FromCity + '</td>'
		    					+ '<td>'+ json.Body + '</td>'
		    					+ '<td><a href="#" onclick="$(\'#dest\').val(\'' + json.From + '\'); document.getElementById(\'light\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\';return false;">reply</a></td>'
		    					
		    					
		
		  						+ '</tr>'); 
	
}


</script>
</head>
<body>
     
     <img src="images/rsz_129-128.png" id="img1" title="Inquire" style="padding-left:4%" />
        <!-- /. NAV TOP  -->
       
        <!-- /. NAV SIDE  -->
       

        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                <input type=submit value='New' id='addlogbutton' style="display: block" onclick = "document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block';return false;"/>
                    <div class="col-md-12">
                     <h2>SMS Log List</h2>
                     <table style="width:100%" id=calllog>
  <tr>
    <th>date</th>
    <th>time</th>
    <th>Status</th>
    <th>To</th>
    <th>To City</th>
    <th>From</th>
    <th>From City</th>
    <th>Body</th>
    <th>Action</th>
  </tr>

</table>   
    


                    </div>
                     <div id="light" class="white_content">Create Msg:<br /><input name=dest id=dest value='' placeholder='destination' /><br><br>
                     <textarea rows="3" cols="70" name=smsbody id=smsbody></textarea>
                      <a href = "javascript:void(0)" onclick = "return sendmsg();">Send</a>
                       <a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">Close</a><br><div id='msgresult'></div></div>
        <div id="fade" class="black_overlay"></div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
              
                 <!-- /. ROW  -->           
    </div>
   
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
    
          

     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>
