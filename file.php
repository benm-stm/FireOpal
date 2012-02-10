<?php

$filename  = '/home/ounis/AutomaticTests/tests/tuleap.rb';
$className = basename($filename, '.rb');
$file      = file_get_contents($filename);

#### Include & require
$fileContent = "require \"rubygems\"\n";
$fileContent .= "gem \"rspec\"\n";
$fileContent .= "require \"rspec/autorun\"\n";
$fileContent .= "require '".$className."'\n";

#### Class definition turned on describe
#### setup, teardown & login call
$pattern = '/class (\w+)/i';
$replacement = 'describe ${1} do 	
    before(:each) do
        @bowling = ${1}.new 
        @bowling.setup() 
        @bowling.login()
    end

    after(:each) do
        @bowling.teardown()
    end';

$fileContent .= preg_replace($pattern, $replacement, $file);

$pattern = '/def setup(.*)\send/isUe';
$fileContent = preg_replace($pattern, '', $fileContent);

#### teardown
$pattern     = '/def teardown(.*)\send/isUe';
$fileContent = preg_replace($pattern, '', $fileContent);

#### login
$pattern     = '/def login(.*)\send/isUe';
$fileContent = preg_replace($pattern, '', $fileContent);


$pattern     ='/def (\w+)\s(.*)\send/isUe';

$fileContent = preg_replace($pattern, '"$1"', $fileContent);

$fileContent = preg_replace('/\s+\n/', "\n", $fileContent);

file_put_contents($className.'_spec.rb', $fileContent);

?>
