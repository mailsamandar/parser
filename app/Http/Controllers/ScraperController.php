<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;


class ScraperController extends Controller
{
    protected string $url = "https://anaki.uz/odezhda-1/";

    protected string $scrollUrl = 'https://anaki.uz/index.php?route=extension/module/loading_goods/swd_more';

    protected array $result;

    protected $temporary;


    public function view()
    {
        $response = Http::get($this->url);

        $crawler = new Crawler($response);

        $node =  $crawler->filter('.cate-items')->eq(0);

        $categories = $node->filter('.item-cate')->each(function ($node) use ($crawler){
           return [
               'path' => $node->attr('data-value'),
               'text' => $node->text(),
               'url' => $this->findLink($crawler, $node)
           ];
        });

        return view('scrape', compact('categories'));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(Request $request)
    {
        $category = json_decode($request['category']);

        $response = Http::get($category->url);

        $crawler = new Crawler($response);

        $products = $this->productRows($crawler)->withScroll($category->path, $request['scroll_times'])->get();

        return ($products);
    }

    public function get(): array
    {
        return $this->result;
    }

    public function productRows(Crawler $DOM): static
    {
        $this->result = $DOM->filter('.product-item')->each(function ($node) {
            return [
                'title' => $node->filter('.caption h4 a')->text(),
                'image' => $node->filter('img')->eq(0)->attr('data-src') ?? 'empty',
                'manufacturer' => $node->filter('p.manufacture-product a')->text(),
                'price' => $node->filter('.price')->text(),
            ];
        });

        return $this;
    }

    public function withScroll($path, int $times = 1): static
    {
        for ($i = 0; $i < $times; $i++) {

            $result = $this->result;

            $starting_point = count($result);

            $ch = curl_init();

            $postParameter = array(
                'start' => $starting_point,
                'path' => $path
            );

            curl_setopt($ch, CURLOPT_URL,"https://anaki.uz/index.php?route=extension/module/loading_goods/swd_more");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postParameter);

            // Receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);

            curl_close ($ch);

            $content = json_decode($server_output)->response;

            $crawler = new Crawler($content);

            $scrolled_result = $this->productRows($crawler)->get();

            $this->result =  array_merge($scrolled_result, $result);
        }

        return $this;
    }

    public function findLink($DOM, $node){

        $DOM->filter('.ul-top-items a')->each(function ($a) use ($node) {
            if ($a->text() == $node->text()) {
                $this->temporary = $a->attr('href');
            }
        });

        return $this->temporary;
    }
}
