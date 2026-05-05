@echo off
:: Verificar si ya es administrador
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo Solicitando permisos de administrador...
    powershell -Command "Start-Process '%~f0' -Verb RunAs"
    exit /b
)

echo ========================================
echo   Configurando Edge para DigiTurno
echo ========================================
echo.

:: Crear la politica de Edge que permite autoplay con sonido
reg add "HKCU\SOFTWARE\Policies\Microsoft\Edge" /v AutoplayAllowed /t REG_DWORD /d 1 /f
reg add "HKCU\SOFTWARE\Policies\Microsoft\Edge\AutoplayAllowlist" /v "1" /t REG_SZ /d "http://127.0.0.1:8000" /f
reg add "HKCU\SOFTWARE\Policies\Microsoft\Edge\AutoplayAllowlist" /v "2" /t REG_SZ /d "http://localhost:8000" /f

echo.
echo ========================================
echo   LISTO! Configuracion aplicada.
echo ========================================
echo.
echo Cierra Edge completamente y vuelvelo a abrir.
echo El sonido de la pantalla de turnos funcionara
echo automaticamente sin necesidad de hacer clic.
echo.
pause
