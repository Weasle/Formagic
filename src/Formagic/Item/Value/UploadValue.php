<?php
/**
 * Formagic
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at
 * http://www.formagic-php.net/license-agreement/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@formagic-php.net so we can send you a copy immediately.
 *
 * @category    Formagic
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Represents item values after file upload is initiated. Implements a magic toString method, so that any default rules
 * or filter will not fail when applied to this value.
 *
 * @category    Formagic
 * @package     Item\Value
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @version     $Id: Upload.php 173 2012-05-16 13:19:22Z meweasle $
 */
class Formagic_Item_Value_UploadValue
{
    /**
     * Original name of uploaded file
     * @var string
     */
    private $fileName;

    /**
     * Mime type of uploaded file
     * @var string
     */
    private $mimeType;

    /**
     * Server path of uploaded file
     * @var string
     */
    private $uploadedFilePath;

    /**
     * Upload status
     * @var integer
     */
    private $uploadErrorStatus;

    /**
     * Size in byte of uploaded file
     * @var integer
     */
    private $fileSize;

    /**
     * @param string $fileName
     * @param string $mimeType
     * @param string $uploadedFilePath
     * @param integer $uploadError
     * @param integer $fileSize
     */
    public function __construct($fileName, $mimeType, $uploadedFilePath, $uploadError, $fileSize)
    {
        $this->fileName = $fileName;
        $this->mimeType = $mimeType;
        $this->uploadedFilePath = $uploadedFilePath;
        $this->uploadErrorStatus = $uploadError;
        $this->fileSize = $fileSize;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->fileName;
    }

    /**
     * Returns the file name of the uploaded file.
     *
     * @return string File name
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Returns upload error status if a file is already uploaded.
     *
     * Can one of the following PHP constant values:
     *  - UPLOAD_ERR_OK
     *  - UPLOAD_ERR_INI_SIZE
     *  - UPLOAD_ERR_FORM_SIZE
     *  - UPLOAD_ERR_PARTIAL
     *  - UPLOAD_ERR_NO_FILE
     *  - UPLOAD_ERR_NO_TMP_DIR
     *  - UPLOAD_ERR_CANT_WRITE
     *  - UPLOAD_ERR_EXTENSION
     * @see http://php.net/manual/en/features.file-upload.errors.php for details
     *
     * @return integer Error status
     */
    public function getUploadErrorStatus()
    {
        return $this->uploadErrorStatus;
    }

    /**
     * Returns file size of the uploaded file in byte.
     *
     * @return integer File size in byte
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }
}