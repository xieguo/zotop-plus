<?php

namespace Modules\Translator\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
//use Modules\Translator\Models\Translator;

class TranslatorController extends AdminController
{
    /**
     * 翻译
     *
     * @return Response
     */
    public function translate(Request $request)
    {
        $source    = $request->input('source');
        $from      = $request->input('from');
        $to        = $request->input('to');
        $maxlength = $request->input('maxlength');
        $separator = $request->input('separator');
        $format    = $request->input('format');

        // 判断转换翻译格式
        if (in_array(strtolower($format), ['slug', 'permalink'])) {
            $separator = $separator ?: '-';
            $translate = translate_slug($source, $separator);
        } elseif (in_array(strtolower($format), ['id', 'key', 'fieldname'])) {
            $separator = $separator ?: '_';
            $translate = translate_slug($source, $separator);
        } else {
            $translate = translate($source, $from ?: null, $to ?: null);
        }
        
        // 如果有长度限制，截取指定长度
        if ($maxlength = intval($maxlength)) {
            $translate = substr($translate, 0, $maxlength);
            $translate = trim($translate, $separator);
        }

        return $translate;
    }


}
