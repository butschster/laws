@echo off

For /L %%S in (1,1,10) do (
	ECHO "Run worker [%%S]"
    START "Worker [%%S]" /MIN php artisan queue:work -new_console:bc:t:"Worker [%%S]"
)
EXIT /B 0