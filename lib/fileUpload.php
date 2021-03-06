<?php

    try {
        define("TL_MODE", "FE");
        $sRootPath = dirname($_SERVER['SCRIPT_FILENAME']) . "/../../../../";
        require_once($sRootPath . "system/initialize.php");

        // User not logged in...
        if (!FE_USER_LOGGED_IN) {
            header('HTTP/1.0 403 Forbidden');
            echo "Forbidden";
            die();
        }


        \System::loadLanguageFile("default");


        // xss cleanup
        $_FILES = \Input::xssClean($_FILES);

        $sServerName = \Environment::get("serverName");
        $sRequestUri = \Environment::get("requestUri");
        $sHttps      = \Environment::get("https");
        $path        = \Environment::get("path");


        $sConfigUploadPath = \Session::getInstance()->get("con4gisFileUploadPath");
        $sConfigUploadPath = \Input::xssClean($sConfigUploadPath);
        $sSubfolder        = date("Y-m-d");

        //if not configured, use fallbackpath
        if (empty($sConfigUploadPath)) {
            $sUploadDir = "/files/uploads/";
        } else {
            $sUploadDir = "/" . $sConfigUploadPath;
        }


        // add subfolder
        $sUploadDir = $sUploadDir . $sSubfolder;


        // create if not exist
        if (!is_dir(TL_ROOT . "/" . $sUploadDir)) {
            mkdir(TL_ROOT . "/" . $sUploadDir, 0777, true);
        }


        $sValidFileTypes = \Session::getInstance()->get("c4g_forum_bbcodes_editor_uploadTypes");
        $sMaxFileSize    = \Session::getInstance()->get("c4g_forum_bbcodes_editor_maxFileSize");



        if (empty($sValidFileTypes)) {
            // get system-configured allowed filetypes
            $sValidFileTypes = \Config::get("uploadTypes");
        }
        if (empty($sMaxFileSize)) {
            // get system-configured max filesize
            $sMaxFileSize = \Config::get("maxFileSize");
        }


        $sValidFileTypes = \Input::xssClean($sValidFileTypes);
        $sMaxFileSize    = \Input::xssClean($sMaxFileSize);

        //config array
        $aConfig = array(
            'maxsize' => intval($sMaxFileSize),          // maximum file size, in KiloBytes (2 MB)
            'type'    => explode(",", $sValidFileTypes)        // allowed extensions
        );

        $sReturn         = '';
        $CKEditorFuncNum = \Input::get('CKEditorFuncNum');
        if (!empty($_FILES['uploadFile']) && strlen($_FILES['uploadFile']['name']) > 1 && !empty($_FILES['uploadFile']['tmp_name'])) {
            $aInfo         = pathinfo($_FILES['uploadFile']['name']);
            $sUploadDir    = trim($sUploadDir, '/') . '/';
            $sFileName     = basename($_FILES['uploadFile']['name']);
            $sUniqFileName = md5(uniqid('', true)) . "." . $aInfo['extension'];

            // get protocol and host name to send the absolute image path to CKEditor
            $sProtocol = !empty($sHttps) ? 'https://' : 'http://';
            $sSite     = $sProtocol . $sServerName . $path . '/system/modules/con4gis_core/lib/deliver.php?file=';

            // build file path
            $sUploadpath = TL_ROOT . '/' . $sUploadDir . $sUniqFileName;       // full file path
            $sExtension  = pathinfo($_FILES['uploadFile']['name']);
            $sType       = $sExtension['extension'];       // gets extension

            // Checks if the file has allowed type, size, width and height (for images)
            if (!in_array($sType, $aConfig['type'])) {
                $sError = sprintf($GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_invalid_extension'], $_FILES['uploadFile']['name']);
            }elseif ($_FILES['uploadFile']['size'] > $aConfig['maxsize']) {
                $sError = sprintf($GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_invalid_size'], ($sMaxFileSize / 1024));
            }

            $sFileHash = md5($sUniqFileName . $GLOBALS['TL_CONFIG']['encryptionKey'] . $sFileName);


            // If no errors, upload the image, else, output the errors
            if ($sError == '') {
                if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $sUploadpath)) {
                    $url     = $sSite . $sUploadDir . $sFileName . "&u=" . $sUniqFileName . "&c=" . $sFileHash;
                    $message = sprintf($GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_successful'], $sFileName, number_format($_FILES['uploadFile']['size'] / 1024, 3, '.', ''));
                    $sReturn = "window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$message')";
                } else {
                    $message = $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_error'];
                    $sReturn = "window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', '$message')";
                }
            } else {
                $sReturn = "window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', '$sError')";
            }
        } else {


            if (!empty($_FILES['uploadFile']['name'])) {
                $sExtension  = pathinfo($_FILES['uploadFile']['name']);
                $sType       = $sExtension['extension'];       // gets extension

                if (!in_array($sType, $aConfig['type'])) {
                    $message = sprintf($GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_invalid_extension'], $_FILES['uploadFile']['name']);
                }

            }

            if (!empty($_FILES['uploadFile']['size'])) {
                // Checks if the file has allowed type, size, width and height (for images)
                if ($_FILES['uploadFile']['size'] > $aConfig['maxsize']) {
                    $message = sprintf($GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_invalid_size'], ($sMaxFileSize / 1024));
                }
            }

            if(empty($message)){
                $message = $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['file_upload_error'];
            }


            $sReturn = "window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', '$message')";
        }
    } catch (Exception $e) {
        $sReturn = "window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', '" . $GLOBALS['TL_LANG']['MSC']['C4G_ERROR']['exception_'.$e->getCode()] . "')";
    }

    echo "<script>$sReturn;</script>";
