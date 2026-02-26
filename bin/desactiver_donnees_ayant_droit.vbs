Set WshShell = CreateObject("WScript.Shell")
WshShell.Run chr(34) & "C:\wamp64\www\biometry\bin\desactiver_donnees_ayant_droit.bat" & Chr(34), 0
Set WshShell = Nothing