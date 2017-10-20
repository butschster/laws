@echo off

set /p total_workers="Number of workers: "


For /L %%S in (1,1,%total_workers%) do (
	ECHO "Run worker [%%S]"
    START "Worker [%%S]" /MIN php artisan queue:work --sleep=3 --tries=3 -new_console:bc:t:"Worker [%%S]"
)

EXIT /B 0