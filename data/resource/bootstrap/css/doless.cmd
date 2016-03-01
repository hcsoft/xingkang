
echo off
SETLOCAL enabledelayedexpansion  
for  %%f in (*.less) do (
lessc -ru %%~nf.less  %%~nf.css
lessc --clean-css %%~nf.less  %%~nf.min.css
)
ENDLOCAL
pause