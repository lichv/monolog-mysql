<?php

namespace Logger\Monolog\Handler;

use DB;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class MysqlNoSessionHandler extends AbstractProcessingHandler{
    protected $table;

    public function __construct($table = '', $level = Logger::DEBUG, $bubble = true){
        $this->table = empty($table)?'logs'.date("Ym",time()):$table;
        parent::__construct($level, $bubble);
    }

    protected function write(array $record){
        $this->checkTable();
        $data = [
            'channel'     => $record['channel'],
            'message'     => $record['message'],
            'level'       => $record['level'],
            'level_name'  => $record['level_name'],
            'context'     => json_encode($record['context']),
            'remote_addr' => isset($_SERVER['REMOTE_ADDR'])     ? ip2long($_SERVER['REMOTE_ADDR']) : null,
            'user_agent'  => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT']      : null,
            'session_id'  => 'no session',
            'created_at'  => $record['datetime']->format('Y-m-d H:i:s')
        ];

        DB::connection()->table($this->table)->insert($data);
    }

    protected function checkTable($table=''){
        $tablename = empty($table)?'logs'.date("Ym",time()):$table;
        $result = DB::select('show tables like "'.$tablename.'"');
        if(empty($result)){
            $sql = "CREATE TABLE IF NOT EXISTS `".$tablename."` (`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,`channel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,`level` varchar(50) COLLATE utf8_unicode_ci NOT NULL,`level_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,`message` text COLLATE utf8_unicode_ci NOT NULL,`context` text COLLATE utf8_unicode_ci NOT NULL,`remote_addr` int(11) NOT NULL,`user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,`session_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,`created_at` timestamp NULL DEFAULT NULL,`updated_at` timestamp NULL DEFAULT NULL,PRIMARY KEY (`id`),KEY `logs_channel_index` (`channel`),KEY `logs_level_index` (`level`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
            DB::statement($sql);
        }
        $this->table = $tablename;
        return $tablename;
    }
}
