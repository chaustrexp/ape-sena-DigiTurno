@echo off
title DigiTurno - Pantalla
echo Abriendo pantalla con audio automatico en Edge...
start msedge --autoplay-policy=no-user-gesture-required --user-data-dir="%TEMP%\DigiTurnoPantallaEdge" --start-fullscreen --no-first-run --disable-default-apps "http://127.0.0.1:8000/pantalla"
exit
