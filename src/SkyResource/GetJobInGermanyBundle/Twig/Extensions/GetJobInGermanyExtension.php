<?php
// src/SkyResource/GetJobInGermanyBundle/Twig/Extensions/BloggerBlogExtension.php

namespace SkyResource\GetJobInGermanyBundle\Twig\Extensions;

class GetJobInGermanyExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'created_ago' => new \Twig_Filter_Method($this, 'createdAgo'),
            'transform_newlines' => new \Twig_Filter_Method($this, 'transformNewlines'),
        );
    }
    
    // filter function that accepts DateTime object and transforms it into "ago" date string
    public function createdAgo(\DateTime $dateTime)
    {
        $delta = time() - $dateTime->getTimestamp();
        if ($delta < 0)
            throw new \InvalidArgumentException("createdAgo is unable to handle dates in the future");

        $duration = "";
        if ($delta < 60)
        {
            // Seconds
            $time = $delta;
            $duration = $time . " second" . (($time === 0 || $time > 1) ? "s" : "") . " ago";
        }
        else if ($delta < 3600)
        {
            // Mins
            $time = floor($delta / 60);
            $duration = $time . " minute" . (($time > 1) ? "s" : "") . " ago";
        }
        else if ($delta < 86400)
        {
            // Hours
            $time = floor($delta / 3600);
            $duration = $time . " hour" . (($time > 1) ? "s" : "") . " ago";
        }
        else
        {
            // Days
            $time = floor($delta / 86400);
            if ($time==1) {
                return 'yesterday';
            }
            $duration = $time . " day" . (($time > 1) ? "s" : "") . " ago";
        }

        return $duration;
    }
    
    // filter function which removes some newlines from original strings recorded in the database using pyrthon script that fetches the jobs; basically for correction of strings from database before they are shown on the web page
    public function transformNewlines($sourceString) {
        
        $order   = array("\r\n", "\n", "\r");
        $replace = '<br />';
        
        // Processes \r\n's first so they aren't converted twice.
        $resultString = str_replace($order, $replace, $sourceString);
        
        $replaceAssoc = array(
            '<br /><br /><br />' => '<br /><br />',
            '<br /><br />' => '<br />'  ,
            '<br />            <br />' => ''
            );
        
        $resultString = $this->strReplaceAssoc ($replaceAssoc,$resultString);
        
        /*
        
        $resultString = str_replace("<br /><br />", "<br />", $resultString);
        
        */
        
        return $resultString;
        
    }
    // helper function for transformNewlines($sourceString)
    private function strReplaceAssoc(array $replace, $subject) { 
        return str_replace(array_keys($replace), array_values($replace), $subject);    
    } 

    public function getName()
    {
        return 'getjobingermany_extension';
    }
}