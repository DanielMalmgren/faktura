<?php

function str_word_count_utf8(string $str, int $format = 0) {
    if($format === 1) {
        return preg_split('~[^\p{L}\p{N}\'-]+~u',$str);
    }
    return count(preg_split('~[^\p{L}\p{N}\'-]+~u',$str));
}

function mb_strcasecmp($str1, $str2, $encoding = null) {
    if (null === $encoding) { $encoding = mb_internal_encoding(); }
    return strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str2, $encoding));
}
