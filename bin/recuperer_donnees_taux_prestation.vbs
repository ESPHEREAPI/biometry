Set WshShell = CreateObject("WScript.Shell")
WshShell.Run chr(34) & "C:\wamp64\www\biometry\bin\recuperer_donnees_taux_prestation.bat" & Chr(34), 0
Set WshShell = Nothing