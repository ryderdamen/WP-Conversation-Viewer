<?php

function getDataFile($name) {
    return file_get_contents(dirname(__FILE__) . '/' .  $name);
}