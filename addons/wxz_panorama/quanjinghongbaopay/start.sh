#!/bin/sh
SERVICE='cron_quanjinghongbao_pay.php'
 
if ps ax | grep -v grep | grep $SERVICE > /dev/null
then
    echo "$SERVICE service running, everything is fine"
else
    echo "$SERVICE is not running"
sh /home/wwwroot/quanjinghongbaopay/run.sh
fi
 
