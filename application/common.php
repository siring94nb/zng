<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 输出
 * [pp description]
 * @param  boolean $bool [description]
 * @return [type]        [description]
 * 作者：fyk
 */
function pp($bool=false){
    list($callee) = debug_backtrace();
    $arguments = func_get_args();
    $totalArguments = count($arguments);

    echo "<fieldset class='dump'>" . PHP_EOL .
        "<legend>{$callee['file']} @ line: {$callee['line']}</legend>" . PHP_EOL .
        '<pre>';

    $i = 0;
    foreach ($arguments as $argument) {
        echo '<br /><strong>Debug #' . (++$i) . " of {$totalArguments}</strong>: ";

        if (! empty($argument)
            && (is_array($argument) || is_object($argument))
        ) {
            print_r($argument);
        } else {
            var_dump($argument);
        }
    }

    echo '</pre>' . PHP_EOL .
        '</fieldset>' . PHP_EOL;
    if(!$bool){

    }
}