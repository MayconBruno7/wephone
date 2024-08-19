@echo off
REM Inicia o WampServer (substitua o caminho pelo caminho real do executável)
start "" "C:\wamp64\wampmanager.exe"

REM Aguarda alguns segundos para o WampServer iniciar completamente
timeout /t 10

REM Abre o navegador padrão na página do localhost
start http://controleestoque/