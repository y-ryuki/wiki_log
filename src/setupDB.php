<?php

namespace wiki_log_analysis;

use PDO;
use PDOException;

class setupDB
{

    private $dbName;
    private $dbUser;
    private $dbPass;
    private $dns;
    public $pdo;
    public function __construct()
    {

        $this->dbName = $_ENV['MYSQL_DATABASE'];
        $this->dbUser = $_ENV['MYSQL_USER'];
        $this->dbPass = $_ENV['MYSQL_PASSWORD'];
        $this->dns = 'mysql:host=db;dbname=log_db';
        $this->pdo = $this->dbConnect();
        $this->checkExistData();
    }

    
    public function dbConnect() :PDO
    {
            try{
                $pdo = new PDO($this->dns, $this->dbUser, $this->dbPass,[
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_LOCAL_INFILE => 1
                ]);
                
                
            }catch(PDOException $e){
                echo "データベースへの接続に失敗しました。" . $e->getMessage() . PHP_EOL;
                exit();
            }
            
            echo 'データベースへの接続に成功しました。' .PHP_EOL;
            return $pdo;
        }
        
        
        
        
        public function importLogFile() :void
        {
            echo 'ログファイルをデータベースにインポートしています。' . PHP_EOL;

            $this->createTable();
            
            $sql = <<<SQL
            LOAD DATA LOCAL INFILE './log_file/pageviews'
            INTO TABLE page_views
            FIELDS TERMINATED BY ' '
            SQL;
            
            try {
                $this->pdo->exec($sql);
            } catch (PDOException $e) {
                exit('インポートに失敗しました。'. PHP_EOL . $e);
            }
            
            $this->createIndex();
            echo 'インポートが完了しました.' . PHP_EOL;
            echo PHP_EOL;
        }
        
        
        public function createTable() :void
        {
        $sql = <<<SQL
        DROP TABLE IF EXISTS page_views;
        CREATE TABLE page_views (
            domain_code VARCHAR(255),
            page_title VARCHAR(500),
            count_views INTEGER,
            total_response_size INTEGER,
            PRIMARY KEY (domain_code, page_title)
        )
        SQL;
        
        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            exit('テーブルの初期化に失敗しました' . $e);
        }
        
        echo 'テーブルを作成しました。' . PHP_EOL;
    }


    private function createIndex(): void
    {
        $sql = <<<SQL
        CREATE INDEX
            count_views_index
        ON
            page_views (count_views)
        SQL;

        try {
            $this->pdo->exec($sql);
            echo 'テーブルを最適化にしました。' .PHP_EOL;
        } catch (PDOException $e) {
            echo 'テーブルの最適化に失敗しました' . $e;
        }
    }



    private function checkExistData(): void
    {
        $sql = <<<SQL
        SELECT
            *
        FROM
            page_views
        LIMIT
            1
        SQL;

        try {
            echo '既存のデータがあります。' . PHP_EOL;
            $this->pdo->query($sql);
            $this->importConfirm();

        } catch (PDOException $e) {
            $this->importLogFile();
        }
    }

    public function importConfirm() :void
    {
        while (true) {
            echo "データベースをインポートしますか？". PHP_EOL;
            echo "はい , いいえで入力してください。". PHP_EOL;
            $input = trim(fgets(STDIN));
            if ($input === 'はい') {
                $this->importLogFile();
                break;
            } elseif ($input === 'いいえ') {
                break;
            } else {
                echo '入力が正しくありません' . PHP_EOL;
            }
        }


    }

}
    
    
    
    
    
    