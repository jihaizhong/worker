<!DOCTYPE html>
<html lang="en">
<head>
 	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="UTF-8">
	<title>聊天系统</title>
	<style>
	*{
		color: #666;
	}
		.chat-container{
			padding: 14px;
			width: 500px;
			margin: 10px auto;
			border: 1px solid #ddd;
			box-shadow: 0px 0px 13px rgba(0,0,0,.4);
		}
		.user{
			padding: 14px;
			margin: 5px auto;
		}
		.roomId{
			font-size: 16px;
			font-weight: 500;
			margin: 10px;
			border: 1px solid #666;
			padding: 4px;
			border-radius: 4px;
		}
	</style>
</head>
<body>
	<h2>聊天室<span class="roomId" id="roomId" style="">默认频道</span></h2>

	<div class="chat-container" id="chatContainer">
		<h4>聊天内容：</h4>

		<!-- <div>
			用户uid，昵称：季海忠 说：dgrdgdrg发
		</div> -->
	</div>
<!-- 用户列表 -->
	<div class="userList">
		<ul id="userList">
			<!-- <li>jack</li>
			<li>tom</li> -->
		</ul>
	</div>

	<div>	
		<div class="user">
			<span>用户</span>
			<input type="text" id="userName" value="季海忠">

			<span>聊天室</span>
			<input type="text" id="chatRoomId" value="默认频道"> 
			<input type="button" id="changeRoomBtn" value="修改">

		</div>
		<input type="text" id="txt" placeholder="输入聊天信息">
		<input type="button" id="send" value="发送">
	</div>

	<hr>
	<div>
    	<div class="messages"></div>
		  <audio controls autoplay></audio>  
		<div class="contrs">
		        <input type="button" value="开始录音" onclick="startRecording()"/>  
		        <input type="button" value="停止录音" onclick="stopRecord()"/>  
		        <input type="button" value="获取录音" onclick="obtainRecord()"/>   
		        <input type="button" value="发送" onclick="send()"/>  
		        <!-- <input type="button" value="播放录音" onclick="playRecord()"/>   -->
		</div>
		        <div class="error">   
		        </div>
	</div>
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/recoder.js"></script>
	<script src="js/recIndex.js"></script>
	<script src="js/main.js"></script>
</body>
</html>