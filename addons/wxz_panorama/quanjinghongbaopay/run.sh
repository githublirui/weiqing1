#!/bin/bash

PHP=php
BIN=/home/wwwroot/quanjinghongbaopay/cron_quanjinghongbao_pay.php
`nohup $PHP $BIN >>/dev/null 2>&1 &`
