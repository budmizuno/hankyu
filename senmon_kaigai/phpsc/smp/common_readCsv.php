<?php

include_once(dirname(__FILE__) . '/../getCsvItemClass.php');

class common_readCsv
{
    public function readCsv($file)
    {
        $handle = fopen($file, "r");
        $csvdata = array();
        if ($handle) {
            $num = 0;

            while (!feof($handle)) {

                $buffer = rtrim(fgets($handle, 9999));
                $buffer = str_replace('"', '', $buffer);

                if (empty($buffer)) {
                    continue;
                }
                if ($num == 1) {
                    ++$num;
                    continue;
                }

                $data = explode("\t", $buffer);




                if ($num == 0) {
                    $keyAry = array();

                    foreach ($data as $no => $val) {
                        if (empty($val)) {
//                            continue;
                            // 項目名がないなら添字を入れる
                            $val = $no;
                        }
                        $keyAry[$no] = $val;
                    }

                    // 拠点自由と拠点特集のCSVは最後の項目が空白なので特別
                    if($file == KYOTEN_FREE_CSV_URL || $file == KYOTEN_TOKUSYU_CSV_URL)
                    {
                        $keyAry[count($data)] = count($data);
                    }
                    ++$num;
                } else {
                    foreach ($keyAry as $no => $key) {
                        $csvdata[$key] = $data[$no];
                    }
                    $CsvAry[] = $csvdata;
                }
            }
            fclose($handle);
        }


        return $CsvAry;
    }

}
?>
