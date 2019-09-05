<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/5/1
// +----------------------------------------------------------------------


namespace AppBundle\Util;


class EncryptUtil
{
    /**
     * 加密
     * @param $password
     * @return bool|string
     */
    public static function enPassword($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }

    /**
     * 验证密码
     * @param $password
     * @param $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}