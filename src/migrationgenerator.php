<?php

namespace MerrySean\migrationgenerator;

use Illuminate\Console\Command;
use DB;
use Artisan;

class migrationgenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate migration files from database';

    /**
     * 
     * Custom Variables
     * 
     */

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Delete all files under Sample folder
        echo "Deleting Files on Sample\n";
        $this->ClearFolder('Sample');
        //Get all table in databse
        $tables = DB::select(DB::raw('show Tables'));
        foreach ($tables as $key => $table) {
            $TableName = $table->Tables_in_dirigentloftet;
            // get all column of table
            $columns = DB::select(DB::raw('SHOW COLUMNS FROM '.$TableName));
            // Check if migration File already Exist
            if($this->MigrationExist($TableName)){
                // handle exisiting Migration File
                echo "Editing Migration File for ". $TableName."\n";
            }else{
                $this->create_migration($TableName, $columns);
            }
            
        }
    }

    private function MigrationExist($table){
        // Get all migrations files
        $files = array_diff(scandir('./database/migrations'), array('.', '..'));
        // Loop all migration files
        foreach($files as $k => $v){
            $SchemaFor = $this->MigrationForTable('./database/migrations/'.$v);
            if($SchemaFor == $table){
                return true;
            }
        }
    }

    private function MigrationForTable($file){
        $Migration = fopen($file, 'r+') or die("Unable to Open ".$file);
        // Output one line until end-of-file
        while(!feof($Migration)) {
            $line = fgets($Migration);
            // echo $line . "\n";
            $schemaLine = strpos($line, 'Schema::create(');
            if($schemaLine) {
                return explode("'",$line)[1];
            }
        }
    }

    private function ClearFolder($folder){
        // Get all migrations files
        $files = array_diff(scandir('./database/'.$folder), array('.', '..'));
        // Loop all migration files
        foreach($files as $k => $v){
            unlink('./database/'.$folder.'/'.$v) or die("Couldn't delete file");
        }
    }

    private function create_migration($table, $columns){
            echo "Creating Migration File for ". $table."\n";
            $this->call('make:migration', [
                'name' => $table,
                '--path' => 'database/Sample'
            ]);
            foreach ($columns as $key => $column) {
                // echo    $column->Field." ".
                //         $column->Type." ".
                //         $column->Null." ".
                //         $column->Key." ".
                //         $column->Default." ".
                //         $column->Extra."\n";
            }
    }
}
