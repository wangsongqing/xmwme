<?php

class WechatAction extends actionMiddleware {

    public function index() {
        require App . '/Util/wechat.class.php';
        $options = array(
            'AppID'=>'wx5345868ae6077f56',
            'token' => 'xmeweixin', //填写你设定的key
            'encodingaeskey' => 'dJQF8gpu0Fn7mKCht88EbLqK5kYCzWuQfXmYZTzIAhu' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
        );
        $weObj = new Wechat($options);
        $weObj->valid(); //明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
        $type = $weObj->getRev()->getRevType();
        switch ($type) {
            case Wechat::MSGTYPE_TEXT:
                $weObj->text("<a href='wap.xmwme.com'>赚红包喽！</a>")->reply();
                exit;
                break;
            case Wechat::MSGTYPE_EVENT:
                $evenArr = $weObj->getRevEvent();
                if(!empty($evenArr) && isset($evenArr['event'])){
                    if($evenArr['event']=='subscribe'){
                        $weObj->text("<a href='wap.xmwme.com'>赚红包喽！</a>")->reply();
                    }else if($evenArr['event']=='CLICK'){
                        if($evenArr['key']=='BIG_HEALTH'){
                            $weObj->text("大健康")->reply();
                        }
                    }
                }
                break;
            case Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $weObj->text("QQ:1105235512")->reply();
        }

       
    }

}
