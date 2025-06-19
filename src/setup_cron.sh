#!/bin/bash
CRON_JOB="*/5 * * * * php $(pwd)/cron.php >/dev/null 2>&1"
CRON_TAB=$(crontab -l 2>/dev/null | grep -v 'cron.php' ; echo "$CRON_JOB")
echo "$CRON_TAB" | crontab -
echo "CRON job set to run cron.php every 5 minutes." 