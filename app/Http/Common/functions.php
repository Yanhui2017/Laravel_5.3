<?php

function time_format($time){
    if(is_numeric($time)){
        return date('Y-m-d H:i:s',$time);
    };
    return date('Y-m-d H:i:s',strtotime($time));
}