<?php
set_time_limit(0);                                     //���ó������г�ʱ��
ob_start();                                            //�򿪴��̻���
require_once('Config/apppath.config.php');             //��ʼ��Ӧ�ó���·��  
header("Content-type: text/html; charset=utf-8");      //ָ������
ini_set("date.timezone", "Asia/Shanghai");             //����ʱ������
require_once(Lib . '/Core/App.php');                     //����װ����
$app = new App();                                      //ʵ����һ������
$app->path = Lib;                           //ָ�����·�� 
$app->isCached = IsCached;                      //�Ƿ񻺴������Դ
$app->cacheDir = CacheDir;                      //������Դ����Ŀ¼
$app->rootPath = Root;                          //ָ����·��
$app->module = Module;                        //ָ��Ĭ�Ͽ�����
$app->exceptionModule = ExceptionModule;               //ָ�������쳣ʱ�Ŀ�����(���Ҳ�������Ŀ�����)   
$app->init();               //��ʼ�����
require_once(App . "/Util/actionMiddleware.php");
require_once(App . "/Util/modelMiddleware.php");
$content = $app->execute();

//���´�������:��action return ���ݵ�ʱ��Ҳ�������;ʹ��ܸ��ӵ���������һ����API��
if ($content != null) {
    echo $content;
}
$app = null;                                           //���ٶ���
?>