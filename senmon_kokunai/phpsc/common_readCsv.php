<?php

/*
#################################################################
	専門店共通で持つCSV読み込みファイル
#################################################################
*/


/*
*  CSV読み込みクラス
*/
class common_readCsv
{
    public function readCsv($file)
    {
        $CsvAry = array();
        if (file_exists($file)) {
            // CSVファイルをオープン
	        $handle = fopen($file, "r");
	        $csvdata = array();
	        if ($handle) {
	            $num = 0;
	
	                // すべての行を読み込む
	            while (!feof($handle)) {
	
	                $buffer = rtrim(fgets($handle, 9999));
	                $buffer = str_replace('"', '', $buffer);
	
	                if (empty($buffer)) {
	                    continue;
	                }
	                    // 2行目は日本語での説明行なので省く
	                if ($num == 1) {
	                    ++$num;
	                    continue;
	                }
	                    // 1行取り出す
	                $data = explode("\t", $buffer);
	
	                    // 1行目の時
	                if ($num == 0) {
	                    $keyAry = array();
	                        // keyに使用する
	                    foreach ($data as $no => $val) {
	                        if (empty($val)) {
	                            continue;
	                        }
	                        $keyAry[$no] = $val;
	                    }
	
	                    ++$num;
	                } else {
	                    foreach ($keyAry as $no => $key) {
	                            $csvdata[$key] = isset($data[$no]) ? $data[$no] : '';
	                    }
	                        // 配列にCSVの項目を入れていく
	                    $CsvAry[] = $csvdata;
	                }
	            }
	            fclose($handle);
	        }
    	}


        return $CsvAry;
    }

}
