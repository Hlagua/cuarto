@echo off
set JAVA_HOME="C:\Program Files\Java\jre1.8.0_491"
set JAR_PATH="C:\JasperStarter36\lib\jasperstarter.jar"

%JAVA_HOME%\bin\java.exe -jar %JAR_PATH% %*
