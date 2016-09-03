<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

$getImages = function () {
    $filepaths = array_filter(Storage::files('public'), function ($file) {
        return (
            ends_with($file, '.jpeg')
            || ends_with($file, '.jpg')
            || ends_with($file, '.png')
        );
    });

    $filepaths = array_map(function ($path) {
        return str_replace('public', '/uploads/img', $path);
    }, $filepaths);

    return ['images' => array_reverse($filepaths)];
};

$getGalleryImages = function () {
    $filepaths = array_filter(Storage::files('gallery'), function ($file) {
        return (
            ends_with($file, '.jpeg')
            || ends_with($file, '.jpg')
            || ends_with($file, '.png')
        );
    });

    $filepaths = array_map(function ($path) {
        return str_replace('gallery', '/img/gallery', $path);
    }, $filepaths);

    return array_reverse($filepaths);
};

Route::get('/', function () {
    $allArticles = Article::whereNotNull('published_at')
        ->where('published_at', '<', new DateTime())
        ->get();
    return view('welcome', ['allAricles' => $allArticles, 'page' => 'home']);
});

Route::get('gallery', function () use ($getGalleryImages) {
    return view('pages.gallery', [
        'title' => 'Gallery',
        'images' => $getGalleryImages(),
        'page' => 'gallery',
    ]);
});

Route::get('workshops', function () {
    return view('pages.workshops', [
        'title' => 'Workshops',
        'page' => 'workshops',
        'content' => (new Parsedown())->parse(\Cache::get('workshop_content')),
    ]);
});

Route::get('about', function () {
    return view('pages.about', [
        'title' => 'About',
        'page' => 'about',
        'content' => (new Parsedown())->parse(\Cache::get('about_content')),
    ]);
});

Route::get('blog', function () {
    $allArticles = Article::whereNotNull('published_at')
        ->where('published_at', '<', new DateTime())
        ->get()
        ->toArray();

    $allArticles = array_map(function ($article) {
        $article['content'] = (new Parsedown)->parse($article['content']);
        return $article;
    }, $allArticles);

    return view('pages.blog', [
        'title' => 'Blog',
        'allArticles' => $allArticles,
        'page' => 'blog',
    ]);
});

Route::get('images/list', ['as' => 'list-images', $getImages]);

Route::get('uploads/img/{filename}', function ($filename) {
    if (!\Storage::exists('public/' . $filename)) {
        return response("File does not exist.", 404);
    }

    $fileContents = \Storage::get('public/' . $filename);

    return response($fileContents, 200, ['Content-Type' => ends_with($filename, '.png') ? 'image/png' : 'image/jpeg']);
});

Route::get('img/gallery/{filename}', function ($filename) {
    if (!\Storage::exists('gallery/' . $filename)) {
        return response("File does not exist.", 404);
    }

    $fileContents = \Storage::get('gallery/' . $filename);

    return response($fileContents, 200, ['Content-Type' => ends_with($filename, '.png') ? 'image/png' : 'image/jpeg']);
});

