# Projekt-DigiSchulNet

Mit dem hier herunterzuladenden Code wurde, basierend auf dem Projekt „Graphical Ego-Centered 
Network Survey Interface – GENSI“ (http://www.tobiasstark.nl/GENSI/GENSI.htm), eine 
längsschnittliche Online-Erhebung ego-zentrierter Netzwerke von Lehrkräften in dem vom 
Bundesministerium für Bildung und Forschung geförderten Forschungsprojekt „Digitale 
Schulentwicklung in Netzwerken. Gelingensbedingungen schulübergreifender Kooperation bei der 
digitalen Transformation – DigiSchulNet“ (2018-2021) (https://digi-ebf.de/digischulnet) umgesetzt. 
Die Software GENSI ist unter GNU General Public License 3 veröffentlicht und konnte daher durch das 
Projektteam im Quellcode für die Bedarfe der DigiSchulNet-Studie modifiziert werden. Angelehnt an 
die Unterscheidung von drei Formen der Lehrkräftekooperation (Gräsel, Fußangel & Pröbstel, 2006) 
werden die Teilnehmenden durch eine grafisch unterstützte Oberfläche durch die Erhebung ihrer 
persönlichen, professionellen Kooperationsnetzwerke geführt. In der hier verfügbaren Version 
werden Fragebögen zu zwei verschiedenen Innovationsthemen in Schulnetzwerken umgesetzt: 
Digitalisierungsbezogene Schulentwicklung sowie Schulentwicklung im Kontext Bildung für 
nachhaltige Entwicklung. In jedem Fragebogen werden vier Namensgeneratoren und zu den 
erhobenen Namen jeweils mehrere Namensinterpretatoren abgefragt.

Zur Einhaltung von datenschutzrechtlichen Vorgaben wurde die Online-Umfrage auf 
hochschuleigenen Servern administriert. Die Umfrage bzw. (temporäre) Datenhaltung wurde über 
einen Linux-Server (virtuelle Maschine; Betriebssystem Ubuntu 18.04 LTS) realisiert. Im Rahmen der 
Online-Befragung anfallende Server-Logdaten (Apache) werden nur 7 Tage vorgehalten. Das 
Umfragetool selbst produziert keine eigenen Logdaten. Der Quellcode sowie die Fragebögen können 
an die Bedarfe einer eigenen Erhebung angepasst werden. 
