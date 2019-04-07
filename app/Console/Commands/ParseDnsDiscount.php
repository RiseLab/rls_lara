<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ParseDnsDiscount extends Command
{
	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var Crawler
	 */
	protected $crawler;

	/**
	 * @var string
	 */
	protected $url = 'https://www.dns-shop.ru/catalogMarkdown/markdown/update/?group=none&offset=0';

	/**
	 * @var array
	 */
	protected $options = [
		'verify' => false,
		'referer' => true,
		'headers' => [
			'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36',
			'Referer' => '',
			'X-Requested-With' => 'XMLHttpRequest',
			'Cookie' => 'current_path=e0b5683ebfb7c198073d937c8f22602f83fe9ec2ad7168e4713550d7c4664effa%3A2%3A%7Bi%3A0%3Bs%3A12%3A%22current_path%22%3Bi%3A1%3Bs%3A36%3A%22a9f47dbf-f564-11de-97f8-00151716f9f5%22%3B%7D',
		],
	];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:dns-discount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DNS shop markdown catalog parser';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->client = new Client();
    	$this->crawler = new Crawler();
    	$this->options['headers']['Referer'] = parse_url($this->url, PHP_URL_SCHEME) . '://' . parse_url($this->url, PHP_URL_HOST);
        parent::__construct();
    }

	/**
	 * Execute the console command.
	 *
	 * @return void
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
    public function handle()
    {
    	try {
		    $this->info('Getting catalog page ' . $this->url);
		    $page = $this->client->request('GET', $this->url, $this->options);
		    dd($page);
	    } catch (\Exception $e) {
		    $this->error($e->getMessage());
	    }
    }
}
