<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UploadStaticSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:static';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push a static copy of the site to AWS S3';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function removePrefix($fullStr, $prefix)
    {
        if (substr($fullStr, 0, strlen($prefix)) == $prefix) {
            return substr($fullStr, strlen($prefix));
        }
        return $fullStr;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dir = storage_path('app/static');
        \File::cleanDirectory($dir);
        shell_exec('wget --recursive -p -k -P ' . $dir . ' http://localhost:8000/');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $local */
        $local = Storage::disk('local');
        /** @var \Illuminate\Filesystem\FilesystemAdapter $s3 */
        $s3 = Storage::disk('s3');

        $localBasePath = 'static/localhost:8000/';

        foreach ($local->allFiles($localBasePath) as $path) {
            // Return MIME type ala mimetype extension
            $finfo = finfo_open(FILEINFO_MIME_TYPE);

            // Get the MIME type of the file
            $file_mime = finfo_file($finfo, storage_path('app/' . $path));
            finfo_close($finfo);

            if ($path == 'static/localhost:8000/css/app.css') {
                $file_mime = 'text/css';
            }

            $s3->getDriver()->put(
                $this->removePrefix($path, $localBasePath),
                str_replace('localhost:8000', 'petiteplant.com', $local->get($path)),
                [
                    'visibility' => 'public',
                    'ContentType' => $file_mime
                ]
            );

            $this->info('uploaded ' . $this->removePrefix($path, $localBasePath));
        }
    }
}
