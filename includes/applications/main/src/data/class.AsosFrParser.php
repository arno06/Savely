<?php
namespace app\main\src\data
{

    class AsosFrParser implements InterfaceParser
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
            preg_match('/<meta name="og:title" content="([^\"]+)"/', $pContent, $matches);
            return $matches[1];
        }

        static private function extractPrice($pContent)
        {
            preg_match('/<meta itemprop="price" content=\'([^\']+)\'/', $pContent, $matches);
            $price = trim($matches[1]);
            trace_r($matches);
//        preg_match('/(€|£)/', $price, $matches);
//	    trace_r($matches);
            $matches = explode(' ', $price);

            $devise = $matches[1];

            $price = trim(str_replace($devise, '', $price));
            return array("price"=>str_replace(',', '.',$price)*1, "devise"=>$devise);
        }

        static public function extractImage($pContent)
        {
            preg_match('/<meta name="og:image" content="([^"]+)"/i', $pContent, $matches);
            return $matches[1];
        }

    }
}
 