Route::get('article/{slug}', function ($slug) {
    $cache_name = 'article_' . $slug;

    $tmp = Cache::rememberForever($cache_name, function () use ($slug) {
        /** @var Article $article */
        $article = Article::whereSlug($slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<', new DateTime())
            ->firstOrFail();

        if (!$article) {
            abort(404);
        }

        $parsedown = new Parsedown();

        return ['article' => $article->toArray(), 'content' => $parsedown->text($article->content)];
    });

    $allArticles = Article::whereNotNull('published_at')
        ->where('published_at', '<', new DateTime())
        ->get();

    return view('article', array_merge($tmp, ['allArticles' => $allArticles, 'page' => 'blog']));
});

Route::get('login', function () {
    return redirect()->route('admin-panel');
});

Route::group(['prefix' => 'admin'], function () use ($getImages, $getGalleryImages) {
    Route::get('/', ['as' => 'admin-panel', function () {

        $drafts = Article::whereNull('published_at')
            ->orWhere('published_at', '>', new DateTime())
            ->get();

        $published = Article::whereNotNull('published_at')
            ->where('published_at', '<', new DateTime())
            ->orderBy('published_at', 'desc')
            ->get();

        return view('admin.dashboard', [
            'drafts' => $drafts,
            'published' => $published,
            'title' => 'Admin Dashboard'
        ]);
    }]);

    Route::get('drafts/new', ['as' => 'new-draft', function () use ($getImages) {
        return view('admin.draft', array_merge($getImages(), ['article' => [], 'title' => 'New Draft']));
    }]);

    Route::get('gallery/manage', ['as' => 'manage-gallery', function () use ($getGalleryImages) {
        return view('admin.gallery', ['title' => 'Manage Gallery', 'images' => $getGalleryImages()]);
    }]);

    Route::get('gallery/edit', ['as' => 'edit-gallery', function () {
        return view('admin.edit', [
            'title' => 'Gallery Content',
            'content' => \Cache::get('gallery_content', ''),
            'submitTo' => 'admin/gallery/save',
        ]);
    }]);

    Route::get('about/edit', ['as' => 'edit-about', function () {
        return view('admin.edit', [
            'title' => 'About Content',
            'content' => \Cache::get('about_content', ''),
            'submitTo' => 'admin/about/save',
        ]);
    }]);

    Route::get('workshop/edit', ['as' => 'edit-workshop', function () {
        return view('admin.edit', [
            'title' => 'Workshop Content',
            'content' => \Cache::get('workshop_content', ''),
            'submitTo' => 'admin/workshop/save',
        ]);
    }]);

    Route::get('drafts/edit/{id}', ['as' => 'edit-draft', function ($id) use ($getImages) {
        return view('admin.draft', array_merge($getImages(), ['article' => Article::findOrFail($id)->toArray()]));
    }]);

    Route::get('push/static', ['as' => 'push-static', function () {
        \Artisan::call('push:static');
        return redirect('admin');
    }]);

    Route::post('about/save', function (Request $request) {
        \Cache::forever('about_content', $request->get('content'));
        return redirect('admin');
    });

    Route::post('gallery/save', function (Request $request) {
        \Cache::forever('gallery_content', $request->get('content'));
        return redirect('admin');
    });

    Route::post('workshop/save', function (Request $request) {
        \Cache::forever('workshop_content', $request->get('content'));
        return redirect('admin');
    });

    Route::post('drafts/save', function (Request $request) {
        $article = new Article();
        if ($id = $request->get('article_id')) {
            $article = Article::findOrFail($id);
        }

        $publishedAt = $request->get('published_at') ?: null;

        $article->slug = $request->get('slug');
        $article->title = $request->get('title');
        $article->summary = $request->get('summary');
        $article->content = $request->get('content');
        $article->thumbnail = $request->get('thumbnail');
        $article->tags = $request->get('tags');
        $article->published_at = $request->get('is_published') ? $publishedAt : null;

        $article->save();

        $cache_name = 'article_' . $article->slug;
        Cache::forget($cache_name);

        return redirect('admin');
    });

    Route::post('drafts/render', function (Request $request) {
        return ['result' => (new Parsedown())->text($request->get('content'))];
    });

    Route::post('images/save', ['as' => 'save-image', function (\Illuminate\Http\Request $request) {
        /** @var UploadedFile $file */
        foreach ($request->allFiles() as $file) {
            $filepath = $file->getRealPath();
            $dt = new DateTime();
            $key = $dt->format('Y-m-d-H-i-s-') . str_random('5');
            shell_exec('sips -Z 1024 ' . $filepath);
            Storage::put('public/' . $key . ($file->getMimeType() == 'image/png' ? '.png' : '.jpg'), file_get_contents($filepath));
        }
        return ['success' => true];
    }]);

    Route::post('images/gallery/save', ['as' => 'save-gallery-image', function (\Illuminate\Http\Request $request) {
        /** @var UploadedFile $file */
        foreach ($request->allFiles() as $file) {
            $dt = new DateTime();
            $key = $dt->format('Y-m-d-H-i-s-') . str_random('5');
            Storage::put('gallery/' . $key . ($file->getMimeType() == 'image/png' ? '.png' : '.jpg'), file_get_contents($file->getRealPath()));
        }
        return ['success' => true];
    }]);
});
