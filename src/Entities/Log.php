<?php namespace MatachanaInd\LogViewer\Entities;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use SplFileInfo;

/**
 * Class     Log
 *
 * @package  MatachanaInd\LogViewer\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @todo     Add a stats method
 */
class Log implements Arrayable, Jsonable, JsonSerializable
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var string */
    public $date;

    /** @var string */
    private $path;

    /** @var \MatachanaInd\LogViewer\Entities\LogEntryCollection */
    private $entries;

    /** @var \SplFileInfo */
    private $file;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Log constructor.
     *
     * @param  string  $date
     * @param  string  $path
     * @param  string  $raw
     * @param  array  $files
     */
    public function __construct($date, $path, $raw, $files)
    {
        $this->date    = $date;
        $this->path    = $path;
        $this->file    = new SplFileInfo($path);
        $this->entries = (new LogEntryCollection)->load($raw);
        $this->files   = $files;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get log path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->files;
    }

    /**
     * Get file info.
     *
     * @return \SplFileInfo
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * Get file size.
     *
     * @return string
     */
    public function size()
    {
        $size_array = Array();
        foreach ($this->files as $file)  {
            $aux = new SplFileInfo($file);
            array_push($size_array, $this->formatSize($aux->getSize()));
        }
        return $size_array;
    }

    /**
     * Get file creation date.
     *
     * @return \Carbon\Carbon
     */
    public function createdAt()
    {
        $crea_array = Array();
        foreach ($this->files as $file)  {
            $aux = new SplFileInfo($file);
            array_push($crea_array, Carbon::createFromTimestamp($aux->getATime()));
        }
        return $crea_array;
    }

    /**
     * Get file modification date.
     *
     * @return \Carbon\Carbon
     */
    public function updatedAt()
    {
        $up_array = Array();
        foreach ($this->files as $file)  {
            $aux = new SplFileInfo($file);
            array_push($up_array, Carbon::createFromTimestamp($aux->getMTime()));
        }
        return $up_array;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a log object.
     *
     * @param  string  $date
     * @param  string  $path
     * @param  string  $raw
     * @param  array  $files
     *
     * @return self
     */
    public static function make($date, $path, $raw, $files)
    {
        return new self($date, $path, $raw, $files);
    }

    /**
     * Get log entries.
     *
     * @param  string  $level
     *
     * @return \MatachanaInd\LogViewer\Entities\LogEntryCollection
     */
    public function entries($level = 'all')
    {
        return $level === 'all'
            ? $this->entries
            : $this->getByLevel($level);
    }

    /**
     * Get filtered log entries by level.
     *
     * @param  string  $level
     *
     * @return \MatachanaInd\LogViewer\Entities\LogEntryCollection
     */
    public function getByLevel($level)
    {
        return $this->entries->filterByLevel($level);
    }

    /**
     * Get log stats.
     *
     * @return array
     */
    public function stats()
    {
        return $this->entries->stats();
    }

    /**
     * Get the log navigation tree.
     *
     * @param  bool  $trans
     *
     * @return array
     */
    public function tree($trans = false)
    {
        return $this->entries->tree($trans);
    }

    /**
     * Get log entries menu.
     *
     * @param  bool  $trans
     *
     * @return array
     */
    public function menu($trans = true)
    {
        return log_menu()->make($this, $trans);
    }

    /* -----------------------------------------------------------------
     |  Convert Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the log as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'date'    => $this->date,
            'path'    => $this->path,
            'entries' => $this->entries->toArray(),
            'files'    => $this->files
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Serialize the log object to json data.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Format the file size.
     *
     * @param  int  $bytes
     * @param  int  $precision
     *
     * @return string
     */
    private function formatSize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);

        return round($bytes / pow(1024, $pow), $precision).' '.$units[$pow];
    }
}
