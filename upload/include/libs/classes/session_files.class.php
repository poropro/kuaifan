<?php 
class session_files {
    function __construct() {
		$path = kf_class::run_config('system', 'session_n') > 0 ? kf_class::run_config('system', 'session_n').';'.kf_class::run_config('system', 'session_savepath')  : kf_class::run_config('system', 'session_savepath');
		ini_set('session.save_handler', 'files');
		session_save_path($path);
		session_start();
    }
}
?>