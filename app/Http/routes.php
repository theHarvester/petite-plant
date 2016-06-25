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
        return str_replace('gallery', '/gallery/img', $path);
    }, $filepaths);

    return array_reverse($filepaths);
};

Route::get('/', function () {
    $allArticles = Article::whereNotNull('published_at')
        ->where('published_at', '<', new DateTime())
        ->get();

    return view('welcome', ['allAricles' => $allArticles]);
});

Route::get('gallery', function () use($getGalleryImages) {
    return view('pages.gallery', ['title' => 'Gallery', 'images' => $getGalleryImages()]);
});

Route::get('workshops', function () {
    return view('pages.workshops', [
        'title' => 'Workshops',
        'content' => (new Parsedown())->parse(\Cache::get('workshop_content')),
    ]);
});

Route::get('about', function () {
    return view('pages.about', [
        'title' => 'About',
        'content' => (new Parsedown())->parse(\Cache::get('about_content')),
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

Route::get('gallery/img/{filename}', function ($filename) {
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

    return view('article', array_merge($tmp, ['allArticles' => $allArticles]));
});

Route::get('login', function () {
    return view('screen.login');
});

Route::post('login', ['before' => 'csrf', function (Request $request) {
    if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
        // Authentication passed...
        return redirect()->route('admin-panel');
    }

    abort(404);
}]);

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () use ($getImages, $getGalleryImages) {
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
        $article->content = $request->get('content');
        $article->thumbnail = $request->get('thumbnail');
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
            $dt = new DateTime();
            $key = $dt->format('Y-m-d-H-i-s-') . str_random('5');
            Storage::put('public/' . $key . ($file->getMimeType() == 'image/png' ? '.png' : '.jpg'), file_get_contents($file->getRealPath()));
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
