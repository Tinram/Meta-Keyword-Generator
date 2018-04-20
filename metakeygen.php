#!/usr/bin/env php
<?php

/**
    * MetaKeyGen
    *
    * Meta keyword tag generator from static HTML file,
    * Created after a website utility disappeared in 2005, with word counter inspiration by Isaac Gouy.
    *
    * Coded to PHP 5.4+
    *
    * Usage:
    *        php metakeygen.php <filename>
    *
    * @author        Martin Latter
    * @copyright     Martin Latter, May 2005 (revised 2015)
    * @version       2.21
    * @license       GNU GPL v3.0
    * @link          https://github.com/Tinram/Meta-Keyword-Generator.git
*/


define('DUB_EOL', PHP_EOL . PHP_EOL);


if ( ! isset($_SERVER['argv'][1]))
{
    $sUsage = PHP_EOL . ' ' . basename($_SERVER['argv'][0], '.php') . DUB_EOL . "\tusage: " . basename($_SERVER['argv'][0], '.php') . ' <filename>' . DUB_EOL;
    die($sUsage);
}

$sFilename = $_SERVER['argv'][1];

if ( ! file_exists($sFilename))
{
    die(PHP_EOL . ' \'' . $sFilename . '\' does not exist in this directory!' . DUB_EOL);
}

$sFileData = file_get_contents($sFilename);

if ( ! $sFileData)
{
    die(' Error reading file: ' . $sFilename . DUB_EOL);
}


$sFileData = strip_tags(strtolower($sFileData));

$aWordCount = [];
$aPhraseCount = [];
$aPhrases = [];


$aWords = preg_split('/[^a-zA-Z]+/', $sFileData);
$iNumPhrases = preg_match_all('/\w+\s+\w+/', $sFileData, $aPhrases);


foreach ($aWords as $sWord)
{
    isset($aWordCount[$sWord]) ? $aWordCount[$sWord]++ : $aWordCount[$sWord] = 1;
}


foreach ($aPhrases[0] as $sPhrase)
{
    isset($aPhraseCount[$sPhrase]) ? $aPhraseCount[$sPhrase]++ : $aPhraseCount[$sPhrase] = 1;
}


arsort($aWordCount);
arsort($aPhraseCount);


echo buildMetaTags($aWordCount, $aPhraseCount);


exit;

#####


/**
    * Build meta tag string from words and phrases arrays.
    *
    * @param   array $aWords, word-count pairs
    * @param   array $aPhrases, phrase-count pairs
    * @return  string, meta tag HTML
*/

function buildMetaTags(array $aWords, array $aPhrases)
{
    $aKW = [];

    $aCommonWords = ['the','of','and','to','a','in','that','i','it','he','was','is','for','his','with','as','be','not','by','on','but','which','they','had','have','you','at','or','from','this','her','all','are','my','him','their','were','she','them','so','one','an','me','we','when','who','said','no','there','been','if','shall','will','would','what','out','more','into','up','has','other','some','then','upon','its','man','than','any','may','do','very','now','could','time','your','great','these','only','our','such','lord','unto','people','can','made','about','should','like','before','over','after','us','did','mr','two','those','little','down','came','it\'s','go','under','across','s','nbsp','']; # 100+ most common English words

    foreach ($aWords as $sWord => $iCount)
    {
        if ( ! in_array($sWord, $aCommonWords))
        {
            if ($iCount > 1)
            {
                $aKW[] = $sWord;
            }
        }
    }

    foreach ($aPhrases as $sPhrase => $iCount)
    {
        if ($iCount > 1)
        {
            $aKW[] = $sPhrase;
        }
    }

    return PHP_EOL . '<meta name="keywords" content="' . join(',', $aKW) . '">' . DUB_EOL;
}
