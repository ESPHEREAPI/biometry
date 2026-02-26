Set WshShell = CreateObject("WScript.Shell")
WshShell.Run chr(34) & "C:\wamp64\www\biometry\bin\recuperer_donnees_adherent.bat" & Chr(34), 0
Set WshShell = Nothing