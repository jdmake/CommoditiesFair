<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/1
// +----------------------------------------------------------------------


namespace AppBundle\Util;


class ModelFilterConfigUtil
{
    public static $config = [
        1 => array(
            0 =>
                array(
                    'id' => 'welfare',
                    'title' => '福利',
                    'choice' =>
                        array(
                            0 =>
                                array(
                                    'title' => '五险一金',
                                    'value' => '五险一金',
                                ),
                            1 =>
                                array(
                                    'title' => '包住',
                                    'value' => '包住',
                                ),
                            2 =>
                                array(
                                    'title' => '包吃',
                                    'value' => '包吃',
                                ),
                            3 =>
                                array(
                                    'title' => '年底双薪',
                                    'value' => '年底双薪',
                                ),
                            4 =>
                                array(
                                    'title' => '周末双休',
                                    'value' => '周末双休',
                                ),
                            5 =>
                                array(
                                    'title' => '交通补助',
                                    'value' => '交通补助',
                                ),
                            6 =>
                                array(
                                    'title' => '加班补助',
                                    'value' => '加班补助',
                                ),
                            7 =>
                                array(
                                    'title' => '饭补',
                                    'value' => '饭补',
                                ),
                            8 =>
                                array(
                                    'title' => '话补',
                                    'value' => '话补',
                                ),
                            9 =>
                                array(
                                    'title' => '房补',
                                    'value' => '房补',
                                ),
                        ),
                ),
            1 =>
                array(
                    'id' => 'sendDate',
                    'title' => '时间',
                    'choice' =>
                        array(
                            0 =>
                                array(
                                    'title' => '1天之内',
                                    'value' => '1d',
                                ),
                            1 =>
                                array(
                                    'title' => '3天之内',
                                    'value' => '3d',
                                ),
                            2 =>
                                array(
                                    'title' => '7天之内',
                                    'value' => '7d',
                                ),
                            3 =>
                                array(
                                    'title' => '15天之内',
                                    'value' => '15d',
                                ),
                            4 =>
                                array(
                                    'title' => '一个月之内',
                                    'value' => '1m',
                                ),
                        ),
                ),
            2 =>
                array(
                    'id' => 'salary',
                    'title' => '薪资',
                    'choice' =>
                        array(
                            0 =>
                                array(
                                    'title' => '1000元以下',
                                    'value' => '0_999',
                                ),
                            1 =>
                                array(
                                    'title' => '1000-2000元',
                                    'value' => '1000_2000',
                                ),
                            2 =>
                                array(
                                    'title' => '2000-3000元',
                                    'value' => '2000_3000',
                                ),
                            3 =>
                                array(
                                    'title' => '3000-5000元',
                                    'value' => '3000_5000',
                                ),
                            4 =>
                                array(
                                    'title' => '5000-8000元',
                                    'value' => '5000_8000',
                                ),
                            5 =>
                                array(
                                    'title' => '8000元以上',
                                    'value' => '8000_99999',
                                ),
                        ),
                ),
            3 =>
                array(
                    'id' => 'education',
                    'title' => '学历',
                    'choice' =>
                        array(
                            0 =>
                                array(
                                    'title' => '初中',
                                    'value' => '初中',
                                ),
                            1 =>
                                array(
                                    'title' => '高中',
                                    'value' => '高中',
                                ),
                            2 =>
                                array(
                                    'title' => '技校',
                                    'value' => '技校',
                                ),
                            3 =>
                                array(
                                    'title' => '中专',
                                    'value' => '中专',
                                ),
                            4 =>
                                array(
                                    'title' => '大专',
                                    'value' => '大专',
                                ),
                            5 =>
                                array(
                                    'title' => '本科',
                                    'value' => '本科',
                                ),
                            6 =>
                                array(
                                    'title' => '硕士',
                                    'value' => '硕士',
                                ),
                            7 =>
                                array(
                                    'title' => '博士',
                                    'value' => '博士',
                                ),
                        ),
                ),
            4 =>
                array(
                    'id' => 'experience',
                    'title' => '经验',
                    'choice' =>
                        array(
                            0 =>
                                array(
                                    'title' => '1-2年',
                                    'value' => '1-2年',
                                ),
                            1 =>
                                array(
                                    'title' => '3-5年',
                                    'value' => '3-5年',
                                ),
                            2 =>
                                array(
                                    'title' => '6-7年',
                                    'value' => '6-7年',
                                ),
                            3 =>
                                array(
                                    'title' => '8-10年',
                                    'value' => '8-10年',
                                ),
                            4 =>
                                array(
                                    'title' => '10年以上',
                                    'value' => '10年以上',
                                ),
                        ),
                ),
        ),
        2 => []
    ];
}