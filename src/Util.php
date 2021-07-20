<?php
namespace Asialong\KwaixiaodianSdk;

class Util
{
    /**
     * 字符串转Unicode编码
     * @param $strLong
     * @return string
     */
    public static function unicode_encode($strLong) {
        $strArr = preg_split('/(?<!^)(?!$)/u', $strLong);//拆分字符串为数组(含中文字符)
        $resUnicode = '';
        foreach ($strArr as $str)
        {
            if (!in_array($str,['&','<','>'])){
                $resUnicode .= $str;
                continue;
            }
            $bin_str = '';
            $arr = is_array($str) ? $str : str_split($str);//获取字符内部数组表示,此时$arr应类似array(228, 189, 160)
            foreach ($arr as $value)
            {
                $bin_str .= decbin(ord($value));//转成数字再转成二进制字符串,$bin_str应类似111001001011110110100000,如果是汉字"你"
            }
            $bin_str = preg_replace('/^.{4}(.{4}).{2}(.{6}).{2}(.{6})$/', '$1$2$3', $bin_str);//正则截取, $bin_str应类似0100111101100000,如果是汉字"你"
            $unicode = dechex(bindec($bin_str));//返回unicode十六进制
            $_sup = '';
            for ($i = 0; $i < 4 - strlen($unicode); $i++)
            {
                $_sup .= '0';//补位高字节 0
            }
            $str =  '\\u' . $_sup . $unicode; //加上 \u  返回
            $resUnicode .= $str;
        }
        return $resUnicode;
    }

    /**
     * Unicode编码转字符串方法1
     * @param $name
     * @return mixed|string
     */
    public static function unicode_decode2($name)
    {
        // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $name, $matches);
        if (!empty($matches))
        {
            $name = '';
            for ($j = 0; $j < count($matches[0]); $j++)
            {
                $str = $matches[0][$j];
                if (strpos($str, '\\u') === 0)
                {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code).chr($code2);
                    $c = iconv('UCS-2', 'UTF-8', $c);
                    $name .= $c;
                }
                else
                {
                    $name .= $str;
                }
            }
        }
        return $name;
    }

    /**
     * Unicode编码转字符串方法2
     * @param $str
     * @return mixed|string
     */
    public static function unicode_decode($str){
        $json = '{"str":"' . $str . '"}';
        $arr = json_decode($json, true);
        if (empty($arr)) return '';
        return $arr['str'];
    }
}