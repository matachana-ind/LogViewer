<?php namespace MatachanaInd\LogViewer\Utilities;

use MatachanaInd\LogViewer\Contracts\Utilities\Filesystem as FilesystemContract;
use MatachanaInd\LogViewer\Exceptions\FilesystemException;
use Illuminate\Filesystem\Filesystem as IlluminateFilesystem;

/**
 * Class     Filesystem
 *
 * @package  MatachanaInd\LogViewer\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Filesystem implements FilesystemContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The base storage path.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * The log files prefix pattern.
     *
     * @var string
     */
    protected $prefixPattern;

    /**
     * The log files date pattern.
     *
     * @var string
     */
    protected $datePattern;

    /**
     * The log files name pattern.
     *
     * @var string
     */
    protected $namePattern;

    /**
     * The log files extension.
     *
     * @var string
     */
    protected $extension;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Filesystem constructor.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string                             $storagePath
     */
    public function __construct(IlluminateFilesystem $files, $storagePath)
    {
        $this->filesystem  = $files;
        $this->setPath($storagePath);
        $this->setPattern();
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the files instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getInstance()
    {
        return $this->filesystem;
    }

    /**
     * Set the log storage path.
     *
     * @param  string  $storagePath
     *
     * @return self
     */
    public function setPath($storagePath)
    {
        $this->storagePath = $storagePath;

        return $this;
    }

    /**
     * Get the log pattern.
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->prefixPattern.$this->datePattern.$this->extension;
    }

    /**
     * Get the log pattern.
     *
     * @return string
     */
    public function getPatternRealTime()
    {
        return $this->prefixPatternRT.$this->namePattern.$this->extensionRT;
    }

    /**
     * Set the log pattern.
     *
     * @param  string  $date
     * @param  string  $prefix
     * @param  string  $extension
     *
     * @return self
     */
    public function setPattern(
        $prefix    = self::PATTERN_PREFIX,
        $date      = self::PATTERN_DATE,
        $extension = self::PATTERN_EXTENSION,
        $prefixRT    = self::PATTERN_PREFIXRT,
        $name      = self::PATTERN_NAME,
        $extensionRT = self::PATTERN_EXTENSIONRT
    ) {
        $this->setPrefixPattern($prefix);
        $this->setDatePattern($date);
        $this->setExtension($extension);
        $this->setPrefixPatternRT($prefixRT);
        $this->setNamePattern($name);
        $this->setExtensionRT($extensionRT);

        return $this;
    }

    /**
     * Set the log date pattern.
     *
     * @param  string  $datePattern
     *
     * @return self
     */
    public function setDatePattern($datePattern)
    {
        $this->datePattern = $datePattern;

        return $this;
    }

    /**
     * Set the log name pattern.
     *
     * @param  string  $namePattern
     *
     * @return self
     */
    public function setNamePattern($namePattern)
    {
        $this->namePattern = $namePattern;

        return $this;
    }

    /**
     * Set the log prefix pattern.
     *
     * @param  string  $prefixPattern
     *
     * @return self
     */
    public function setPrefixPattern($prefixPattern)
    {
        $this->prefixPattern = $prefixPattern;

        return $this;
    }

    /**
     * Set the log prefix patternRT.
     *
     * @param  string  $prefixPatternRT
     *
     * @return self
     */
    public function setPrefixPatternRT($prefixPatternRT)
    {
        $this->prefixPatternRT = $prefixPatternRT;

        return $this;
    }

    /**
     * Set the log extension.
     *
     * @param  string  $extension
     *
     * @return self
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Set the log extension.
     *
     * @param  string  $extension
     *
     * @return self
     */
    public function setExtensionRT($extensionRT)
    {
        $this->extensionRT = $extensionRT;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get all log files.
     *
     * @return array
     */
    public function all()
    {
        return $this->getFiles('*'.$this->extension);
    }

    /**
     * Get all valid log files.
     *
     * @return array
     */
    public function logs()
    {
        $result = $this->getFiles($this->getPattern());
        $aux = $this->getFiles($this->getPatternRealTime());
        foreach ($aux as $filename) {
            array_push($result, $filename);
        }
        return $result;
    }

    /**
     * List the log files (Only dates).
     *
     * @param  bool  $withPaths
     *
     * @return array
     */
    public function dates($withPaths = false)
    {
        $combined = [];
        $files = array_reverse($this->logs());
        $dates = $this->extractDates($files);
        if ($withPaths) {
            $combined = $this->combo($dates, $files);
        }
        return $combined;
    }

    public function combo($a, $b) {
        $group = [];
        foreach ($a as $key => $value)  {
            //print_r($value);
            if (!isset($group[$value])) {
                $group[$value] = [];
            }
            array_push($group[$value], $b[$key]);
        }
        return $group;
    }

    /**
     * Read the log.
     *
     * @param  string  $date
     *
     * @return string
     *
     * @throws \MatachanaInd\LogViewer\Exceptions\FilesystemException
     */
    public function read($date)
    {
        try {
            $files = $this->dates(true);
            $filter = [];
            foreach ($files as $key => $value) {
                if ($key == $date) {
                    $filter = $value;
                }
            }
            
            $log = "";
            foreach ($filter as $key => $value) {
                if ( ! $this->filesystem->exists($value)) {
                    throw new FilesystemException("The log(s) could not be located at : $value");
                }
                $log .= $this->filesystem->get(realpath($value));
            }
        }
        catch (\Exception $e) {
            throw new FilesystemException($e->getMessage());
        }
        $result = $this->order_text($log);
        return $result;
    }

    public function order_text($log)
    {
        $regex = '/\[([0-9]{4}-[0-9]{2}-[0-9]{2})\s([0-9]{2}:[0-9]{2}:[0-9]{2})\]\s{1}([a-zA-Z]+)\.([a-zA-Z]+)\:\s(.*)/';

        $result = Array();
        $j = 0;
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $log) as $line){
            if (preg_match($regex, $line, $matches, PREG_OFFSET_CAPTURE)) {
                $last = end($matches);
                $line = substr($line, $last[1] + strlen($last[0]) + 1);
                $strings = Array();
                /*
                INPUT ARRAY = matches
                Group 1.	`2018-11-02`
                Group 2.	`11:08:36`
                Group 3.	`local`
                Group 4.	`ERROR`
                Group 5.	`xxxxxx`
                */
                for ($i = 1; $i < 6; $i++) {
                    if ($i == 2) {
                        $strings[0] .= " " . $matches[$i][0];
                    } else {
                        array_push($strings, $matches[$i][0]);
                    }
                }
                /*
                OUTPUT ARRAY = matches
                [0] Group 1.	`2018-11-02 11:08:36`
                [1] Group 2.	`local`
                [2] Group 3.	`ERROR`
                [3] Group 4.	`xxxxxx`
                */
                array_push($result, $strings);
                $j++;
                $first = True;
            } else {
                $result[$j-1][3] .= "\n$line";
            }
            if (substr($result[$j-1][3], -1) == "\n") {
                $result[$j-1][3] = substr($result[$j-1][3], 0, -1);
            }
        }
        $normal_array = $result;
        $sort_array = $result;

        usort($sort_array, function($a, $b) {
            return strtotime($a[0]) - strtotime($b[0]);
        });
        // From array to text
        $text_output = "";
        foreach ($sort_array as $data) {
            $text_output .= "[" . $data[0] . "] " . $data[1] . "." . $data[2] . ": " . $data[3] . "\n";
        }

        return $text_output;
    }

    /**
     * Delete the log.
     *
     * @param  string  $date
     *
     * @return bool
     *
     * @throws \MatachanaInd\LogViewer\Exceptions\FilesystemException
     */
    public function delete($date)
    {
        $files = $this->dates(true);
        $filter = [];
        foreach ($files as $key => $value) {
            if ($key == $date) {
                $filter = $value;
            }
        }
        foreach ($filter as $key => $value) {
            if ( ! $this->filesystem->exists(realpath($value))) {
                throw new FilesystemException("The log(s) could not be located at : $value");
            } elseif ( ! $this->filesystem->delete(realpath($value))) {
                throw new FilesystemException('There was an error deleting the log.');
            }
        }
        return true;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get all files.
     *
     * @param  string  $pattern
     *
     * @return array
     */
    private function getFiles($pattern)
    {
        $pattern_search = $this->storagePath.DS.$pattern;
        $files = $this->filesystem->glob(
            $pattern_search, GLOB_BRACE
        );

        return array_filter(array_map('realpath', $files));
    }

    /**
     * Extract dates from files.
     *
     * @param  array  $files
     *
     * @return array
     */
    private function extractDates(array $files)
    {
        $aux = array_map(function ($file) {
            $a = extract_date(basename($file));
            return $a;
        }, $files);
        
        // set today to RT logs
        foreach ($aux as $key => $value) {
            $x = $value;
            if (!(date('Y-m-d', strtotime($x)) == $x)) {
              // NO it's a date
              $aux[$key] = date("Y-m-d");
            }
        }
        return $aux;
    }
}
