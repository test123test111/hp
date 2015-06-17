#!/bin/sh

while true
do
num=` ps aux | grep 'php /var/www/html/aimei_storage/yii position-online/run' | grep -v grep | wc -l`
if [[ num -eq  1 ]]
then
    echo OK;
    sleep 5;
else
    php /var/www/html/aimei_storage/yii position-online/run &
    sleep 5
fi
done
