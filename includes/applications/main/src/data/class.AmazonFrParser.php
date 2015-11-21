<?php
namespace app\main\src\data
{
    class AmazonFrParser implements InterfaceParser
    {
        static public function parse($pContent)
        {

            $p = self::extractPrice($pContent);
            $title = self::extractTitle($pContent);
            $canonical = self::extractCanonical($pContent);
            $image = self::extractImage($pContent);
            return array(
                "price"=>$p,
                "title"=>$title,
                "canonical"=>$canonical,
                "image"=>$image
            );
        }

        static private function extractCanonical($pContent)
        {
            preg_match('/\<link rel="canonical" href="([^\"]+)"/', $pContent, $matches);
            return $matches[1];
        }

        static private function extractTitle($pContent)
        {
            preg_match('/id="productTitle"[^\>]*\>([^\<]+)/', $pContent, $matches);
            if(empty($matches))
            {
                preg_match('/\<title\>([^\<]+)/', $pContent, $matches);
                $matches[1] = explode(':', $matches[1]);
                $matches[1] = $matches[1][0];
            }
            return $matches[1];
        }

        static private function extractPrice($pContent)
        {
            preg_match('/<span id="priceblock_ourprice"[^>]+>([^\<]+)\<\/span\>/', $pContent, $matches);
            if (empty($matches))
            {
                preg_match("/\<span class='a-color-price[^']*'\>([^\<]+)\<\/span\>/", $pContent, $matches);
                if(empty($matches))
                {
                    preg_match('/\<span class="a-color-price[^"]*"\>([^\<]+)\<\/span\>/', $pContent, $matches);
                    if(empty($matches))
                    {
                        preg_match('/\<span id="priceblock_saleprice" class="a-size-medium a-color-price"\>([^\<]+)\<\/span\>/', $pContent, $matches);
                        if(empty($matches))
                        {
                            preg_match('/\<b class="priceLarge[^"]*"\>([^\<]+)\<\/b\>/', $pContent, $matches);
                        }
                    }
                }
            }

            $price = trim($matches[1]);

            preg_match('/(EUR)/', $price, $matches);

            $devise = $matches[1];

            $price = trim(str_replace($devise, '', $price));
            return array("price"=>str_replace(',', '.',$price)*1, "devise"=>$devise);
        }

        static public function extractImage($pContent)
        {
            preg_match('/<div id="imgTagWrapperId" class="imgTagWrapper">\s+<img\s*alt="[^"]+"\s+src="([^"]+)"/i', $pContent, $matches);
            if(empty($matches))
            {
                preg_match('/<img id="imgBlkFront" src="([^"]+)"/', $pContent, $matches);
                if(empty($matches))
                {
                    preg_match('/<img id="main-image" src="([^"]+)"/', $pContent, $matches);
                    if(empty($matches))
                    {
                        preg_match('/<img id="main-image-nonjs" src="([^"]+)"/', $pContent, $matches);

                        if(empty($matches))
                        {
                            preg_match('/<img\s*class="kib-ma kib-image-ma"\s*alt="[^"]*"\s*src="([^"]+)"/', $pContent, $matches);
                        }
                    }
                }
            }
            return $matches[1];
        }

    }
}
 