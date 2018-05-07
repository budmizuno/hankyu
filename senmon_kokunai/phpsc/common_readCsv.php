<?php

/*
#################################################################
	���X���ʂŎ���CSV�ǂݍ��݃t�@�C��
#################################################################
*/


/*
*  CSV�ǂݍ��݃N���X
*/
class common_readCsv
{
    public function readCsv($file)
    {
        $CsvAry = array();
        if (file_exists($file)) {
            // CSV�t�@�C�����I�[�v��
	        $handle = fopen($file, "r");
	        $csvdata = array();
	        if ($handle) {
	            $num = 0;
	
	                // ���ׂĂ̍s��ǂݍ���
	            while (!feof($handle)) {
	
	                $buffer = rtrim(fgets($handle, 9999));
	                $buffer = str_replace('"', '', $buffer);
	
	                if (empty($buffer)) {
	                    continue;
	                }
	                    // 2�s�ڂ͓��{��ł̐����s�Ȃ̂ŏȂ�
	                if ($num == 1) {
	                    ++$num;
	                    continue;
	                }
	                    // 1�s���o��
	                $data = explode("\t", $buffer);
	
	                    // 1�s�ڂ̎�
	                if ($num == 0) {
	                    $keyAry = array();
	                        // key�Ɏg�p����
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
	                        // �z���CSV�̍��ڂ����Ă���
	                    $CsvAry[] = $csvdata;
	                }
	            }
	            fclose($handle);
	        }
    	}


        return $CsvAry;
    }

}
