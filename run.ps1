$workDir = $(pwd)
$backEndDir = "$($workDir)\Backend"
$frontEndDir = "$($workDir)\Frontend"

Start-Process PowerShell -WorkingDirectory $backEndDir "php artisan serve --port=8080"
Start-Process PowerShell -WorkingDirectory $frontEndDir "ng serve --port 80"
