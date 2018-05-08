// window.onload = function (){

	console.log("加载完成");

	// 
	var chatDao = $("#roomId");
	var userList = $("#userList");
	var userName = $("#userName").val();
	var userArray = new Array();
	var dataRec;
	console.log(userName)
	var ws = new WebSocket("ws://127.0.0.1:8000");
	// var ws = new WebSocket("ws://192.168.10.114:8000");   //本地电脑ip地址
	ws.binaryType = 'arraybuffer';
// 连接
	ws.onopen = function (res) {
		ws.binaryType = 'blob';
		// ws.send('{"type":"login","userName":"'+userName+'"}');
		console.log('websocket连接成功');	
	}

// 开始录音 
	var recorder;  
	 HZRecorder.get(function (rec) {  
	            recorder = rec;  
	            // recorder.start();  
	        },{error:showError});  
	var audio = document.querySelector('audio');  
	      
	    function startRecording() {  
	        if(recorder){
	           recorder.start();
	           return;
	        } 
	         recorder.start();  
	    }  
// 停止录音
	function stopRecord(){  
		console.log("停止录音");
        recorder&&recorder.stop();  
    } 
//获取录音
	function obtainRecord(){  
        if(!recorder){
            showError("请先录音");
            return;
        }
        var record = recorder.getBlob();  
        if(record.duration!==0){
            downloadRecord(record.blob);
        }else{
            showError("请先录音")
        }
    };  

// 发送录音
	// var msg={};
	var msg={};
    //发送音频片段
    var msgId=1;
    var dur;
    function send(){
    	console.log("开始发送语音-----------------------------------x00---------------------");
        if(!recorder){
            showError("请先录音");
            return;
        }
        var data=recorder.getBlob();
        if(data.duration==0){
            showError("请先录音");
            return;
        }
        msg[msgId]=data;
        recorder.clear();
        console.log(data);
        console.log('打印----blob');
        console.log(data.blob);
     
        dur=data.duration/10;
        var str="<div class='warper'><div id="+msgId+" class='voiceItem'>"+dur+"s</div></div>"
        $(".messages").append(str);
        msgId++;

       ws.binaryType = 'blob';
       // var t = {
       // 		"type":'rec',
       // 		"blob":data.blob
       // };
       // ws.send(t);
       ws.send(data.blob);
    }
    // 、点击播放本地的录音文件录音
    $(document).on("click",".voiceItem",function(){
        var id=$(this)[0].id;
        var data=msg[id];
            if(!recorder){
                showError("请先录音");
                return;
            }
        // console.log("点击本地录音文件：看data")
        // console.log(data)
        recorder.play(audio,data.blob);   
    })

// 录音出错提示，show error
    var ct;
    var html = '', saidHtml = '';
    function showError(msg){
        $(".error").html(msg);
        clearTimeout(ct);
        ct=setTimeout(function() {
            $(".error").html("")
        }, 3000);
    }
// onmessage 通讯
		// 收到的是 blob 数据
	ws.binaryType = "blob";
	ws.onmessage = function (res) {
		var chatPingdao = chatDao.html();
		console.log('接收信息成功');
		console.log(res);
		console.log(res.data);
	
		if(typeof(res.data) == "string"){
			console.log('get data is string');
		}
		if((res.data) instanceof Blob) { // 判断是否是二进制数据类型，并处理二进制信息
			console.log('get data is Blob...');
			// dataRec = processBlob(res.data);
			// 测试：
			var  data_blob = new Blob([res.data],{type:"audio/wav"});
		}
				console.log('-------------打印newbolob-----')
				console.log(data_blob)
				dataRec = data_blob;
		// 处理接收到的blob数据;因要判断是文本还是语音，所以传送的时候发了两段数据，第一个用于判断，所以处理时候截取出来
		var reader = new FileReader();
		// 截取第一段数据
		var firstBlob = res.data.slice(0,10);
		var endBlob = res.data.slice(1)
		// var firstBlob = res.data;
		//进行读取处理
		// reader.readAsBinaryString(firstBlob);  //该方法被w3c剔除，用readAsArrayBuffer替代。但是可以用
		// var s = reader.readAsArrayBuffer(res.data);
		var s = reader.readAsText(res.data); //输出是【object，object】和string类似
        // reader.readAsBinaryString(endBlob);
        reader.addEventListener("loadend",function(res){
        	console.log('打印FileReader处理的数据');
        	var buffer = res.currentTarget.result;
        	// var dataV = new DataView(res.currentTarget.result);
        	console.log(res);
        	console.log(buffer);
        	console.log(buffer.indexOf("say"));
        	// console.log($.pabufferrseJSON(buffer).type);
        	if (buffer.indexOf("say")!=-1) {
        		console.log('发送的shi say,文本信息**************')
        		var said = $.parseJSON(buffer);
        		saidHtml = `<div>${said.userName} 说 : ${said.content}</div>`;
        		$("#chatContainer").append(saidHtml+'<br/>');
        	}else if(buffer.indexOf("退出聊天")!=-1){
        		saidHtml = `<div>${buffer}</div>`;
        		$("#chatContainer").append(saidHtml+'<br/>');
        	}
        	else{
        		console.log('发送的是语音**************')
        		html = `<div id="${msgId}" class="userRec">发来了语音</div>`;
				$("#chatContainer").append(html+'<br/>');
        		return;
        	}
	      
        })	
	}

	//点击发送来的语音播放
	$(document).on("click",".userRec",function(){
		console.log("点击发送来的语音播放");
		 // var id=$(this)[0].id;
   //      var data=msg[id-1];
   //          if(!recorder){
   //              showError("请先录音");
   //              return;
   //          }
   //      recorder.play(audio,data.blob);  
   		// console.log(recorder.getBlob())
	          // console.log(recorder)
	          // console.log("tttttt")
	          console.log(dataRec)
	    // audio.src = window.URL.createObjectURL(dataRec.blob); 
	    recorder.play(audio,dataRec); 
	    
	})

//发送消息 
	$("#send").click(function(){
			var sendInfo = $("#txt").val();
			var roomId = $("#chatRoomId").val();
			if (sendInfo=='') {
				alert('请输入发言内容！');
				return false;
			}
			console.log('获取发送的信息',userName,sendInfo,roomId);
			// var ttype = 'txt';
			var mes = '{"type":"say","userName":"'+userName+'","roomId":"'+roomId+'","content":"'+sendInfo+'"}' ;
		    // ws.send('{"type":"say","userName":"'+userName+'","roomId":"'+roomId+'","content":"'+sendInfo+'"}');
		    ws.send(mes);
		    // ws.send(sendInfo);
		})

	// 修改聊天室
	$("#changeRoomBtn").click(function(){
		var roomId = $("#chatRoomId").val();
		chatDao.html(roomId);
	});   //over 修改聊天室


// }  //over - window onload