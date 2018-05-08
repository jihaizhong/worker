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
			用户uid，昵称：季海忠 说：累死了的开发
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
	<script src="js/jquery-3.1.1.min.js"></script>
	<script>
		$(function(){
			// console.log('jquery-3.1.1.min.js,over...');
			var chatDao = $("#roomId");
			var userList = $("#userList");
			var userName = $("#userName").val();
			var userArray = new Array();
			console.log(userName)
			// var ws = new WebSocket("ws://127.0.0.1:8000");
			var ws = new WebSocket("ws://172.25.204.1:8000");
			// console.log(ws);
			// 连接
			ws.onopen = function (res) {
				
				// wx.send('{"userName":"jhz"}');
				ws.send('{"type":"login","userName":"'+userName+'"}');
				console.log('websocket连接成功');
				// console.log(res);	
			}

			// 接收信息
			ws.onmessage = function (res) {
				var chatPingdao = chatDao.html();
				console.log('接收信息成功');
				console.log(res.data);
				var data = res.data.split("-");
				// console.log(data)
				//接收到的是json字符串，要转为数组
				var dataArray = JSON.parse(data[1]);
				console.log(dataArray.type);
				if (dataArray.type=='login') {
					// userArray.push(dataArray.userName);
					var userHtml = `
						<li>${data[0]}--->${dataArray.userName}</li>
					`;
					userList.append(userHtml);
				}

				// console.log(userArray);


				// console.log(dataArray.content);
				if (dataArray.roomId!=chatPingdao) {
					//判断是否是同一个频道，不是则不处理信息
					return false;
				}
				var html = '';
				html = `
					<div>
						昵称：${dataArray.userName} 在聊天室${dataArray.roomId} 说：${dataArray.content}
					</div>
				`;
				// console.log('测试获取信息的数据格式：',typeof(res.data));
				// var said = res.data.split('{')[1];
				// console.log(said);
				$("#chatContainer").append(html+'<br/>');
			}

			
			$("#send").click(function(){
				// var userName = $("#userName").val();
				var sendInfo = $("#txt").val();
				var roomId = $("#chatRoomId").val();
				if (sendInfo=='') {
					alert('请输入发言内容！');
					return false;
				}
				console.log('获取发送的信息',userName,sendInfo,roomId);

				 // var input = document.getElementById("textarea");
			  //     var to_client_id = $("#client_list option:selected").attr("value");
			  //     var to_client_name = $("#client_list option:selected").text();
			  //     ws.send('{"type":"say","to_client_id":"'+to_client_id+'","to_client_name":"'+to_client_name+'","content":"'+input.value.replace(/"/g, '\\"').replace(/\n/g,'\\n').replace(/\r/g, '\\r')+'"}');
			  //     input.value = "";
			  //     input.focus();

			    // ws.send('{"type":"say","content":"'+sendInfo.replace(/"/g, '\\"').replace(/\n/g,'\\n').replace(/\r/g, '\\r')+'"}');
			    ws.send('{"type":"say","userName":"'+userName+'","roomId":"'+roomId+'","content":"'+sendInfo+'"}');
			    // ws.send(sendInfo);

			})

			// 修改聊天室
			$("#changeRoomBtn").click(function(){
				var roomId = $("#chatRoomId").val();
				chatDao.html(roomId);
			});   //over 修改聊天室
				
	
		})	
			
	</script>
</body>
</html>