<?php

    /**
     * Contao Open Source CMS
     *
     * @version   php 5
     * @package   con4gis
     * @author    Tobias Dobbrunz &#40;http://www.kuestenschmiede.de&#41;
     * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
     * @copyright Küstenschmiede GmbH Software & Design 2014
     * @link      https://www.kuestenschmiede.de
     * @filesource
     */


    /**
     * Miscellaneous
     */
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['BACK']   = 'Zurück';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['CANCEL'] = 'Abbrechen';

    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['TITLESUB'] = 'Content-Management für Ihr Geo-Informations-System';
//@todo: shorter!
// $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['INTRO']              = 'Wir möchten es leichter machen, Karten und Kartenprojekte im Internet zu veröffentlichen. con4gis bietet eine einfache Möglichkeit Karten mit Hilfe mächtiger OpenSource Bibliotheken in Websites einzubinden.</br></br>
// Wer ein Geoinformationssystem aufbauen möchte, kann das ohne Programmierkenntnisse schnell und einfach mit con4gis und Contao erreichen - stellen Sie verschiedene Werkzeuge auf der Karte zur Verfügung, binden Sie den Karteneditor an oder zeigen Sie ausgewählte Daten direkt aus der OpenStreetMap-Datenbank an.</br></br>Doch con4gis ist noch mehr. Zum Beispiel ist das Forum ein fester Bestandteil des Baukastens. Mit Hilfe des Forums können Beiträge georeferenziert werden und in der Karte dargestellt werden. Viele weitere Erweiterung sind in der Planung.';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['FOOTER'] = 'Ein Projekt der %s.';

    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['HEADLINE']      = 'Migration (cfs_%1$s -> con4gis_%1$s)';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['INTRO']         = 'Diese Migration kopiert die Daten von "cfs_%1$s" nach "con4gis_%1$s" und konfiguriert Contao anschließend "con4gis_%1$s" zu verwenden.';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['INTROWARN']     = 'Achtung: Diese Aktion ÜBERSCHREIBT alle derzeitigen "con4gis_%s"-Daten.';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['NOMODULEERROR'] = 'Die Migration kann nicht ausgeführt werden, da %s nicht installiert ist. Bitte installieren Sie das Modul und versuchen Sie es erneut.';

    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['SUCCESS']       = 'Erfolgreich';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['TRANSFEREDROW'] = '%d von %d Einträgen erfolgreich übertragen';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['TRANSFEREDCOL'] = '%d Felder erfolgreich übertragen';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['SUCCESSMSG_1']  = 'Transfer erfolgreich abgeschlossen.';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['SUCCESSMSG_2']  = 'Contao-Einstellung erfolgreich abgeschlossen. Das Modul "cfs_%s" kann jetzt deinstalliert werden.';

    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['FAIL']      = 'Fehlgeschlagen';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['FAILMSG_1'] = '%d von %d Übertragungen fehlgeschlagen. Bitte überprüfen Sie Ihre Installation und versuchen Sie es erneut.';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATION']['FAILMSG_2'] = 'Contao-Einstellung fehlgeschlagen. Bitte überprüfen Sie Ihre Installation und versuchen Sie es erneut.';

// button
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['BTN']['MIGRATE']   = 'Migration starten';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['BTN']['UNINSTALL'] = '"cfs_%s" deinstallieren';

// links
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['CONTAO_BOARD']  = 'Community Forum (DE)';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['GITHUB']        = 'Fork on github';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['MIGRATIONTOOL'] = 'Migrationstool';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['INSTALL']       = '%s installieren';

    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['con4gis_website']     = 'con4gis Projektwebsite';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['osm_website']         = 'Website der OpenStreetMap';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['ol_website']          = 'OpenLayers Website';
    $GLOBALS['TL_LANG']['MSC']['C4G_BE_INFO']['overpassapi_website'] = 'Overpass-API Informationen';


    $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_successful']         = "%s erfolgreich hochgeladen: \\n- Größe: %s KB";
    $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_error']              = "Die Datei konnte nicht hochgeladen werden";
    $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_invalid_extension']  = 'Die Datei: %s hat keinen erlaubten Erweiterungs-Typen.';
    $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_invalid_size']       = '\\n Die Datei darf nicht größer sein als: %s KB.';

    $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['image_upload_successful']         = "%s erfolgreich hochgeladen: \\n- Größe: %s KB \\n- Bild Breite x Höhe: %s x %s";
    $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['image_upload_error']              = "Das Bild konnte nicht hochgeladen werden";
    $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['image_upload_invalid_extension']  = 'Das Bild: %s hat ein nicht erlaubtem Format.';
    $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['image_upload_invalid_size']       = '\\n Das Bild draf nicht größer sein als: %s KB.';
    $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['image_upload_invalid_dimensions'] = '\\n Breite x Höhe = %s x %s \\n Die Maße dürfen nicht größer sein als: %s x %s';

    // messages for exceptions by code
    // $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['exception_xxx'] = "";


?>