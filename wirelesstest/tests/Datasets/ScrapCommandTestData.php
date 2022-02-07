<?php

dataset('scrapcommandtestdata', function () {
    return ['https://videx.comesconnected.com/',file_get_contents('index.html')];
});
