<?php 
// --------------------------------------------
// | @author: Bona Brian Siagian
// | @author_url: http://www.carovl.com
// | @author_email: bonabriansiagian@gmail.com
// --------------------------------------------
class Cache
{
    function openCacheDir()
    {
        if (! file_exists('cache')) {
            $oldmask = umask(0);
            @mkdir('cache', 0777, true);
            @umask($oldmask);
        }
        if (! file_exists('cache/.htaccess')) {
            $f = @fopen('cache/.htaccess', 'a+');
            @fwrite($f, "deny from all");
            @fclose($f);
        }
        if (! file_exists('cache/index.html')) {
            $f = @fopen('cache/index.html', 'a+');
            @fclose($f);
        }
    }
    function read($file_name)
    {
        $file_name = 'cache/' . $file_name;
        if (file_exists($file_name)) {
            $handle = fopen($file_name, 'rb');
            $variable = fread($handle, filesize($file_name));
            fclose($handle);
            return unserialize($variable);
        } else {
            return null;
        }
    }
    function write($file_name, $variable)
    {
        $file_name = 'cache/' . $file_name;
        $handle = fopen($file_name, 'a');
        fwrite($handle, serialize($variable));
        fclose($handle);
    }
    function delete($file_name)
    {
        $file_name = 'cache/' . $file_name;
        @unlink($file_name);
    }
}
?>