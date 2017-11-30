<?php

class writeLog
{
	// テキストを作成した場所
	private $want_path = '/attending/senmon_kokunai/phpsc/';
	private $file_name = 'writeLogFile.txt';
	private $file_path;

	function __construct()
	{
		// りーふねっと環境以外なら
		if($_SERVER['HTTP_HOST'] != 'test-hankyu-travel.leafnet.jp') return;


		$this->file_path = ($_SERVER['DOCUMENT_ROOT'] . $this->want_path);

		// ファイルの存在確認
		if( !file_exists($this->file_path.$this->file_name) ){
			// ファイル作成
			touch( $this->file_path.$this->file_name );
		}

		//　空にする
		$fp = fopen($this->file_path.$this->file_name, 'r+');

		//2番目の引数のファイルサイズを0にして空にする
		ftruncate($fp,0);

		fclose($fp);
	}

	// 書き出し
	function write($log)
	{
		// りーふねっと環境以外なら
		if($_SERVER['HTTP_HOST'] != 'test-hankyu-travel.leafnet.jp') return;

		$fp = fopen($this->file_path.$this->file_name, "ab");

		// var_dumpを記述できるようにする
		ob_start();
		var_dump($log);
		$result =ob_get_contents();
		ob_end_clean();

		fputs($fp, $result);
		fclose($fp);
	}




}
