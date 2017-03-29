<?php

namespace Logger\Monolog\Handler;

use DB;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class MysqlHandler extends AbstractProcessingHandler{
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
            'session_id'  => \Session::getId(),
            'created_at'  => $record['datetime']->format('Y-m-d H:i:s')
        ];

        DB::connection()->table($this->table)->insert($data);
    }

    protected function checkTable($table=''){
        $tablename = empty($table)?'logs'.date("Ym",time()):$table;
        $result = DB::select('show tables like "'.$tablename.'"');
        if(empty($result)){
            $result = DB::statement('create table '.$tablename.' like `logs`');
            return empty($result)?$tablename:false;
        }
        $this->table = $tablename;
        return $tablename;
    }
}
