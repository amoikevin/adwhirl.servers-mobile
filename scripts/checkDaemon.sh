#!/bin/sh

if ps aux | grep -v grep | grep java | grep Daemon  > /dev/null
then
    :
else
    echo "AdWhirl Daemon is down" | mail -s "**PROBLEM - AdWhirl Daemon is CRITICAL" youremail@yourdomain.com
fi
