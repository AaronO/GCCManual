<?php

$GLOBALS['manual'] = new DOMDocument();
$GLOBALS['manual']->loadHTMLFile('Manual.html');

function getElementByClass($classname)
{
  $xpath = new DOMXPath($GLOBALS['manual']);
  $results = $xpath->query("//*[@class='" . $classname . "']");
  if ($results->length > 0)
  {
    for ($i = 0; $i <= $results->length; $i++)
    {
      if (isset($results->item($i)->nodeValue))
      {
        $review[$i][0] = $results->item($i);
        $review[$i][1] = $results->item($i)->nodeValue;
      }
    }
    return $review;
  }
}

echo "<pre>";

$README_md = "";

$README_md .= "\n# Genome Compiler\n";

$README_md .= "User Manual\n";
$README_md .= "V.0.5\n";
$README_md .= "23rd November 2014\n";

file_put_contents('README.md',$README_md);

$SUMMARY_md = "";

foreach (getElementByClass('sectionHead') as $section)
{
  $name = preg_replace('/\PL/u', '', $section[1]);
  $SUMMARY_md .= "* [".$section[1]."](".htmlentities($name)."/README.md)\n";
  
  mkdir(htmlentities($name));
  file_put_contents(htmlentities($name).'/README.md',"Please choose a subsection");
  
  foreach (getElementByClass('subsectionHead') as $subsection)
  {
    if (strpos($subsection[1],substr($section[1],0,1).'.') !== false)
    { 
      $subname = preg_replace('/\PL/u', '', $subsection[1]);
      
      $manual_md = file_get_contents('Manual.md');
      $entry = trim(end(explode('    ',$subsection[1])));
      $preg_quote = preg_quote($entry.' {.subsectionHead}');
      $pattern = '/'.$preg_quote.'(.*?).\#\#/s';
      preg_match($pattern,$manual_md,$matches);
      $matches[1] = str_replace('![PIC](pictures/','![PIC](../../pictures/',$matches[1]);
      mkdir(htmlentities($name).'/'.htmlentities($subname));
      file_put_contents(htmlentities($name).'/'.htmlentities($subname).'/README.md',$matches[1]);
      
      $SUMMARY_md .= "\t * [".$subsection[1]."](".htmlentities($name).'/'.htmlentities($subname)."/README.md)\n";
      foreach (getElementByClass('subsubsectionHead') as $subsubsection)
      {
        if (strpos($subsubsection[1],substr($subsection[1],0,3).'.') !== false && substr($subsection[1],3,1) == " ")
        {
          $subsubname = preg_replace('/\PL/u', '', $subsubsection[1]);
          
          $manual_md = file_get_contents('Manual.md');
          $entry = trim(end(explode('    ',$subsubsection[1])));
          $preg_quote = preg_quote($entry.' {.subsubsectionHead}');
          $pattern = '/'.$preg_quote.'(.*?).\#\#/s';
          preg_match($pattern,$manual_md,$matches);
          $matches[1] = str_replace('![PIC](pictures/','![PIC](../../../pictures/',$matches[1]);
          mkdir(htmlentities($name).'/'.htmlentities($subname).'/'.htmlentities($subsubname));
          file_put_contents(htmlentities($name).'/'.htmlentities($subname).'/'.$subsubname.'/README.md',$matches[1]);
          
          $SUMMARY_md .= "\t\t * [".$subsubsection[1]."](".htmlentities($name).'/'.htmlentities($subname).'/'.htmlentities($subsubname)."/README.md)\n";
        }
        elseif (strpos($subsubsection[1],substr($subsection[1],0,4).'.') !== false)
        {
          $subsubname = preg_replace('/\PL/u', '', $subsubsection[1]);
          
          $manual_md = file_get_contents('Manual.md');
          $entry = trim(end(explode('    ',$subsubsection[1])));
          $preg_quote = preg_quote($entry.' {.subsubsectionHead}');
          $pattern = '/'.$preg_quote.'(.*?).\#\#/s';
          preg_match($pattern,$manual_md,$matches);
          $matches[1] = str_replace('![PIC](pictures/','![PIC](../../../pictures/',$matches[1]);
          mkdir(htmlentities($name).'/'.$subname.'/'.htmlentities($subsubname));
          file_put_contents(htmlentities($name).'/'.htmlentities($subname).'/'.$subsubname.'/README.md',$matches[1]);
          
          $SUMMARY_md .= "\t\t * [".$subsubsection[1]."](".htmlentities($name).'/'.htmlentities($subname).'/'.htmlentities($subsubname)."/README.md)\n";
        }
      }
    }
  }
}

file_put_contents('SUMMARY.md',$SUMMARY_md);

echo "</pre>";