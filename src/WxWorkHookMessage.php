<?php
namespace Mili\Tool;

class WxWorkHookMessage
{
    /**
     * @param string $key 机器人key
     * @param string $content 发送的内容
     * @param array $mentionedList userid的列表，提醒群中的指定成员(@某个成员)，@all表示提醒所有人，如果开发者获取不到userid，可以使用mentioned_mobile_list
     * @param array $mentionedMobileList 手机号列表，提醒手机号对应的群成员(@某个成员)，@all表示提醒所有人
     * @return array
     * @author: xiehuayun <459558473@qq.com>
     * @describe:发送微信机器人信息
     */
    public static function send(string $key, string $content, array $mentionedList = [], array $mentionedMobileList = []) : array {
        try {
            if (empty($key)) {
                return ['code' => 1 , 'msg' => 'key不能为空'];
            }
            $data = [
                'msgtype' => 'text',
                'text' => [
                    'content' => $content,
                ],
            ];
            if (!empty($mentionedList)) {
                $data['mentioned_list'] = $mentionedList;
            }
            if (!empty($mentionedMobileList)) {
                $data['mentioned_mobile_list'] = $mentionedMobileList;
            }
            $url = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=' . $key;
            self::sendPost($url, $data);
            return ['code' => 0 , 'msg' => '发送成功'];
        }catch (\Exception $e){
            return ['code'=> 1 , 'msg' => $e->getMessage()];
        }
    }
    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $postData post键值对数据
     * @return string
     */
    private static function sendPost(string $url, array $postData) :string {

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/json',
                'content' => json_encode($postData, JSON_UNESCAPED_UNICODE),
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }
}