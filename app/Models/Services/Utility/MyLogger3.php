<?php
namespace App\Models\Services\Utility;

use Monolog\Logger;
use Monolog\Handler\LogglyHandler;
use Monolog\Formatter\LineFormatter;

class MyLogger3 implements ILogger{
    private static $logger = null;   

    public static function getLogger()
    {
        if(self::$logger == null){ 
            self::$logger = new Logger('MyAppLoggly'); 
            $loggly = new LogglyHandler('6ec9d479-76ef-4406-853f-5a284c8909e2/tag/monolog');
            $loggly->setFormatter(new LineFormatter("%datetime% : %level_name% : %message% %context%\n", "g:iA n/j/Y"));

            self::$logger->pushHandler($loggly);
        }
        return self::$logger;
    }

    public static function debug($message, $data = array())
    {
        self::getLogger()->debug($message, $data);
    }
    
    public static function info($message, $data = array())
    {
        self::getLogger()->info($message, $data);
    }
    
    public static function warning($message, $data = array())
    {
        self::getLogger()->warning($message, $data);
    }
    
    public static function error($message, $data = array())
    {
        self::getLogger()->error($message, $data);
    }
}
