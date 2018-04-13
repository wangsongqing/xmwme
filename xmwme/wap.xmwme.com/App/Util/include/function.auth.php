<?php
//通过key 获取认证数据
function getAuth($key){
    //当前登录IP与，CookieIP是否一致
    $loginIp  = getIp();
    $auth = getCookie('xmwmeHomeAuth');
    $auth = explode("\t", $auth);
    list($user['user_id'],$user['telephone'],$user['password'],$user['loginIp'], $user['loginTime']) = empty($auth) || count($auth) < 5 ? array( '', '', '', '','',) : $auth;
    if ($key == 'all'){
        return $user;
    }else{
        return isset($user[$key]) ? $user[$key] : '';
    }
}

//设置认证数据 adminAuth
function setAuth($user, $time=0){
    addCookie('xmwmeHomeAuth', "{$user['user_id']}\t{$user['telephone']}\t{$user['password']}\t{$user['loginIp']}\t{$user['loginTime']}", $time);
}

//判断当前用户是否登录
function loginCheck()
{
    $user = getAuth('all');
    if (empty($user)) return false;
    //getVar登录成功后设置
    $username = isset($user['user_id']) ? $user['user_id']   : '';
    $password = isset($user['password']) ? $user['password'] : '';
    if (empty($username) || empty($password)) return false;
    return $password == getVar('xmwmwhome_'.$username) ? true : false;
}

//通过key 更新认证数据
function updateAuth($key, $value)
{
    $user = getAuth('all');
    if (isset($user[$key]))
    {
        $user[$key] = $value;
        setAuth($user, 360000);
    }
}

