<?php

namespace wiki_log_analysis;


require_once __DIR__ .'/setupDB.php';
require_once __DIR__ .'/MoreViewsArticle.php';
require_once __DIR__ .'/searchArticle.php';

use PDO;


class Analysis
{
    public PDO $pdo;

    public function __construct()
    {
        $db = new setupDB();
        $this->pdo = $db->pdo;
    }


    public function start()
    {
        
        while(true)
        {
            
            echo '最もビュー数の多い記事を表示したい場合は1を入力してください。' . PHP_EOL . '指定したドメインコードを表示したい場合は2を入力してください。' . PHP_EOL;
            echo '終了する場合は0を入力してください。' . PHP_EOL;
            $option = trim(fgets(STDIN));
                
                if($option == 1){
                    $views = new MoreViewsArticle($this->pdo);
                    $results = $views->getViews();
                    $views->showResult($results);
                    break;
                }elseif($option == 2){
                    $searchViews = new searchArticle();
                    $results = $searchViews->getSearchViews($this->pdo);
                    $searchViews->showResult($results);
                    break;
                }elseif($option == 0){
                    echo '終了します。' .PHP_EOL;
                    break;
                    
                }
            }
            
        }

        }
        
    
