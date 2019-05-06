<?php
    //判断当前用户是否登录
    function loginCheck(){
        $user = getAuth('all');
        if (empty($user)) return false;
        //getVar登录成功后设置
        $username = isset($user['admin_name']) ? $user['admin_name']   : '';
        $password = isset($user['password']) ? $user['password'] : '';
        if (empty($username) || empty($password)) return false;
        return $password == getVar('manage_'.$username) ? true : false;
    }
    
    //通过key 获取认证数据
    function getAuth($key){
        //当前登录IP与，CookieIP是否一致
        $loginIp  = getIp();
        $auth = getCookie('manageAuth');
        $auth = explode("\t", $auth);
        list($user['admin_id'],$user['admin_name'],$user['mobile'],$user['password'],$user['url_code'],$user['group_id'],$user['group_name'], $user['loginIp'], $user['loginTime']) = empty($auth) || count($auth) < 9 ? array( '', '', '', '','', '', '', '', '') : $auth;
        if ($key == 'all'){
            return $user;
        }else{
            return isset($user[$key]) ? $user[$key] : '';
        }
    }
    
    //设置认证数据 adminAuth
    function setAuth($user, $time=0){
        addCookie('manageAuth', "{$user['admin_id']}\t{$user['admin_name']}\t{$user['mobile']}\t{$user['password']}\t{$user['url_code']}\t{$user['group_id']}\t{$user['group_name']}\t{$user['loginIp']}\t{$user['loginTime']}", $time);
    }

