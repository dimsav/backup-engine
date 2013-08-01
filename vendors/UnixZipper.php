<?php
/**
 * Author: http://twitter.com/dimsav
 *
 * Compress a file or folder using the unix zip function. Can compress using a password or can exclude some files or folders.
 * Note: Won't work in windows.
 *
 */
class UnixZipper
{
    // Basic parameters
    private $pathToBeZipped; // can be both file or folder
    private $pathToBeZippedName;
    private $pathToBeZippedParentPath;

    private $zipFilePath;
    private $zipFileDirectoryPath;
    private $zipFileName;

    private $password;
    private $timestampFormat = 'Y-m-d_H.i';
    private $excludes = array();

    private $command = '';

    // Dependencies
    private $log;
    private $utilities;


    function __construct(KLogger $log, Utilities $utilities)
    {
        $this->log = $log;
        $this->utilities = $utilities;
    }

    public function setPathToBeZipped($path)
    {
        $this->pathToBeZipped           = $path;
        $this->pathToBeZippedParentPath = dirname($this->pathToBeZipped);
        $this->pathToBeZippedName       = basename($this->pathToBeZipped);
    }

    public function setZipFileDirectoryPath($directory)
    {
        $this->zipFileDirectoryPath = $directory;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setExcludes(array $excludes)
    {
        $this->excludes = $excludes;
    }

    public function setTimestampFormat($format)
    {
        $this->timestampFormat = $format;
    }


    private function cd($path)
    {
        $this->command .= "cd $path; ";
    }

    public function compress()
    {
        if ( !$this->isInputValid())
        {
            return false;
        }

        $zipFilePath    = $this->getZipFilePath();
        $passwordOption = $this->getPasswordOption();
        $excludesOption = $this->getExcludesOption();

        $this->cd($this->pathToBeZippedParentPath);

        // -r zips recursively
        $this->command.=  "zip $passwordOption $excludesOption -r $zipFilePath $this->pathToBeZippedName";

        return exec($this->command);
    }

    private function getPasswordOption()
    {
        return $this->password != '' ? "-P $this->password " : '';
    }

    private function isInputValid()
    {
        $isInputValid = true;
        if (!is_dir($this->zipFileDirectoryPath))
        {
            $this->log->logError("$this->zipFileDirectoryPath is not a directory.");
            $isInputValid = false;
        }
        if (!$this->utilities->isValidPath($this->pathToBeZipped))
        {
            $this->log->logError("$this->pathToBeZipped is not a directory of file.");
            $isInputValid = false;
        }
        return $isInputValid;
    }

    public function getZipFilePath()
    {
        $this->determineZipFilePath();
        return $this->zipFilePath;
    }

    private function determineZipFilePath()
    {
        $this->determineZipFileName();

        if($this->zipFileDirectoryPath != '' && substr($this->zipFileDirectoryPath, -1, 1) != DIRECTORY_SEPARATOR)
        {
            $this->zipFileDirectoryPath .= DIRECTORY_SEPARATOR;
        }

        // Add path to destination file
        $this->zipFilePath = $this->zipFileDirectoryPath . $this->zipFileName;

    }

    private function determineZipFileName()
    {
        if ($this->zipFileName) return;
        $timestamp = date($this->timestampFormat);

        $this->zipFileName = "{$timestamp}_$this->pathToBeZippedName";
        $this->zipFileName .= $this->fileNameExtensionIsNot($this->zipFileName, 'zip') ? '.zip' : '';
    }

    private function fileNameExtensionIsNot($fileName, $extension)
    {
        return Utilities::getFileNameExtension($fileName) != $extension;
    }

    private function getExcludesOption()
    {
        if (!$this->excludes) return '';

        $excludesOption = ''; // -x folder/\* -x file.zip

        foreach ($this->excludes as $exclude)
        {
            if ($this->utilities->isValidPath($exclude) && $this->isPathInsidePathToBeZipped($exclude) )
            {
                $convertedExclude = $this->getExcludeForZipOption($exclude);

                if (is_dir($exclude))
                {
                    $convertedExclude .= $this->makeExcludePathRecursive($convertedExclude);
                }

                $excludesOption .= "-x $convertedExclude ";
            }
        }

        return $excludesOption;
    }

    private function getExcludeForZipOption($path)
    {
        $pathDirectory = "$this->pathToBeZippedParentPath/";

        return substr($path, strlen($pathDirectory));
    }

    private function isPathInsidePathToBeZipped($path)
    {
        return strpos($path, "$this->pathToBeZippedParentPath/") === 0;
    }

    private function makeExcludePathRecursive($excludePath)
    {
        return $this->isLastCharacterASlash($excludePath) ? '\*' : '/\*';
    }

    private function isLastCharacterASlash($string)
    {
        return substr($string, -1, 1) == '/';
    }


}
