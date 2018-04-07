<?php 

class App {

	public function env()
	{
		return ENVIRONMENT;
	}

	public function isProd()
	{
		return (ENVIRONMENT == 'production');
	}

	public function redirect($url)
	{
		redirect($url, 'refresh');
	}
}