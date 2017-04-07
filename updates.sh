#!/bin/sh

cd /home/hplc/virusupdate
/usr/local/bin/php updates.php
export HTTP_PROXY="192.168.1.8:8080"
/usr/local/bin/wget -nc -i targets.url

/usr/bin/ftp -a 192.168.1.139 << EOF
user cy password
cd Update
binary
mput *.bin
a
quit
EOF
