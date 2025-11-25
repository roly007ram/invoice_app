@echo off
REM backup_invoice_app.bat - Crea dump MySQL y zip del proyecto
setlocal

REM ---------- CONFIGURACIÓN (EDITA SI ES NECESARIO) ----------
set "DB_NAME=invoice_app"
set "DB_USER=root"
set "DB_PASS="

REM Ruta al mysqldump de XAMPP (ajusta si tu instalación está en otra carpeta)
set "MYSQLDUMP=C:\xampp\mysql\bin\mysqldump.exe"
REM Ruta típica a 7-Zip (si está instalado). Si no la tienes, el script usará Compress-Archive.
set "SEVENZIP=C:\Program Files\7-Zip\7z.exe"

REM Directorio del script y carpeta de backups
set "SCRIPT_DIR=%~dp0"
REM Quitar barra final si existe (evita problemas con "\\" antes de la comilla)
if "%SCRIPT_DIR:~-1%"=="\\" set "SCRIPT_DIR=%SCRIPT_DIR:~0,-1%"
set "BACKUP_DIR=%SCRIPT_DIR%\backups"

REM Obtiene timestamp en formato YYYYMMDD_HHMMSS usando CMD nativo (evita dependencia de PowerShell)
for /f "tokens=2-4 delims=/ " %%a in ('date /t') do (set mydate=%%c%%a%%b)
for /f "tokens=1-2 delims=/:" %%a in ('time /t') do (set mytime=%%a%%b)
set "TIMESTAMP=%mydate%_%mytime%"

if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

set "TEMP_DIR=%TEMP%\invoice_app_backup_%TIMESTAMP%"
REM Asegurarse de que TEMP_DIR no termine con barra final para evitar escapar la comilla
if "%TEMP_DIR:~-1%"=="\\" set "TEMP_DIR=%TEMP_DIR:~0,-1%"
mkdir "%TEMP_DIR%"

echo.
echo ===== Backup de invoice_app - %TIMESTAMP% =====
echo.

echo Creando volcado de la base de datos...
if exist "%MYSQLDUMP%" (
    if "%DB_PASS%"=="" (
        "%MYSQLDUMP%" -u"%DB_USER%" "%DB_NAME%" > "%TEMP%\db_backup_%TIMESTAMP%.sql"
    ) else (
        "%MYSQLDUMP%" -u"%DB_USER%" -p"%DB_PASS%" "%DB_NAME%" > "%TEMP%\db_backup_%TIMESTAMP%.sql"
    )
) else (
    echo ERROR: No se encontró mysqldump en "%MYSQLDUMP%".
    echo Edita la variable MYSQLDUMP al inicio del script.
    pause
    goto :EOF
)

echo Copiando archivos del proyecto al directorio temporal (excluye backups)...
robocopy "%SCRIPT_DIR%" "%TEMP_DIR%" /E /COPYALL /R:1 /W:1 /XD "%BACKUP_DIR%"

if errorlevel 8 (
    echo Advertencia: Robocopy devolvió un código de error. Algunos archivos pueden no haberse copiado.
)

echo Moviendo volcado SQL al paquete de backup...
move "%TEMP%\db_backup_%TIMESTAMP%.sql" "%TEMP_DIR%" >nul

echo Comprimiendo en zip (esto puede tardar)...
if exist "%SEVENZIP%" (
    echo Usando 7-Zip para crear el zip...
    "%SEVENZIP%" a -tzip "%BACKUP_DIR%\invoice_app_backup_%TIMESTAMP%.zip" "%TEMP_DIR%\*" -mx=9 >nul 2>&1
) else (
    echo 7-Zip no encontrado, usando Compress-Archive de PowerShell...
    powershell -NoProfile -Command "Add-Type -AssemblyName System.IO.Compression.FileSystem; [System.IO.Compression.ZipFile]::CreateFromDirectory('%TEMP_DIR%', '%BACKUP_DIR%\invoice_app_backup_%TIMESTAMP%.zip', 'Optimal', $true)" >nul 2>&1
)

if exist "%BACKUP_DIR%\invoice_app_backup_%TIMESTAMP%.zip" (
    echo.
    echo Backup creado correctamente: "%BACKUP_DIR%\invoice_app_backup_%TIMESTAMP%.zip"
) else (
    echo.
    echo ERROR: No se pudo crear el zip. Revisa los logs en %TEMP%\robocopy_log_%TIMESTAMP%.txt
)

echo Limpiando archivos temporales...
rmdir /s /q "%TEMP_DIR%"

endlocal
echo.
pause
