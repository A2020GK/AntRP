<?php

namespace Smarty\Extension;

function strftime(string $format,int|null $timestamp):string|false {
    return date($format,$timestamp);
}