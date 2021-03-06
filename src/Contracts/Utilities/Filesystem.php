<?php namespace MatachanaInd\LogViewer\Contracts\Utilities;

use MatachanaInd\LogViewer\Contracts\Patternable;

/**
 * Interface  Filesystem
 *
 * @package   MatachanaInd\LogViewer\Contracts\Utilities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Filesystem extends Patternable
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const PATTERN_PREFIX    = 'laravel-';
    const PATTERN_DATE      = '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]';
    const PATTERN_EXTENSION = '.log';
    const PATTERN_PREFIXRT    = 'python-';
    const PATTERN_NAME      = '*-RT';
    const PATTERN_EXTENSIONRT = '.log';

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the files instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getInstance();

    /**
     * Set the log storage path.
     *
     * @param  string  $storagePath
     *
     * @return self
     */
    public function setPath($storagePath);

    /**
     * Set the log date pattern.
     *
     * @param  string  $datePattern
     *
     * @return self
     */
    public function setDatePattern($datePattern);

    /**
     * Set the log prefix pattern.
     *
     * @param  string  $prefixPattern
     *
     * @return self
     */
    public function setPrefixPattern($prefixPattern);

    /**
     * Set the log extension.
     *
     * @param  string  $extension
     *
     * @return self
     */
    public function setExtension($extension);

    /**
     * Set the log name pattern.
     *
     * @param  string  $namePattern
     *
     * @return self
     */
    public function setNamePattern($namePattern);

    /**
     * Set the log prefix pattern.
     *
     * @param  string  $prefixPattern
     *
     * @return self
     */
    public function setPrefixPatternRT($prefixPatternRT);

    /**
     * Set the log extension.
     *
     * @param  string  $extension
     *
     * @return self
     */
    public function setExtensionRT($extensionRT);

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get all log files.
     *
     * @return array
     */
    public function all();

    /**
     * Get all valid log files.
     *
     * @return array
     */
    public function logs();

    /**
     * List the log files (Only dates).
     *
     * @param  bool  $withPaths
     *
     * @return array
     */
    public function dates($withPaths = false);

    /**
     * Read the log.
     *
     * @param  string  $date
     *
     * @return string
     *
     * @throws \MatachanaInd\LogViewer\Exceptions\FilesystemException
     */
    public function read($date);

    /**
     * Delete the log.
     *
     * @param  string  $date
     *
     * @return bool
     *
     * @throws \MatachanaInd\LogViewer\Exceptions\FilesystemException
     */
    public function delete($date);

    /**
     * Get the log file path.
     *
     * @param  string  $date
     *
     * @return string
     */
    //public function path($date);
}
