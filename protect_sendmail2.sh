#!/bin/sh

while true
do
num=` ps aux | grep 'php /www/wwwroot/invertory/yii send-approval-email/run2' | grep -v grep | wc -l`
if [[ num -gt  0 ]]
then
    echo OK;
    sleep 5;
else
    php /www/wwwroot/invertory/yii send-approval-email/run2 &
    sleep 5
fi
done
