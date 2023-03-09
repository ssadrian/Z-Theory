$workDir = $(pwd)
$backEndDir = "$($workDir)\Backend"
$frontEndDir = "$($workDir)\Frontend"

Start-Process PowerShell -WorkingDirectory $backEndDir "php artisan serve"
Start-Process PowerShell -WorkingDirectory $frontEndDir "ng serve --port 80"
