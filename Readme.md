# WP Cron Manager

WP Cron Manager ist ein WordPress-Plugin, mit dem Sie WordPress-Cron-Events verwalten können - anzeigen, bearbeiten, löschen, pausieren, fortsetzen und neue Events hinzufügen.

## Funktionen

- Anzeige aller geplanten Cron-Events
- Hinzufügen neuer Cron-Events
- Löschen bestehender Cron-Events
- Pausieren und Fortsetzen von Cron-Events
- Sofortige Ausführung von Cron-Events
- Erstellung und Verwaltung benutzerdefinierter Cron-Zeitpläne
- Export von Cron-Events als CSV-Datei

## Installation

1. Laden Sie den Ordner `wp-cron-manager` in das Verzeichnis `/wp-content/plugins/` hoch
2. Aktivieren Sie das Plugin über das Menü 'Plugins' in WordPress
3. Greifen Sie über das Menü 'Werkzeuge' im WordPress-Administrationsbereich auf das Plugin zu

## Verwendung

### Anzeigen von Cron-Events

Navigieren Sie zu 'Werkzeuge' > 'Cron Manager' im WordPress-Administrationsbereich, um alle geplanten Cron-Events anzuzeigen.

### Hinzufügen eines neuen Cron-Events

1. Füllen Sie das Formular 'Neues Cron-Event hinzufügen' aus:
   - Hook-Name: Der Name des auszuführenden Action-Hooks
   - Argumente: Alle Argumente, die an den Hook übergeben werden sollen (im JSON-Format)
   - Zeitplan: Wählen Sie aus bestehenden oder benutzerdefinierten Zeitplänen

2. Klicken Sie auf 'Cron-Event hinzufügen'

### Verwalten von Cron-Events

Für jedes Cron-Event können Sie:
- Jetzt ausführen: Führt das Cron-Event sofort aus
- Löschen: Entfernt das Cron-Event
- Pausieren: Stoppt ein wiederkehrendes Cron-Event vorübergehend

### Benutzerdefinierte Zeitpläne

Sie können benutzerdefinierte Zeitpläne für Ihre Cron-Events erstellen:

1. Füllen Sie das Formular 'Benutzerdefinierten Zeitplan hinzufügen' aus:
   - Zeitplanname: Ein eindeutiger Bezeichner für den Zeitplan
   - Intervall: Die Zeit zwischen den Ausführungen in Sekunden
   - Anzeigename: Ein benutzerfreundlicher Name für den Zeitplan

2. Klicken Sie auf 'Benutzerdefinierten Zeitplan hinzufügen'

Benutzerdefinierte Zeitpläne können bei der Erstellung neuer Cron-Events verwendet werden.

### Exportieren von Cron-Events

Klicken Sie auf die Schaltfläche 'Cron-Events exportieren (CSV)', um eine CSV-Datei mit allen aktuellen Cron-Events herunterzuladen.

## Entwicklung

Dieses Plugin verwendet eine objektorientierte Struktur mit Namespaces. Die Hauptkomponenten sind:

- `WPCronManager\Core\Plugin`: Die Hauptklasse des Plugins, die alles initialisiert
- `WPCronManager\Core\CronManager`: Behandelt alle Cron-bezogenen Operationen
- `WPCronManager\Core\AdminPage`: Verwaltet die Admin-Oberfläche

## Support

Für Support öffnen Sie bitte ein Issue im GitHub-Repository.

## Mitwirken

Beiträge sind willkommen! Bitte reichen Sie gerne einen Pull Request ein.

## Lizenz

Dieses Projekt wird später lizenziert.