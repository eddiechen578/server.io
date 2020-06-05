<?php


namespace Lib;


class Exception
{
    const
        LEVEL_ERROR = 'LEVEL_ERROR',
        LEVEL_NOTICE = 'LEVEL_NOTICE',
        LEVEL_WARNING = 'LEVEL_WARNING';

    private
        $exception,
        $level,
        $message;

    public function __construct()
    {
        $this->exception = new \Exception;
    }

    public function __destruct()
    {
        unset(
            $this->exception,
            $this->level,
            $this->message
        );
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getTraceString()
    {
        $trace = explode("\n", $this->exception->getTraceAsString());

        $trace = array_reverse($trace); // reverse array to make steps line up chronologically

        $trace = array_slice($trace, 1, -2);//remove first {main} and last two (\userlog\Model::setException and call to this method)

        $length = count($trace);

        $array1 = [];
        for ($i = 0; $i < $length; ++$i) {
            $array1[] = ($i + 1) . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
        }

        return 'Exception: ' . $this->getMessage() . ' ' . implode(' ', $array1);
    }

    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function output()
    {
        try {
            throw $this->exception;
        } catch (\Exception $exception) {
            switch ($this->level) {
                case self::LEVEL_ERROR:
                    $status = $this->getTraceString();

                    die($status);

                    break;

                case self::LEVEL_NOTICE:
                    break;

                case self::LEVEL_WARNING:
                    break;
            }
        }
    }
}
