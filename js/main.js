            function downloadRecord(record){
              var save_link = document.createElementNS('http://www.w3.org/1999/xhtml', 'a')
                save_link.href = URL.createObjectURL(record);
                var now=new Date;
                save_link.download = now.Format("yyyyMMddhhmmss");
                fake_click(save_link);
            }

       
            function fake_click(obj) {
            var ev = document.createEvent('MouseEvents');
            ev.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            obj.dispatchEvent(ev);
            }

            function getStr(){
              var now=new Date;
              var str= now.toDateString();
            }

            function playRecord(blob){  
              
            };  
              
            /* 视频 */  
            function scamera() {  
                var videoElement = document.getElementById('video1');  
                var canvasObj = document.getElementById('canvas1');  
                var context1 = canvasObj.getContext('2d');  
                context1.fillStyle = "#ffffff";  
                context1.fillRect(0, 0, 320, 240);  
                context1.drawImage(videoElement, 0, 0, 320, 240);  
            };  
              
            function playVideo(){  
                var videoElement1 = document.getElementById('video1');  
                var videoElement2 = document.getElementById('video2');  
                videoElement2.setAttribute("src", videoElement1);  
            };  