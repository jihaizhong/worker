<?php
	use Workerman\Worker;
	require_once __DIR__ . '/Workerman/Autoloader.php';
use Workerman\Protocols\Websocket;
	// 创建一个Worker监听2345端口，使用http协议通讯
    $http_worker = new Worker("websocket://127.0.0.1:8000");
	// $http_worker = new Worker("websocket://192.168.10.114:8000");

	// 启动4个进程对外提供服务
	$http_worker->count = 4;

	// 接收到浏览器发送的数据时回复hello world给浏览器
	// $http_worker->onMessage = function($connection, $data)
	// {
	//     // 向浏览器发送hello world
	//     // $connection->send('hello world');
	//     handle_message($connection, $data);
	// };

	$global_uid = 0;

    $client = []; //保存客户端信息
    $out = [];

    // 当客户端连上来时分配uid，并保存连接，并通知所有客户端
    function handle_connection($connection)
    {
        global $http_worker, $global_uid;
         $connection->websocketType = Websocket::BINARY_TYPE_ARRAYBUFFER;   //设置传输二进制数据
        // 为这个连接分配一个uid
        $connection->uid = ++$global_uid;

        foreach($http_worker->connections as $conn)
        {
            // $conn->send("{$connection->uid}");
            // $conn->send($data);
            // $conn->send($connection->uid);
            // $conn->send("{$connection->uid}-testConection");
        }
    }
    // 当客户端发送消息过来时，转发给所有人
    function handle_message($connection, $data)
    {
      global $http_worker,$client,$out;
      // $connection->websocketType = Websocket::BINARY_TYPE_BLOB;   //文档说是传输文本，经过测试，确实是，
       $connection->websocketType = Websocket::BINARY_TYPE_ARRAYBUFFER;   //设置传输二进制数据
       var_dump($data);
        $s = gettype($data);  //返回字符串string
        echo $s; //返回字符串string
        //把客户端传过来的json字符串$data 强制转为数组
        //
        // $arr = json_decode($data,true);
        // $a = $arr["userName"];
        // //echo "$a";  //可以输出用户的名字
      
        // $client["id"] = $connection->uid;
        // // $client["name"] = $data;
        // $out[] = $client;
        // 
        // var_dump($out);
        // foreach ($out as $key => $value) {
        //     echo "for xunhuan out : value";
        //     echo "$value";
        // }
        foreach($http_worker->connections as $conn)
        {
            $conn->send($data);
            // $conn->send('users:'.json_encode($out));
            // $conn->send($data);
            // $conn->send($connection->uid);
        }
    }

    // 当客户端断开时，广播给所有客户端
    function handle_close($connection)
    {
        global $http_worker,$client,$out;
        //退出的时候删除用户
        foreach ($out as $key => $value) {
          
            if ($value["id"] == $connection->uid) {
                // echo "delet array";
                // unset($out[$key]);
            }
        }
        foreach($http_worker->connections as $conn)
        {
            $conn->send("user[{$connection->uid}] 退出聊天室");
        }
    }

    $http_worker->onConnect = 'handle_connection';  
	$http_worker->onMessage = 'handle_message';  
	$http_worker->onClose = 'handle_close';  

	Worker::runAll();