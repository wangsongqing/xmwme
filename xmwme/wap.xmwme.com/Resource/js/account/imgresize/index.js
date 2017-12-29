$('input[type="file"]').on("change", function() {
    var name = $(this).attr("name");
    alert(name);
    // var input = document.querySelector('input[type="file"]');
        // 也可以传入图片路径：lrz('../demo.jpg', ...
        lrz(this.files[0], {
			width:800,
			height:500,
			quality:0.9,
			aregister:false,
            before: function() {
            	BB.popup.loading.show();
                console.log('压缩开始');
            },
            fail: function(err) {
            	BB.popup.loading.hide();
            	BB.popup.alert('只支持jpeg,jpg,png,gif');
            	this.aregister = false;
            },
            always: function() {
                console.log('压缩结束');
            },
            done: function (results) {
            // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
            //console.log(results);

            // 以下为演示用内容
            var report = document.querySelector('#' + name);
            report.innerHTML = '';
            //tip.innerHTML = '<p>正在生成和上传..</p> <small class="text-muted">演示未优化移动端内存占用，可能会造成几秒内卡顿或闪退，不代表真实表现，请亲测。</small>';
            //demo_report('原始图片', results.origin, results.origin.size);
            if(this.aregister == true) return false;
            
            this.aregister = true;
            
            setTimeout(function () {
               // demo_report('客户端预压的图片', results.base64, results.base64.length * 0.8);

                // 发送到后端
                var data = {
                    base64: results.base64,      //results.clearBase64,results.base64
                    size: results.base64.length, // 校验用，防止未完整接收
                    img_name:name,
                    t : Math.random()
                };
                $.ajax({
                    type: "post",
                    url : "/mybank/ajax_image/",
                    dataType:'json',
                    data:data,
                    success: function(data){
                    	BB.popup.loading.hide();
                        $('#scsfz_img').attr('src',data.url + data.msg+'?r='+Math.random());
                    	this.aregister = false;
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                    	BB.popup.loading.hide();
                    	BB.popup.alert('网络异常,请稍后重试');
                    	this.aregister = false;
                    }
                });

            }, 100);
            }
        });

    /**
     * 演示报告
     * @param title
     * @param src
     * @param size
     */
    function demo_report(title, src, size) {
        var img = new Image(),
            li = document.createElement('li'),
            size = (size / 1024).toFixed(2) + 'KB';

        if(size === 'NaNKB') size = '';

        img.onload = function () {
            var content = '<ul>' +
                '<li>' + title + '（' + img.width + ' X ' + img.height + '）</li>' +
                '<li class="text-cyan">' + size + '</li>' +
                '</ul>';

            li.className = 'item';
            li.innerHTML = content;
            li.appendChild(img);
            document.querySelector('#report').appendChild(li);
        };

        img.src = typeof src === 'string' ? src : URL.createObjectURL(src);
    }

    // 演示用服务器太慢，做个延缓加载
})();
